<?php

namespace SciMS\Controllers;

use SciMS\Models\Article;
use SciMS\Models\ArticleQuery;
use SciMS\Models\AccountQuery;
use SciMS\Models\ArticleView;
use SciMS\Models\ArticleViewQuery;
use SciMS\Utils;
use Slim\Http\Request;
use Slim\Http\Response;

class ArticleController {

    const ARTICLES_PER_PAGE = 5;
    const ARTICLE_NOT_FOUND = 'ARTICLE_NOT_FOUND';

    /**
     * Endpoint to create an article.
     * Returns the newly created article id or errors.
     */
    public function create(Request $request, Response $response) {
        // Retreives the parameters.
        $isDraft = $request->getParsedBodyParam('is_draft', true);
        $title = $request->getParsedBodyParam('title', '');
        $content = $request->getParsedBodyParam('content', '');
        $categoryId = $request->getParsedBodyParam('category_id', -1);
        $subcategoryId = $request->getParsedBodyParam('subcategory_id', -1);
        $keywords = $request->getParsedBodyParam('keywords', []);

        // Retreives the User from the given token.
        $user = $request->getAttribute('user');
        $article = new Article();
        $article->setIsDraft($isDraft);
        $article->setAccountId($user->getId());
        $article->setTitle($title);
        $article->setContent($content);
        $article->setPublicationDate(time());
        $article->setLastModificationDate(time());
        $article->setCategoryId($categoryId);
        $article->setSubcategoryId($subcategoryId);
        $article->setKeywords($keywords);
        $errors = Utils::validate($article);
        if (count($errors) > 0 && !$isDraft) {
            return $response->withJson([
                'errors' => $errors
            ], 400);
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
    public function edit(Request $request, Response $response, array $args) {
        // Retreives all the parameters.
        $isDraft = $request->getParsedBodyParam('is_draft', true);
        $title = $request->getParsedBodyParam('title', '');
        $content = $request->getParsedBodyParam('content', '');
        $categoryId = $request->getParsedBodyParam('category_id', '-1');
        $subcategoryId = $request->getParsedBodyParam('content', '');
        $keywords = $request->getParsedBodyParam('keywords', []);

        // Updates keywords
        if (count($keywords) > 0) {
            $article->getKeywords()->delete();
            $article->setKeywords($keywords);
        }

        // Retreives the article by its id.
        // Returns an error if the article is not found.
        $article = ArticleQuery::create()->findPk($args['id']);
        if (!$article) {
            return $response->withJson([
                'errors' => [
                    self::ARTICLE_NOT_FOUND
                ]
            ], 400);
        }

        // Updates the article informations.
        $article->setIsDraft($isDraft);
        $article->setTitle($title);
        $article->setContent($content);
        $article->setCategoryId($categoryId);
        $article->setLastModificationDate(time());
        $article->setSubcategoryId($subcategoryId);
        $errors = Utils::validate($article);
        if (count($errors) > 0 && !$isDraft) {
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
     * The number of articles contained in a page is given by the const ARTICLES_PER_PAGE. */
    public function getPage(Request $request, Response $response) {
        // Get all articles.
        $query = ArticleQuery::create()
            ->filterByIsDraft(false)
            ->orderByPublicationDate('DESC');

        // If an category id is given, filter.
        if ($categoryId = $request->getQueryParam('category_id')) {
            $query->filterByCategoryId($categoryId);
        }

        // Paginates and get results.
        $page = (int)$request->getQueryParam('page', 1);
        $articles = $query->paginate($page, self::ARTICLES_PER_PAGE);

        $nrOfArticles = $query->count();

        return $response->withJson([
            'pagination' => [
                'current' => $page,
                'count' => (int) ceil($nrOfArticles / self::ARTICLES_PER_PAGE),
            ],
            'articles' => $articles->getResults()->getData(),
        ], 200);
    }

    /**
     * Get all draft articles of the current user
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function drafts(Request $request, Response $response) {
        $user = $request->getAttribute('user');

        $drafts = ArticleQuery::create()
            ->orderByLastModificationDate('DESC')
            ->filterByaccount($user)
            ->findByIsDraft(true)
            ->getData();

        return $response->withJson(['drafts' => $drafts], 200);
    }

    /**
     * Returns an article given by its id.
     * @param  Request $request a PSR-7 Request object.
     * @param  Response $response a PSR-7 Response object.
     * @param  array $args arguments in url.
     * @return Response a JSON containing the article informations.
     */
    public function getById(Request $request, Response $response, array $args) {
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

        return $response->withJson(['article' => $article], 200);
    }

    /**
     * Endpoint to delete an article given by its id.
     * @param  Request $request a PSR-7 Request object.
     * @param  Response $response a PSR-7 Response object.
     * @param  array $args an array containing url arguments.
     * @return Response an http 200 status or a json containing errors
     */
    public function delete(Request $request, Response $response, array $args) {
        // Retreives the article with its id
        $article = ArticleQuery::create()->findPk($args['id']);

        if (!$article) {
            return $response->withJson([
                'errors' => [
                    self::ARTICLE_NOT_FOUND
                ]
            ], 400);
        }

        $article->delete();

        return $response->withJson(200);
    }

    /**
     * Endpoint to add an article view.
     * If the user is a guest, record a view.
     * If the user is authenticated, checks if the user has already view the article.
     */
    public function recordView(Request $request, Response $response, array $args) {
        // Retreives the Article given by its ID.
        $article = ArticleQuery::create()->findPk($args['id']);
        if (!isset($article)) {
            return $response->withJson([
                'errors' => [
                    self::ARTICLE_NOT_FOUND
                ]
            ], 400);
        }

        // Checks if a token is given in HTTP headers.
        $token = null;
        if ($request->hasHeader('Authorization')) {
            $authorizationHeader = explode(' ', $request->getHeaderLine('Authorization'));
            if (isset($authorizationHeader[1])) {
                $token = $authorizationHeader[1];
            }
        }

        // If a token is given, retreives the Account associated with it.
        if (isset($token)) {
            $account = AccountQuery::create()->findOneByToken($authorizationHeader[1]);
            if (isset($account)) {
                // Checks if the user read the article for the first time
                $articleViewCount = ArticleViewQuery::create()
                    ->filterByAccount($account)
                    ->count();
                if ($articleViewCount <= 0) {
                    $articleView = new ArticleView();
                    $articleView->setAccount($account);
                    $articleView->setArticle($article);
                    $articleView->setDate(time());
                    $articleView->save();
                }
            }
        } else {
            $articleView = new ArticleView();
            $articleView->setArticle($article);
            $articleView->setDate(time());
            $articleView->save();
        }

        return $response->withStatus(200);
    }

}
