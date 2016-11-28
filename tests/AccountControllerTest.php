<?php

require_once 'vendor/autoload.php';
require_once 'config/config.php';
require_once 'ControllerTest.php';

use Slim\Http\Response;
use SciMS\Controllers\AccountController;

class AccountControllerTest extends ControllerTest {
    public function testSignup() {
        $request = parent::createRequest('POST', '/account/create');
        $response = new Response();
        $accountController = new AccountController();
        $response = $accountController->create($request, $response)->getBody();
        $json = json_decode($response);

        $expectedErrors = [
            'INVALID_EMAIL',
            'INVALID_FIRST_NAME',
            'INVALID_LAST_NAME',
            'INVALID_PASSWORD'
        ];

        parent::assertEquals($expectedErrors, $json->errors);
    }
}