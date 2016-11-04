<?php

require '../vendor/autoload.php';
include '../config/config.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use SciMS\Middlewares\TokenMiddleware;

$app = new \Slim\App();

$app->group('/article', function() {
  $this->post('', 'SciMS\Controllers\ArticleController:create')
    ->add('SciMS\Middlewares\TokenMiddleware');
  $this->get('/{id}', 'SciMS\Controllers\ArticleController:getById');
  $this->delete('/{id}', 'SciMS\Controllers\ArticleController:delete')
    ->add('SciMS\Middlewares\TokenMiddleware');
});

$app->get('/articles', 'SciMS\Controllers\ArticleController:getPage');

$app->group('/account', function() {
  $this->put('', 'SciMS\Controllers\AccountController:changeInformations')
    ->add('SciMS\Middlewares\TokenMiddleware');
  $this->get('/{uid}', 'SciMS\Controllers\AccountController:get')
    ->add('SciMS\Middlewares\TokenMiddleware');
  $this->post('/create', 'SciMS\Controllers\AccountController:create');
  $this->post('/login', 'SciMS\Controllers\AccountController:login');
  $this->put('/password', 'SciMS\Controllers\AccountController:changePassword')
    ->add('SciMS\Middlewares\TokenMiddleware');
});

$app->get('/categories', 'SciMS\Controllers\CategoryController:getCategories');
$app->post('/category', 'SciMS\Controllers\CategoryController:addCategory')
  ->add('SciMS\Middlewares\TokenMiddleware');

$app->run();
