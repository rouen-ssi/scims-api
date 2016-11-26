<?php

namespace SciMS\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use SciMS\Models\User;
use SciMS\Models\UserQuery;
use SciMS\Models\ArticleQuery;
use SciMS\Models\HighlightedArticle;
use SciMS\Models\HighlightedArticleQuery;

class AccountController {

  const TOKEN_HOURS = 24;
  const PASSWORD_MIN_LEN = 6;

  const INVALID_CREDENTIALS = 'INVALID_CREDENTIALS';
  const INVALID_PASSWORD = 'INVALID_PASSWORD';
  const INVALID_OLD_PASSWORD = 'INVALID_OLD_PASSWORD';
  const INVALID_NEW_PASSWORD = 'INVALID_NEW_PASSWORD';
  const USER_NOT_FOUND = 'USER_NOT_FOUND';
  const ARTICLE_NOT_FOUND = 'ARTICLE_NOT_FOUND';

  /**
   * Get account informations given by its uid.
   * @param  Request $request  a PSR-7 Request object.
   * @param  Response      $response a PSR-7 Response object.
   * @param  array                  $args     arguments passed in the route url.
   * @return Response a PSR-7 Response object containing a JSON with the account informations.
   */
  public function get(Request $request, Response $response, array $args) {
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
   * @param  Request $request  a PSR-7 request object.
   * @param  Response      $response a PSR-7 response object
   * @return Response a 200 response code if successful or an array of errors.
   */
  public function changePassword(Request $request, Response $response) {
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
  public function updateEmail(Request $request, Response $response) {
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
   * Endpoint to update user's informations (avatar, biography, lastname, ...).
   * If an information is not given, it will not be updated.
   * Returns an http 200 status or a json containing errors.
   */
  public function updateInformations(Request $request, Response $response) {
    // Retreives the user given by its token.
    $user = $request->getAttribute('user');

    // Retreives the parameters.
    $firstName = trim($request->getParsedBodyParam('first_name', $user->getFirstName()));
    $lastName = trim($request->getParsedBodyParam('last_name', $user->getLastName()));
    $biography = trim($request->getParsedBodyParam('biography', $user->getBiography()));

    // Updates the user's highlighted articles.
    $response = $this->updateHighlighted($request, $response);

    // Updates and validates the user's informations.
    $user->setFirstName($firstName);
    $user->setLastName($lastName);
    $user->setBiography($biography);
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

    // Returns an http 200 status
    return $response->withStatus(200);
  }

  public function updateHighlighted(Request $request, Response $response) {
    // Retreives the user given by its token.
    $user = $request->getAttribute('user');

    // Retreives the parameters.
    $articleIds = $request->getParsedBodyParam('highlighted_articles', []);

    // Add highlighted articles.
    foreach ($articleIds as $articleId) {
      // Retreives the article by its id.
      $article = ArticleQuery::create()->findPk($articleId);
      if (!$article) {
        return $response->withJson([
          'errors' => [
            self::ARTICLE_NOT_FOUND
          ]
        ], 400);
      }

      // Checks if the highlighted articles already exists
      $highlightedArticlesCount = HighlightedArticleQuery::create()
        ->filterByUser($user)
        ->filterByArticle($article)
        ->count();
      if ($highlightedArticlesCount > 0) {
        continue;
      }

      $highlightedArticle = new HighlightedArticle();
      $highlightedArticle->setUser($user);
      $highlightedArticle->setArticle($article);
      $highlightedArticle->save();
    }

    // Returns an http 200 status
    return $response->withStatus(200);
  }

  /**
   * Endpoint for create an account
   * @param  Request  $request  a PSR 7 Request object
   * @param  Response       $response a PSR 7 Response object
   * @return Response PSR 7 Response object containing the response.
   */
  public function create(Request $request, Response $response) {
    // Retreives the parameters
    $email = $request->getParsedBodyParam('email', '');
    $firstName = $request->getParsedBodyParam('first_name', '');
    $lastName = $request->getParsedBodyParam('last_name', '');
    $password = $request->getParsedBodyParam('password', '');

    $user = new User();
    $user->setUid(uniqid());
    $user->setEmail($email);
    $user->setFirstName($firstName);
    $user->setLastName($lastName);
    $user->setPassword(password_hash($password, PASSWORD_DEFAULT));

    if (!$user->validate()) {
      $errors = [];
      foreach ($user->getValidationFailures() as $failure) {
        $errors[] = $failure->getMessage();
      }
      return $response->withJson(array(
        'errors' => $errors
      ), 400);
    }

    // Checks the password length
    if (strlen($password) < self::PASSWORD_MIN_LEN) {
      return $response->withJson([
        'errors' => [
          self::INVALID_PASSWORD
        ]
      ], 400);
    }

    $user->save();

    return $response->withStatus(200);
  }

  /**
   * Endpoint used for user authentification
   * @param  Request $request  a PSR 7 Request object
   * @param  Response      $response a PSR 7 Response object
   * @return Response PSR 7 Response object containing the response.
   */
  public function login(Request $request, Response $response) {
    $body = $request->getParsedBody();

    // Verifies email address and password
    $user = UserQuery::create()->findOneByEmail($body['email']);

    if (!$user || !password_verify($body['password'], $user->getPassword())) {
      return $response->withJson([
        'errors' => [
          self::INVALID_CREDENTIALS
        ]
      ], 400);
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

    return $response->withJson([
      'token' => $token,
      'user' => $user
    ], 200);

  }

  /**
   * Generate to new token and stores it in the database for the user given
   * @param  User   $user the concerned User.
   * @return String the newly generated token encoded in base64.
   */
  private function generateAndStoreToken(User $user) {
    $token = base64_encode(openssl_random_pseudo_bytes(64));
    $user->setToken($token);
    $user->setTokenExpiration(time());
    $user->save();
    return $token;
  }

}
