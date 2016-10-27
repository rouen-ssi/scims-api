<?php

namespace SciMS\Controllers;

use SciMS\Models\User;

class AccountController {

  public function create($request, $response, $args) {
    $body = $request->getParsedBody();
    $errors = [];

    // Verifies email
    if (!filter_var($body['email'], FILTER_VALIDATE_EMAIL)) {
      $errors[] = 'INVALID_EMAIL';
    }

    // Verifies first name
    if (empty($body['first_name'])) {
      $errors[] = 'INVALID_FIRST_NAME';
    }

    // Verifies last_name
    if (empty($body['last_name'])) {
      $errors[] = 'INVALID_LAST_NAME';
    }

    // Verifies password
    if (strlen($body['password']) < 6) {
      $errors[] = 'INVALID_PASSWORD';
    }

    // Sends errors if any
    if (count($errors) > 0) {
      return $response->withJson(array(
        'errors' => $errors
      ), 400);
    }

    $user = new User();
    $user->setUid(uniqid());
    $user->setEmail($body['email']);
    $user->setFirstName($body['first_name']);
    $user->setLastName($body['last_name']);
    $user->setPassword(sha1($body['password']));
    $user->save();

    return $response->withStatus(200);
  }

}
