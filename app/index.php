<?php

require '../vendor/autoload.php';
include '../config/config.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use SciMS\Middlewares\TokenMiddleware;

$app = new \Slim\App();

// Add CorsMiddleware to app
$app->add('SciMS\Middlewares\CorsMiddleware');

/***********
 * Article *
 ***********/
$app->get('/articles', 'SciMS\Controllers\ArticleController:getPage');

$app->group('/article', function() {
  $this->post('', 'SciMS\Controllers\ArticleController:create')
    ->add('SciMS\Middlewares\TokenMiddleware');
  $this->group('/{id}', function() {
    $this->get('', 'SciMS\Controllers\ArticleController:getById');
    $this->put('', 'SciMS\Controllers\ArticleController:edit')
      ->add('SciMS\Middlewares\TokenMiddleware');
    $this->delete('', 'SciMS\Controllers\ArticleController:delete')
      ->add('SciMS\Middlewares\TokenMiddleware');
  });
});

/***********
 * Account *
 ***********/
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

/************
 * Category *
 ************/
$app->get('/categories', 'SciMS\Controllers\CategoryController:getCategories');

$app->group('/category', function() {
  $this->post('', 'SciMS\Controllers\CategoryController:addCategory')
    ->add('SciMS\Middlewares\TokenMiddleware');
  $this->group('/{id}', function() {
    $this->get('', 'SciMS\Controllers\CategoryController:getCategory');
    $this->delete('', 'SciMS\Controllers\CategoryController:delete')
      ->add('SciMS\Middlewares\TokenMiddleware');
    $this->put('', 'SciMS\Controllers\CategoryController:edit')
      ->add('SciMS\Middlewares\TokenMiddleware');
  });
});

$app->run();
