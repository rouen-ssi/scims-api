<?php

use PHPUnit\Framework\TestCase;
use SciMS\Controllers\ArticleController;
use SciMS\Models\Account;
use SciMS\Models\Article;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;

class ArticleControllerTest extends TestCase {
    /**
     * @var ArticleController
     */
    private $articleController;
    /**
     * @var Account
     */
    private $account;
    private $article;

    public function setUp() {
        $this->articleController = new ArticleController();

        $this->account = new Account();
        $this->account->setUid('1234azer');
        $this->account->setEmail('john.doe@example.com');
        $this->account->setFirstName('John');
        $this->account->setLastName('Doe');
        $this->account->setPassword('johndoepassword');
        $this->account->save();

        $this->article = [
            'is_draft' => 'false',
            'title' => 'Dummy Article',
            'content' => 'Dummy article content'
        ];
    }

    public function tearDown() {
        $this->articleController = null;
    }

    public function testCreate() {
        $environment = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/article',
            'QUERY_STRING' => '',
            'CONTENT_TYPE' => 'application/json;charset=utf8',
        ]);

        $request = Request::createFromEnvironment($environment);
        $request = $request->withAttribute('user', $this->account);
        $request->getBody()->write(json_encode($this->article));

        $response = new Response();
        $response = $this->articleController->create($request, $response);

        parent::assertEquals(200, $response->getStatusCode());
    }

    /**
     * @depends testCreate
     */
    public function testEdit() {
        $environment = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '/article',
            'QUERY_STRING' => '',
            'CONTENT_TYPE' => 'application/json;charset=utf8'
        ]);

        $article = [
            'is_draft' => true,
            'title' => 'An edited dummy article',
            'content' => 'An edit article content.'
        ];

        $request = Request::createFromEnvironment($environment);
        $request->getBody()->write(json_encode($article));

        $response = new Response();
        $response = $this->articleController->edit($request, $response, [ 'id' => 1 ]);

        parent::assertEquals(200, $response->getStatusCode());
    }

    /**
     * @depends testCreate
     */
    public function testGetById() {
        $environment = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/article',
            'QUERY_STRING' => '',
            'CONTENT_TYPE' => 'application/json'
        ]);

        $request = Request::createFromEnvironment($environment);
        $response = new Response();
        $response = $this->articleController->getById($request, $response, [ 'id' => 1 ]);

        parent::assertEquals(200, $response->getStatusCode());
    }

    /**
     * @depends testCreate
     */
    public function testGetPage() {
        $environment = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/article',
            'QUERY_STRING' => '',
            'CONTENT_TYPE' => 'application/json;charset=utf8'
        ]);

        $request = Request::createFromEnvironment($environment);

        $response = new Response();
        $response = $this->articleController->getPage($request, $response);

        parent::assertEquals(200, $response->getStatusCode());
    }

    public function testDelete() {
        $environnment = Environment::mock([
            'REQUEST_METHOD' => 'DELETE',
            'REQUEST_URI' => '/article',
            'QUERY_STRING' => '',
            'CONTENT_TYPE' => 'applocation/json;charset=utf8'
        ]);

        $article = new Article();
        $article->setAccountId($this->account->getId());
        $article->setTitle('Dummy article');
        $article->setContent('Dummy article content');
        $article->setPublicationDate(time());
        $article->setLastModificationDate(time());
        $article->save();

        $request = Request::createFromEnvironment($environnment);

        $response = new Response();
        $response = $this->articleController->delete($request, $response, [ 'id' => $article->getId() ]);

        parent::assertEquals(200, $response->getStatusCode());
    }

    /** @depends testCreate */
    public function recordViewTest() {
        $environment = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '',
            'QUERY_STRING' => '',
            'CONTENT_TYPE' => 'applocation/json;charset=utf8'
        ]);
        
        $request = Request::createFromEnvironment($environment);

        $response = new Response();
        $response = $this->articleController->recordView($request, $response, [ 'id' => 1]);

        parent::assertEquals(200, $response->getStatusCode());
    }
}