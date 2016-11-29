<?php

namespace SciMS\Controllers;

use SciMS\Models\ArticleQuery;
use SciMS\Models\CommentQuery;
use SciMS\Models\Comment;
use SciMS\Utils;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Uri;

class CommentController {

  const INVALID_ARTICLE = 'INVALID_ARTICLE';
  const INVALID_PARENT_COMMENT = 'INVALID_PARENT_COMMENT';
  const NOT_AUTHORIZED = 'NOT_AUTHORIZED';
  const COMMENT_NOT_FOUND = 'COMMENT_NOT_FOUND';

  /**
   * Fetch all comments posted on an article. Results are paginated accordingly to query parameter `?page=`.
   *
   * @param Request $request
   * @param Response $response
   * @return Response
   */
  public function index(Request $request, Response $response) {
    $articleId = (int) $request->getAttribute('id');

    $comments = CommentQuery::create()
      ->filterByArticleId($articleId)
      ->orderByPublicationDate('ASC')
      ->find()
    ;

    return $response->withJson([
      'comments' => $comments->getData(),
    ], 200);
  }

  /**
   * @param Request $request a JSON containing the parent comment id and content.
   * The comment article id is given in the URL.
   * @param Response $response
   * @return Response a JSON containing the newly created comment id or an array of errors if any.
   */
  public function post(Request $request, Response $response) {
    $errors = [];

    // Retrieves the user given by TokenMiddleware;
    $user = $request->getAttribute('user');

    // Retrieves all the parameters.
    $articleId = $request->getAttribute('id', null);
    $parentCommentId = $request->getParsedBodyParam('parent_comment_id', null);
    $content = trim($request->getParsedBodyParam('content', ''));

    // Retrieves the Article by its id.
    $article = ArticleQuery::create()->findPk($articleId);
    if (!$article) {
      $errors[] = self::INVALID_ARTICLE;
    }

    // Retrieves the parent Comment by its id (if it is given)
    $parentComment = null;
    if ($parentCommentId) {
      $parentComment = CommentQuery::create()->findPk($parentCommentId);
      if (!$parentComment) {
        $errors[] = self::INVALID_PARENT_COMMENT;
      }
    }

    // Creates the new Comment.
    $comment = new Comment();
    $comment->setAuthor($user);
    $comment->setParentComment($parentComment);
    $comment->setArticle($article);
    $comment->setPublicationDate(time());
    $comment->setContent($content);
    $errors = Utils::validate($comment);
    if (count($errors) > 0) {
      return $response->withJson([
        'errors' => $errors
      ], 400);
    }

    // Saves the new Comment.
    $comment->save();

    // Returns the newly created comment id.
    return $response->withJson([
      'id' => $comment->getId()
    ], 200);
  }

  public function edit(Request $request, Response $response, array $args) {
    // Retrieve the user given by TokenMiddleware.
    $user = $request->getAttribute('user');

    // Retrieves the Comment given by its id.
    $comment = CommentQuery::create()->findPk($args['comment_id']);

    // Returns an error if the Comment is not found
    if (!$comment) {
      return $response->withJson([
        'errors' => [ self::COMMENT_NOT_FOUND ]
      ], 400);
    }

    // Returns an error if the User is not the author of the Comment.
    if ($user != $comment->getAuthor()) {
      return $response->withJson([
        'errors' => [
          self::NOT_AUTHORIZED
        ]
      ], 401);
    }

    // Updates & validates the Comment given by its id.
    $comment->setContent(trim($request->getParsedBodyParam('content', '')));
    $errors = Utils::validate($comment);
    if (count($errors) > 0) {
      return $response->withJson([
        'errors' => $errors
      ], 400);
    }

    $comment->save();

    // Returns an HTTP 200 code.
    return $response->withStatus(200);
  }

  public function delete(Request $request, Response $response, array $args)
  {
    // Retrieve the user given by TokenMiddleware.
    $user = $request->getAttribute('user');

    // Retrieves the Comment given by its id.
    $comment = CommentQuery::create()->findPk($args['comment_id']);

    // Returns an error if the Comment is not found
    if (!$comment) {
      return $response->withJson([
        'errors' => [self::COMMENT_NOT_FOUND]
      ], 400);
    }

    // Returns an error if the User is not the author of the Comment.
    if ($user != $comment->getAuthor()) {
      return $response->withJson([
        'errors' => [
          self::NOT_AUTHORIZED
        ]
      ], 401);
    }

    // Deletes the Comment and returns an HTTP 200 code.
    $comment->delete();
    return $response->withStatus(200);
  }

}
