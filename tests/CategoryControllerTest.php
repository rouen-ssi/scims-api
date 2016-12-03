<?php

use PHPUnit\Framework\TestCase;
use SciMS\Controllers\CategoryController;
use SciMS\Models\Account;
use SciMS\Models\Category;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;

class CategoryControllerTest extends TestCase {

    /** @var  CategoryController */
    private $categoryController;
    private $account;
    /** @var  Category */
    private $category;

    public function setUp() {
        $this->categoryController = new CategoryController();

        $this->account = new Account();
        $this->account->setUid(uniqid());
        $this->account->setEmail('john.doe@example.com');
        $this->account->setFirstName('John');
        $this->account->setLastName('Doe');
        $this->account->setPassword(password_hash('dummy_password', PASSWORD_DEFAULT));
        $this->account->save();

        $this->category = new Category();
        $this->category->setName('Dummy category');
        $this->category->save();
    }

    public function testGetCategories() {
        $environment = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '',
            'QUERY_STRING' => '',
            'CONTENT_TYPE' => 'application/json;charset=utf8',
        ]);

        $request = Request::createFromEnvironment($environment);

        $response = new Response();
        $response = $this->categoryController->getCategories($request, $response);

        parent::assertEquals(200, $response->getStatusCode());
    }

    public function testGetCategory() {
        $environment = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '',
            'QUERY_STRING' => '',
            'CONTENT_TYPE' => 'application/json;charset=utf8',
        ]);

        $request = Request::createFromEnvironment($environment);

        $response = new Response();
        $response = $this->categoryController->getCategories($request, $response, [ 'id' => $this->category->getId() ]);

        parent::assertEquals(200, $response->getStatusCode());
    }

    public function testAddCategory() {
        $category = [
            'name' => 'Dummy subcategory',
            'parent_category_id' => $this->category->getId()
        ];

        $environment = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '',
            'QUERY_STRING' => '',
            'CONTENT_TYPE' => 'application/json;charset=utf8',
        ]);

        $request = Request::createFromEnvironment($environment);
        $request = $request->withAttribute('user', $this->account);
        $request->getBody()->write(json_encode($category));

        $response = new Response();
        $response = $this->categoryController->addCategory($request, $response);

        parent::assertEquals(200, $response->getStatusCode());
    }

    public function testEdit() {
        $editedCategory = [ 'name' => 'Edited dummy category' ];

        $environment = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '',
            'QUERY_STRING' => '',
            'CONTENT_TYPE' => 'application/json;charset=utf8',
        ]);

        $request = Request::createFromEnvironment($environment);
        $request = $request->withAttribute('user', $this->account);
        $request->getBody()->write(json_encode($editedCategory));

        $response = new Response();
        $response = $this->categoryController->edit($request, $response, [ 'id' => $this->category->getId() ]);

        parent::assertEquals(200, $response->getStatusCode());
    }

    public function testDelete() {
        $environment = Environment::mock([
            'REQUEST_METHOD' => 'DELETE',
            'REQUEST_URI' => '',
            'QUERY_STRING' => '',
            'CONTENT_TYPE' => 'application/json;charset=utf8',
        ]);

        $request = Request::createFromEnvironment($environment);
        $request = $request->withAttribute('user', $this->account);

        $response = new Response();
        $response = $this->categoryController->delete($request, $response, [ 'id' => $this->category->getId() ]);

        parent::assertEquals(200, $response->getStatusCode());
    }

}