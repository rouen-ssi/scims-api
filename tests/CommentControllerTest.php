<?php

use PHPUnit\Framework\TestCase;
use SciMS\Controllers\CommentController;
use SciMS\Models\Account;
use SciMS\Models\Article;
use SciMS\Models\Comment;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;

class CommentControllerTest extends TestCase {

    /* @var CommentController */
    private $commentController;
    private $account;
    /** @var  Article */
    private $article;

    public function setUp() {
        $this->commentController = new CommentController();

        $this->account = new Account();
        $this->account->setUid(uniqid());
        $this->account->setEmail('john.doe@example.com');
        $this->account->setFirstName('John');
        $this->account->setLastName('Doe');
        $this->account->setPassword(password_hash('dummy_password', PASSWORD_DEFAULT));
        $this->account->save();

        $this->article = new Article();
        $this->article->setAccount($this->account);
        $this->article->setTitle('A Dummy Article');
        $this->article->setContent('A dummy article content.');
        $this->article->setPublicationDate(time());
        $this->article->setLastModificationDate($this->article->getPublicationDate());
        $this->article->save();
    }

    public function testPost() {
        $environment = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '',
            'QUERY_STRING' => '',
            'CONTENT_TYPE' => 'application/json'
        ]);

        $comment = [ 'content' => 'This is a dummy comment' ];

        $request = Request::createFromEnvironment($environment);
        $request = $request->withAttribute('user', $this->account);
        $request->getBody()->write(json_encode($comment));

        $response = new Response();
        $response = $this->commentController->post($request, $response, [ 'id' => $this->article->getId() ]);

        parent::assertEquals(200, $response->getStatusCode());
    }

    public function testEdit() {
        $comment = new Comment();
        $comment->setAuthor($this->account);
        $comment->setArticle($this->article);
        $comment->setPublicationDate(time());
        $comment->setContent('This is a dummy comment.');
        $comment->save();

        $environment = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '',
            'QUERY_STRING' => '',
            'CONTENT_TYPE' => 'application/json'
        ]);

        $editedComment = [ 'content' => 'This is an edited dummy content.' ];

        $request = Request::createFromEnvironment($environment);
        $request = $request->withAttribute('user', $this->account);
        $request->getBody()->write(json_encode($editedComment));

        $response = new Response();
        $response = $this->commentController->edit($request, $response, [ 'comment_id' => $comment->getId() ]);

        parent::assertEquals(200, $response->getStatusCode());
    }

}