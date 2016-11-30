<?php

require_once 'generated-conf/config.php';

use PHPUnit\Framework\TestCase;
use SciMS\Controllers\ArticleController;
use SciMS\Models\Account;
use Slim\Http\Environment;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\RequestBody;
use Slim\Http\Response;
use Slim\Http\Uri;

class ArticleControllerTest extends TestCase {
    private $articleController;
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

        parent::assertEquals($response->getStatusCode(), 200);
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

        parent::assertEquals($response->getStatusCode(), 200);
    }
}