<?php

namespace SciMS\Middlewares;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use SciMS\Models\User;
use SciMS\Models\UserQuery;

class TokenMiddleware {

  /**
 * Checks if the token given is valid.
 * If not, returns the INVALID_TOKEN error.
 * If the token is valid, retreives the user infos with the token and gives it to the next middleware.
 * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
 * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
 * @param  callable                                 $next     Next middleware
 *
 * @return \Psr\Http\Message\ResponseInterface
 */
  public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next) {
      $body = $request->getParsedBody();

      if (!isset($body['token'])) {
        return $this->invalidToken($response);
      }

      // Retreives the user associated with the given token
      $user = UserQuery::create()->findOneByToken($body['token']);
      if (!$user) {
        return $this->invalidToken($response);
      }

      // Checks the token validity
      $today = new \DateTime();
      if ($user->getTokenExpiration() >= $today->getTimestamp()) {
        return $this->invalidToken($response);
      }

      // Add user informations to the request
      $request = $request->withAttribute('user', $user);

      return $next($request, $response);
  }

  private function invalidToken(ResponseInterface $response) {
    return $response->withJson(array(
      "errors" => array(
        'INVALID_TOKEN'
      )
    ), 400);
  }

}
