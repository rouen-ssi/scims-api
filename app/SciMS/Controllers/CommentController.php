<?php

namespace SciMS\Controllers;

use SciMS\Models\ArticleQuery;
use SciMS\Models\CommentQuery;
use SciMS\Models\Comment;
use Slim\Http\Request;
use Slim\Http\Response;

class CommentController {

    const INVALID_ARTICLE = 'INVALID_ARTICLE';
    const INVALID_PARENT_COMMENT = 'INVALID_PARENT_COMMENT';

    /**
     * @param Request $request a JSON containing the comment article id, the parent comment id and the content.
     * @param Response $response
     * @return Response a JSON containing the newly created comment id or an array of errors if any.
     */
    public function post(Request $request, Response $response) {
        $errors = [];

        // Retrieves the user given by TokenMiddleware;
        $user = $request->getAttribute('user');

        // Retrieves all the parameters.
        $parentCommentId = $request->getParsedBodyParam('parent_comment_id', null);
        $articleId = $request->getParsedBodyParam('article_id', null);
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

        // Validates the new Comment.
        if (!$comment->validate()) {
            foreach ($comment->getValidationFailures() as $failure) {
                $errors[] = $failure->getMessage();
            }
        }

        // Returns a JSON containing errors if any.
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

}
