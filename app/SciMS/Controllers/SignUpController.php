<?php

namespace SciMS\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use SciMS\Models\User;

/**
 * Controller managing account creations (and other in the future).
 */
class SignUpController {

  /**
   * Endpoint for create an account
   * @param  ServerRequestInterface  $request  a PSR 7 Request object
   * @param  ResponseInterface       $response a PSR 7 Response object
   * @return a PSR 7 Response object containing the response.
   */
  public function post(ServerRequestInterface $request, ResponseInterface $response) {
    $body = $request->getParsedBody();

    $user = new User();
    $user->setUid(uniqid());
    $user->setEmail($body['email']);
    $user->setFirstName($body['first_name']);
    $user->setLastName($body['last_name']);
    $user->setPassword(password_hash($body['password'], PASSWORD_DEFAULT));

    if (!$user->validate()) {
      $errors = [];
      foreach ($user->getValidationFailures() as $failure) {
        $errors[] = $failure->getMessage();
      }

      return $response->withJson(array(
        'errors' => $errors
      ), 400);
    }

    $user->save();

    return $response->withStatus(200);
  }

}
