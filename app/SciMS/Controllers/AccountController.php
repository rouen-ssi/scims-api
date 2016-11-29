<?php

namespace SciMS\Controllers;

use SciMS\Models\Account;
use SciMS\Models\AccountQuery;
use SciMS\Models\ArticleQuery;
use SciMS\Models\HighlightedArticle;
use SciMS\Models\HighlightedArticleQuery;
use SciMS\Utils;
use Slim\Http\Request;
use Slim\Http\Response;

class AccountController {

    const TOKEN_HOURS = 24;
    const PASSWORD_MIN_LEN = 6;

    const INVALID_CREDENTIALS = 'INVALID_CREDENTIALS';
    const INVALID_PASSWORD = 'INVALID_PASSWORD';
    const INVALID_OLD_PASSWORD = 'INVALID_OLD_PASSWORD';
    const INVALID_NEW_PASSWORD = 'INVALID_NEW_PASSWORD';
    const account_NOT_FOUND = 'ACCOUNT_NOT_FOUND';
    const ARTICLE_NOT_FOUND = 'ARTICLE_NOT_FOUND';

    /**
     * Get account informations given by its uid.
     * @param  Request $request a PSR-7 Request object.
     * @param  Response $response a PSR-7 Response object.
     * @param  array $args arguments passed in the route url.
     * @return Response a PSR-7 Response object containing a JSON with the account informations.
     */
    public function get(Request $request, Response $response, array $args) {
        // Get the account given by its uid
        $account = AccountQuery::create()->findOneByUid($args['uid']);

        // Returns an error if the account is not found
        if (!$account) {
            return $response->withJson([
                'errors' => [
                    self::account_NOT_FOUND
                ]
            ], 400);
        }

        // Returns the account's informations
        return $response->withJson(json_encode($account), 200);
    }

    /**
     * Changes the account's password given by his token.
     * @param  Request $request a PSR-7 request object.
     * @param  Response $response a PSR-7 response object
     * @return Response a 200 response code if successful or an array of errors.
     */
    public function changePassword(Request $request, Response $response) {
        $body = $request->getParsedBody();

        // Gets the accounts from TokenMiddleware
        $account = $request->getAttribute('account');

        // Checks if the given old password matches
        if (!password_verify($body['old_password'], $account->getPassword())) {
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

        // Changes the account's password
        $account->setPassword(password_hash($body['new_password'], PASSWORD_DEFAULT));
        $account->save();

        return $response->withStatus(200);
    }

    /**
     * Update the account's email given by its token.
     * Returns an http 200 status or a JSON containing errors.
     */
    public function updateEmail(Request $request, Response $response) {
        // Retreives the account given by TokenMiddleware
        $account = $request->getAttribute('account');

        // Retreives the parameters
        $email = $request->getParsedBodyParam('email', NULL);

        // Updates the account's email and validate
        $account->setEmail(trim($email));
        $errors = Utils::validate($account);
        if (count($errors) > 0) {
            return $response->withJson([
                'errors' => $errors
            ], 400);
        }
        $account->save();

        // Returns an http 200 status
        return $response->withStatus(200);
    }

    /**
     * Endpoint to update account's informations (avatar, biography, lastname, ...).
     * If an information is not given, it will not be updated.
     * Returns an http 200 status or a json containing errors.
     */
    public function updateInformations(Request $request, Response $response) {
        // Retreives the account given by its token.
        $account = $request->getAttribute('account');

        // Retreives the parameters.
        $firstName = trim($request->getParsedBodyParam('first_name', $account->getFirstName()));
        $lastName = trim($request->getParsedBodyParam('last_name', $account->getLastName()));
        $biography = trim($request->getParsedBodyParam('biography', $account->getBiography()));

        // Updates the account's highlighted articles.
        $response = $this->updateHighlighted($request, $response);

        // Updates and validates the account's informations.
        $account->setFirstName($firstName);
        $account->setLastName($lastName);
        $account->setBiography($biography);
        $errors = Utils::validate($account);
        if (count($errors) > 0) {
            return $response->withJson([
                'errors' => $errors
            ], 400);
        }
        $account->save();

        // Returns an http 200 status
        return $response->withStatus(200);
    }

    public function updateHighlighted(Request $request, Response $response) {
        // Retreives the account given by its token.
        $account = $request->getAttribute('account');

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
                ->filterByaccount($account)
                ->filterByArticle($article)
                ->count();
            if ($highlightedArticlesCount > 0) {
                continue;
            }

            $highlightedArticle = new HighlightedArticle();
            $highlightedArticle->setaccount($account);
            $highlightedArticle->setArticle($article);
            $highlightedArticle->save();
        }

        // Returns an http 200 status
        return $response->withStatus(200);
    }

    /**
     * Endpoint for create an account
     * @param  Request $request a PSR 7 Request object
     * @param  Response $response a PSR 7 Response object
     * @return Response PSR 7 Response object containing the response.
     */
    public function create(Request $request, Response $response) {
        // Retreives the parameters
        $email = $request->getParsedBodyParam('email', '');
        $firstName = $request->getParsedBodyParam('first_name', '');
        $lastName = $request->getParsedBodyParam('last_name', '');
        $password = $request->getParsedBodyParam('password', '');

        $account = new Account();
        $account->setUid(uniqid());
        $account->setEmail($email);
        $account->setFirstName($firstName);
        $account->setLastName($lastName);
        $account->setPassword(password_hash($password, PASSWORD_DEFAULT));
        $errors = Utils::validate($account);

        // Checks the password length
        if (strlen($password) < self::PASSWORD_MIN_LEN) {
            $errors[] = self::INVALID_PASSWORD;
        }

        // Returns a JSON containing errors if any.
        if (count($errors) > 0) {
            return $response->withJson([
                'errors' => $errors
            ], 400);
        }

        $account->save();

        return $response->withStatus(200);
    }

    /**
     * Endpoint used for account authentification
     * @param  Request $request a PSR 7 Request object
     * @param  Response $response a PSR 7 Response object
     * @return Response PSR 7 Response object containing the response.
     */
    public function login(Request $request, Response $response) {
        $body = $request->getParsedBody();

        // Verifies email address and password
        $account = AccountQuery::create()->findOneByEmail($body['email']);

        if (!$account || !password_verify($body['password'], $account->getPassword())) {
            return $response->withJson([
                'errors' => [
                    self::INVALID_CREDENTIALS
                ]
            ], 400);
        }

        $token = $account->getToken();

        if ($token) {
            $token_expiration = new \DateTime();
            $token_expiration->setTimestamp($account->getTokenExpiration());
            $date_now = new \DateTime();
            $date_diff = $date_now->diff($token_expiration);

            // If the account's token is not valid anymore, regenerates it
            if ($date_diff->h >= self::TOKEN_HOURS) {
                $token = $this->generateAndStoreToken($account);
            }
        } else { // If the account does not have a token, generate it
            $token = $this->generateAndStoreToken($account);
        }

        return $response->withJson([
            'token' => $token,
            'account' => $account
        ], 200);

    }

    /**
     * Generate to new token and stores it in the database for the account given
     * @param  account $account the concerned account.
     * @return String the newly generated token encoded in base64.
     */
    private function generateAndStoreToken(account $account) {
        $token = base64_encode(openssl_random_pseudo_bytes(64));
        $account->setToken($token);
        $account->setTokenExpiration(time());
        $account->save();
        return $token;
    }

}
