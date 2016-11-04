<?php

namespace SciMS\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use SciMS\Models\User;
use SciMS\Models\Article;
use SciMS\Models\ArticleQuery;

class ArticleController {

  const ARTICLES_PER_PAGE = 5;
  const ARTICLE_NOT_FOUND = 'ARTICLE_NOT_FOUND';

  /**
   * Create a new article.
   * @param  ServerRequestInterface $request  a PSR-7 Request object
   * @param  ResponseInterface      $response a PRS-7 Response object
   * @return ResponseInterface a PSR-7 Response object containg the URL of the new article or a list of errors.
   */
  public function create(ServerRequestInterface $request, ResponseInterface $response) {
    $body = $request->getParsedBody();

    // Retreives the User from TokenMiddleware
    $user = $request->getAttribute('user');

    $article = new Article();
    $article->setUserId($user->getId());
    $article->setTitle($body['title']);
    $article->setContent($body['content']);
    $article->setPublicationDate(time());

    if (!$article->validate()) {
      $errors = [];
      foreach ($article->getValidationFailures() as $failure) {
        $errors[] = $failure->getMessage();
      }

      return $response->withJson(array(
        'errors' => $errors
      ), 400);
    }

    $article->save();

    return $response->withJson(array(
      'url' => 'blabla'
    ), 200);
  }

  /**
   * Edits an article given by its id.
   * @param  ServerRequestInterface $request  a PSR-7 Request object.
   * @param  ResponseInterface      $response a PSR-7 Response object.
   * @param  array                  $args     the article id passed in the url.
   * @return ResponseInterface an http 200 or a JSON if errors.
   */
  public function edit(ServerRequestInterface $request, ResponseInterface $response, array $args) {
    // Retreives all the parameters.
    $articleTitle = $request->getParsedBodyParam('title', NULL);
    $articleContent = $request->getParsedBodyParam('content', NULL);

    // Retreives the article by its id.
    // Returns an error if the article is not found.
    $article = ArticleQuery::create()->findPK($args['id']);
    if (!$article) {
      return $reponse->withJson([
        'errors' => [
          self::ARTICLE_NOT_FOUND
        ]
      ], 400);
    }

    // Updates the article informations.
    $article->setTitle($articleTitle);
    $article->setContent($articleContent);

    // Validates the new data.
    // Returns errors if the new data are not valid.
    if (!$article->validate()) {
      $errors = [];
      foreach ($article->getValidationFailures() as $failure) {
        $errors[] = $failure->getMessage();
      }
      return $response->withJson([
        'errors' => $errors
      ], 400);
    }

    // Saves the new data and send http 200.
    $article->save();
    return $response->withStatus(200);
  }

  /**
   * Get articles with a specific page.
   * The number of articles contained in a page is given by the const ARTICLES_PER_PAGE.
   * @param  ServerRequestInterface $request  a PSR-7 request object.
   * @param  ResponseInterface      $response a PSR-7 response object.
   * @return ResponseInterfaace a JSON containing all the articles.
   */
  public function getPage(ServerRequestInterface $request, ResponseInterface $response) {
    $page = $request->getQueryParam('page', 1);

    $articles = ArticleQuery::create()->paginate($page, self::ARTICLES_PER_PAGE);

    $json = [
      'articles' => []
    ];

    foreach ($articles as $article) {
      $json['articles'][] = $article;
    }

    return $response->withJson(json_encode($json), 200);

   return $response;
  }

  /**
   * Returns an article given by its id.
   * @param  ServerRequestInterface $request  a PSR-7 Request object.
   * @param  ResponseInterface      $response a PSR-7 Response object.
   * @param  array                  $args     arguments in url.
   * @return ResponseInterface a JSON containing the article informations.
   */
  public function getById(ServerRequestInterface $request, ResponseInterface $response, array $args) {
    // Get the article given by its id
    $article = ArticleQuery::create()->findOneById($args['id']);

    // Returns an error if the article is not found
    if (!$article) {
      return $response->withJson([
        'errors' => [
          self::ARTICLE_NOT_FOUND
        ]
      ], 400);
    }

    return $response->withJson(json_encode($article), 200);
  }

  /**
   * Endpoint to delete an article given by its id.
   * @param  ServerRequestInterface $request  a PSR-7 Request object.
   * @param  ResponseInterface      $response a PSR-7 Response object.
   * @param  array                  $args     an array containing url arguments.
   * @return http 200 of ok or 400 if errors.
   */
  public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args) {
    // Retreives the article with its id
    $article = ArticleQuery::create()->findPK($args['id']);

    if (!$article) {
      return $response->withJson([
        'errors' => [
          ARTICLE_NOT_FOUND
        ]
      ], 400);
    }

    $article->delete();

    return $response->withJson(200);
  }

}
