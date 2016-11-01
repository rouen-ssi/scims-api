<?php

namespace SciMS\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use SciMS\Models\User;

class AccountController {

  const INVALID_OLD_PASSWORD = 'INVALID_OLD_PASSWORD';

  public function changePassword(ServerRequestInterface $request, ResponseInterface $response) {
    $body = $request->getParsedBody();

    // Get the users from TokenMiddleware
    $user = $request->getAttribute('user');

    // Checks if the given old password matches
    if (!password_verify($body['old_password'], $user->getPassword())) {
      return $response->withJson([
        'errors' => [
          self::INVALID_OLD_PASSWORD
        ]
      ]);
    }

    // Changes the user's password
    $user->setPassword(password_hash($body['new_password'], PASSWORD_DEFAULT));
    $user->save();

    return $response->withStatus(200);
  }

}
