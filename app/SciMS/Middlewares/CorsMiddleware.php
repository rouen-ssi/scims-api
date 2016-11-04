<?php

namespace 'SciMS\Middlewares\CorsMiddleware';

/**
 * Middlewares which add CORS headers to the HTTP response.
 */
class CorsMiddleware {

  public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next) {
    $response = $next($request, $response);
    return $response->withHeader('Access-Control-Allow-Origin', '*')
      ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
      ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE');
  }

}
