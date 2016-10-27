<?php

namespace SciMS\Controllers;

use SciMS\Models\User;

class AccountController {

  public function create($request, $response, $args) {
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
