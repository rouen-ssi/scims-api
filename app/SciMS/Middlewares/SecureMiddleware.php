<?php
/**
 * @author Antoine Chauvin <antoine.chauvin@etu.univ-rouen.fr>
 */

namespace SciMS\Middlewares;


use SciMS\Models\Account;
use Slim\Http\Request;
use Slim\Http\Response;

class SecureMiddleware {
    public static function hasRole($role) {
        return function(Request $request, Response $response, callable $next) use ($role) {
            /** @var Account $user */
            $user = $request->getAttribute('user');

            if (!$user) {
                return $response->withStatus(401);
            }

            if ($user->getRole() !== $role) {
                return $response->withStatus(401);
            }

            return $next($request, $response);
        };
    }
}