<?php

require '../vendor/autoload.php';
include '../config/config.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use SciMS\Middlewares\TokenMiddleware;

$app = new \Slim\App();

$app->post('/signup', 'SciMS\Controllers\SignUpController:post');

$app->post('/article', 'SciMS\Controllers\ArticleController:create')
  ->add(new TokenMiddleware());

$app->get('/articles', 'SciMS\Controllers\ArticleController:getPage');

$app->group('/account', function() {
  $this->get('/{uid}', 'SciMS\Controllers\AccountController:get')
    ->add(new TokenMiddleware());
  $this->post('/create', 'SciMS\Controllers\AccountController:create');
  $this->post('/login', 'SciMS\Controllers\AccountController:login');
  $this->put('/password', 'SciMS\Controllers\AccountController:changePassword')
    ->add(new TokenMiddleware());
});

$app->run();
