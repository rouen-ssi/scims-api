<?php

namespace SciMS\Middlewares;

use SciMS\Models\AccountQuery;
use Slim\Http\Request;
use Slim\Http\Response;

class TokenMiddleware {

    const INVALID_TOKEN = 'INVALID_TOKEN';

    /**
     * Checks if the given token is valid.
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return Response a JSON containing errors if the given token is invalid.
     */
    public function __invoke(Request $request, Response $response, callable $next) {
        $token = explode(' ', $request->getHeaderLine('Authorization'))[1];

        if (!$token) {
            return $this->invalidToken($response);
        }

        // Retreives the user associated with the given token
        $account = AccountQuery::create()->findOneByToken($token);
        if (!$account) {
            return $this->invalidToken($response);
        }

        // Checks the token validity
        $today = new \DateTime();
        if ($account->getTokenExpiration() >= $today->getTimestamp()) {
            return $this->invalidToken($response);
        }

        // Add user informations to the request
        $request = $request->withAttribute('user', $account);

        return $next($request, $response);
    }

    private function invalidToken(Response $response) {
        return $response->withJson(array(
            "errors" => array(
                self::INVALID_TOKEN
            )
        ), 401);
    }

}