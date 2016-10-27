<?php

require '../vendor/autoload.php';
include '../config/config.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App();

$app->post('/account', 'SciMS\Controllers\AccountController:create');
$app->post('/auth', 'SciMS\Controllers\AuthentificationController:auth');

$app->run();
