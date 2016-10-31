<?php

namespace SciMS\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use SciMS\Models\User;
use SciMS\Models\Article;

class ArticleController {

  /**
   * Create a new article.
   * @param  ServerRequestInterface $request  a PSR-7 Request object
   * @param  ResponseInterface      $response a PRS-7 Response object
   * @return ResponseInterface a PSR-7 Response object containg the URL of the new article or a list of errors.
   */
  public function post(ServerRequestInterface $request, ResponseInterface $response) {
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

}
