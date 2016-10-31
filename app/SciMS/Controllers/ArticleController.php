<?php

namespace SciMS\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use SciMS\Models\User;
use SciMS\Models\Article;

class ArticleController {

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
