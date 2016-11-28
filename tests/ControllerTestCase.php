<?php

require_once './vendor/autoload.php';
require_once './config/config.php';

use PHPUnit\Framework\TestCase;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;

class ControllerTestCase extends TestCase {
    protected function createRequest($method, $uri) {
        $env = Environment::mock([
            'REQUEST_METHOD' => $method,
            'REQUEST_URI' => $uri
        ]);
        
        return Request::createFromEnvironment($env);
    }
}