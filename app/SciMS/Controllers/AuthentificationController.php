<?php

namespace SciMS\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use SciMS\Models\User;
use SciMS\Models\UserQuery;

class AuthentificationController {

  const TOKEN_HOURS = 24;

  public function auth(ServerRequestInterface $request, ResponseInterface $response) {
    $body = $request->getParsedBody();

    // Verifies email address and password
    $user = UserQuery::create()->findOneByEmail($body['email']);

    if (!$user || !password_verify($body['password'], $user->getPassword())) {
      return $response->withJson(array(
        'error' => 'INVALID_CREDENTIALS'
      ));
    }

    $token = $user->getToken();

    if ($token) {
      $token_expiration = new \DateTime();
      $token_expiration->setTimestamp($user->getTokenExpiration());
      $date_now = new \DateTime();
      $date_diff = $date_now->diff($token_expiration);

      // If the user's token is not valid anymore, regenerates it
      if ($date_diff->h >= self::TOKEN_HOURS) {
        $token = $this->generateAndStoreToken($user);
      }
    } else { // If the user does not have a token, generate it
      $token = $this->generateAndStoreToken($user);
    }

    return $response->withJson(array(
      'first_name' => $user->getFirstName(),
      'last_name' => $user->getLastName(),
      'token' => $token
    ), 200);

  }

  private function generateAndStoreToken(User $user) {
    $token = base64_encode(openssl_random_pseudo_bytes(64));
    $user->setToken($token);
    $user->setTokenExpiration(time());
    $user->save();
    return $token;
  }

}
