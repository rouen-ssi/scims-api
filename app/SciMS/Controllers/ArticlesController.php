<?php

namespace SciMS\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use SciMS\Models\Article;
use SciMS\Models\ArticleQuery;

class ArticlesController {

  const ARTICLES_PER_PAGE = 5;

  public function get(ServerRequestInterface $request, ResponseInterface $response) {
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

}
