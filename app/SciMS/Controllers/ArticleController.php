<?php

namespace SciMS\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use SciMS\Models\User;
use SciMS\Models\Article;
use SciMS\Models\ArticleQuery;
use Slim\Http\Request;
use Slim\Http\Response;

class ArticleController {

  const ARTICLES_PER_PAGE = 5;
  const ARTICLE_NOT_FOUND = 'ARTICLE_NOT_FOUND';

  /**
   * Endpoint to create an article.
   * Returns the newly created article id or errors.
   */
  public function create(ServerRequestInterface $request, ResponseInterface $response) {
    // Retreives the parameters.
    $title = $request->getParsedBodyParam('title', '');
    $content = $request->getParsedBodyParam('content', '');
    $categoryId = $request->getParsedBodyParam('category_id', -1);
    $subcategoryId = $request->getParsedBodyParam('subcategory_id', -1);

    // Retreives the User from the given token.
    $user = $request->getAttribute('user');

    $article = new Article();
    $article->setUserId($user->getId());
    $article->setTitle($title);
    $article->setContent($content);
    $article->setPublicationDate(time());
    $article->setCategoryId($categoryId);
    $article->setSubcategoryId($subcategoryId);

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
      'id' => $article->getId()
    ), 200);
  }

  /**
   * Endpoint to edit an article given by its id.
   * Returns an http 200 status if success or errors.
   */
  public function edit(ServerRequestInterface $request, ResponseInterface $response, array $args) {
    // Retreives all the parameters.
    $articleTitle = $request->getParsedBodyParam('title', '');
    $articleContent = $request->getParsedBodyParam('content', '');
    $categoryId = $request->getParsedBodyParam('category_id', '-1');
    $subcategoryId = $request->getParsedBodyParam('content', '');

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
    $article->setCategoryId($categoryId);
    $article->setSubcategoryId($subcategoryId);

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
   * Get articles from a specific page, the first page is queried by default.
   * Filter results from a specific category, all categories are queried by default.
   * The number of articles contained in a page is given by the const ARTICLES_PER_PAGE.
   *
   * @param Request $request
   * @param Response $response
   * @return Response JSON containing all the articles.
   */
  public function getPage(Request $request, Response $response) {
    $query = ArticleQuery::create('a')
      ->orderByPublicationDate('DESC')
    ;

    if ($categoryId = $request->getQueryParam('categoryId')) {
      $query = $query->where('a.category_id = ?', (int) $categoryId);
    }

    $page = $query->paginate($request->getQueryParam('page', 1), self::ARTICLES_PER_PAGE);
    $articles = $page->getResults();

    return $response->withJson([
      'articles' => $articles->getData(),
    ], 200);
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
