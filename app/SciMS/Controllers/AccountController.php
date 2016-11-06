<?php

namespace SciMS\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use SciMS\Models\User;
use SciMS\Models\UserQuery;

class AccountController {

  const TOKEN_HOURS = 24;
  const PASSWORD_MIN_LEN = 6;
  const INVALID_OLD_PASSWORD = 'INVALID_OLD_PASSWORD';
  const INVALID_NEW_PASSWORD = 'INVALID_NEW_PASSWORD';
  const USER_NOT_FOUND = 'USER_NOT_FOUND';

  /**
   * Get account informations given by its uid.
   * @param  ServerRequestInterface $request  a PSR-7 Request object.
   * @param  ResponseInterface      $response a PSR-7 Response object.
   * @param  array                  $args     arguments passed in the route url.
   * @return ResponseInterface a PSR-7 Response object containing a JSON with the account informations.
   */
  public function get(ServerRequestInterface $request, ResponseInterface $response, array $args) {
    // Get the user given by its uid
    $user = UserQuery::create()->findOneByUid($args['uid']);

    // Returns an error if the user is not found
    if (!$user) {
      return $response->withJson([
        'errors' => [
          self::USER_NOT_FOUND
        ]
      ], 400);
    }

    // Returns the user's informations
    return $response->withJson(json_encode($user), 200);
  }

  /**
   * Changes the user's password given by his token.
   * @param  ServerRequestInterface $request  a PSR-7 request object.
   * @param  ResponseInterface      $response a PSR-7 response object
   * @return ResponseInterface a 200 response code if successful or an array of errors.
   */
  public function changePassword(ServerRequestInterface $request, ResponseInterface $response) {
    $body = $request->getParsedBody();

    // Gets the users from TokenMiddleware
    $user = $request->getAttribute('user');

    // Checks if the given old password matches
    if (!password_verify($body['old_password'], $user->getPassword())) {
      return $response->withJson([
        'errors' => [
          self::INVALID_OLD_PASSWORD
        ]
      ], 400);
    }

    // Checks the password length
    if (strlen($body['new_password']) < self::PASSWORD_MIN_LEN) {
      return $response->withJson([
        'errors' => [
          self::INVALID_NEW_PASSWORD
        ]
      ], 400);
    }

    // Changes the user's password
    $user->setPassword(password_hash($body['new_password'], PASSWORD_DEFAULT));
    $user->save();

    return $response->withStatus(200);
  }

  /**
   * Update the user's email given by its token.
   * Returns an http 200 status or a JSON containing errors.
   */
  public function updateEmail(ServerRequestInterface $request, ResponseInterface $response) {
    // Retreives the user given by TokenMiddleware
    $user = $request->getAttribute('user');

    // Retreives the parameters
    $email = $request->getParsedBodyParam('email', NULL);

    // Updates the user's email and validate
    $user->setEmail(trim($email));
    if (!$user->validate()) {
      $errors = [];
      foreach ($user->getValidationFailures() as $failure) {
        $errors[] = $failure->getMessage();
        return $response->withJson([
          'errors' => $errors
        ], 400);
      }
    }
    $user->save();

    // Returns an http 200 status
    return $response->withStatus(200);
  }

  /**
   * Updates the user's information given by its token.
   * @param  ServerRequestInterface $request  a PSR-7 Request object.
   * @param  ResponseInterface      $response a PSR-7 Response object.
   * @return ResponseInterface http 200 or a json containing errors if errors.
   */
  public function changeInformations(ServerRequestInterface $request, ResponseInterface $response) {
    $body = $request->getParsedBody();

    // Gets the users from TokenMiddleware
    $user = $request->getAttribute('user');

    // Updates the user's informations
    $user->setEmail($body['email']);
    $user->setLastName($body['last_name']);
    $user->setFirstName($body['first_name']);

    if (!$user->validate()) {
      $errors = [];
      foreach ($user->getValidationFailures() as $failure) {
        $errors[] = $failure->getMessage();
      }
      return $response->withJson([
        'errors' => $errors
      ], 400);
    }

    $user->save();

    return $response->withStatus(200);
  }

  /**
   * Endpoint for create an account
   * @param  ServerRequestInterface  $request  a PSR 7 Request object
   * @param  ResponseInterface       $response a PSR 7 Response object
   * @return a PSR 7 Response object containing the response.
   */
  public function create(ServerRequestInterface $request, ResponseInterface $response) {
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

  /**
   * Endpoint used for user authentification
   * @param  ServerRequestInterface $request  a PSR 7 Request object
   * @param  ResponseInterface      $response a PSR 7 Response object
   * @return a PSR 7 Response object containing the response.
   */
  public function login(ServerRequestInterface $request, ResponseInterface $response) {
    $body = $request->getParsedBody();

    // Verifies email address and password
    $user = UserQuery::create()->findOneByEmail($body['email']);

    if (!$user || !password_verify($body['password'], $user->getPassword())) {
      return $response->withJson(array(
        'errors' => array('INVALID_CREDENTIALS')
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

  /**
   * Generate to new token and stores it in the database for the user given
   * @param  User   $user the concerned User.
   * @return returns the newely generated token encoded in base64.
   */
  private function generateAndStoreToken(User $user) {
    $token = base64_encode(openssl_random_pseudo_bytes(64));
    $user->setToken($token);
    $user->setTokenExpiration(time());
    $user->save();
    return $token;
  }

}
