<?php

require '../vendor/autoload.php';
include '../config/config.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use SciMS\Middlewares\TokenMiddleware;

$app = new \Slim\App();

$app->post('/signup', 'SciMS\Controllers\SignUpController:post');
$app->post('/signin', 'SciMS\Controllers\SignInController:post');
$app->post('/article', 'SciMS\Controllers\ArticleController:post')
  ->add(new TokenMiddleware());
$app->get('/articles', 'SciMS\Controllers\ArticlesController:get');

$app->run();
