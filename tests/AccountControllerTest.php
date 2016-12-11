<?php
use Faker\Generator;
use PHPUnit\Framework\TestCase;
use SciMS\Controllers\AccountController;
use SciMS\Models\Account;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * @author Antoine Chauvin <antoine.chauvin@etu.univ-rouen.fr>
 */
class AccountControllerTest extends TestCase
{
    /**
     * @var AccountController
     */
    private $controller;

    /**
     * @var Generator
     */
    private $faker;

    protected function setUp()
    {
        $this->controller = new AccountController();
        $this->faker = \Faker\Factory::create();
    }

    /**
     * @return Account
     */
    public function mockAccount()
    {
        $account = new Account();
        $account->setUid(uniqid());
        $account->setFirstName($this->faker->firstName);
        $account->setLastName($this->faker->lastName);
        $account->setEmail($this->faker->email);
        return $account;
    }

    public function testChangePasswordSuccess()
    {
        $oldPassword = $this->faker->password;
        $newPassword = $this->faker->password;

        $account = $this->mockAccount();
        $account->setPassword(password_hash($oldPassword, PASSWORD_DEFAULT));
        $account->save();

        $request = Request::createFromEnvironment(Environment::mock())
            ->withAttribute('user', $account)
            ->withParsedBody([
                'old_password' => $oldPassword,
                'new_password' => $newPassword,
            ])
        ;
        $response = $this->controller->changePassword($request, new Response());

        $this->assertEquals(200, $response->getStatusCode());

        $account->reload();
        $this->assertTrue(password_verify($newPassword, $account->getPassword()));
    }

    public function testChangePasswordFailure()
    {
        $oldPassword = $this->faker->password;
        $newPassword = $this->faker->password;

        $account = $this->mockAccount();
        $account->setPassword(password_hash($oldPassword, PASSWORD_DEFAULT));
        $account->save();

        $request = Request::createFromEnvironment(Environment::mock())
            ->withAttribute('user', $account)
            ->withParsedBody([
                'old_password' => $this->faker->password, // deliberately provide an incorrect password
                'new_password' => $newPassword,
            ])
        ;
        $response = $this->controller->changePassword($request, new Response());

        $this->assertEquals(400, $response->getStatusCode());

        $account->reload();
        $this->assertTrue(password_verify($oldPassword, $account->getPassword()));
    }

    public function testChangePasswordSuccessWithPreviouslyEmptyPassword()
    {
        $newPassword = $this->faker->password;

        $account = $this->mockAccount();
        $account->setPassword('');
        $account->save();

        $request = Request::createFromEnvironment(Environment::mock())
            ->withAttribute('user', $account)
            ->withParsedBody([
                'new_password' => $newPassword,
            ])
        ;
        $response = $this->controller->changePassword($request, new Response());

        $this->assertEquals(200, $response->getStatusCode());

        $account->reload();
        $this->assertTrue(password_verify($newPassword, $account->getPassword()));
    }
}