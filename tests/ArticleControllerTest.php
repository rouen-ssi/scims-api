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

    public function setUp() {
        $this->articleController = new ArticleController();
    }

    public function tearDown() {
        $this->articleController = null;
    }

    public function testCreate() {
        $account = new Account();
        $account->setUid('1234azer');
        $account->setEmail('john.doe@example.com');
        $account->setFirstName('John');
        $account->setLastName('Doe');
        $account->setPassword('johndoepassword');
        $account->save();

        $article = [
            'is_draft' => 'false',
            'title' => 'Dummy Article',
            'content' => 'Dummy article content'
        ];

        $environment = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/article',
            'QUERY_STRING' => '',
            'CONTENT_TYPE' => 'application/json;charset=utf8',
        ]);

        $request = Request::createFromEnvironment($environment);
        $request = $request->withAttribute('user', $account);
        $request->getBody()->write(json_encode($article));

        $response = new Response();
        $response = $this->articleController->create($request, $response);

        parent::assertEquals($response->getStatusCode(), 200);
    }
}