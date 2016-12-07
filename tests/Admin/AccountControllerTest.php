<?php
use Faker\Factory;
use PHPUnit\Framework\TestCase;
use SciMS\Controllers\Admin\AccountController;
use SciMS\Models\Account;
use SciMS\Models\AccountQuery;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * @author Antoine Chauvin <antoine.chauvin@etu.univ-rouen.fr>
 */
class AccountControllerTest extends TestCase {

    /**
     * @var AccountController
     */
    private $controller;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    protected function setUp()
    {
        $this->controller = new AccountController();
        $this->faker = Factory::create();
    }

    private function getResponseBodyAsString(Response $response)
    {
        $body = $response->getBody();
        $body->rewind();
        return $body->getContents();
    }

    public function testIndex()
    {
        $request = Request::createFromEnvironment(Environment::mock());

        $response = $this->controller->index($request, new Response());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('application/json', $response->getHeader('Content-Type')[0]);
        $this->assertNotEmpty($this->getResponseBodyAsString($response));
    }

    public function testCreate()
    {
        $values = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->email,
        ];

        $request = Request::createFromEnvironment(Environment::mock())
            ->withParsedBody($values)
        ;

        $response = $this->controller->create($request, new Response());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('application/json', $response->getHeader('Content-Type')[0]);
        $this->assertNotEmpty($this->getResponseBodyAsString($response));
        $this->assertNotEmpty(AccountQuery::create()->findByArray([
            'firstName' => $values['first_name'],
            'lastName' => $values['last_name'],
            'email' => $values['email'],
        ]));
    }

    public function testUpdate()
    {
        $account = new Account();
        $account->setUid(uniqid());
        $account->setEmail($this->faker->email);
        $account->setFirstName($this->faker->firstName);
        $account->setLastName($this->faker->lastName);
        $account->setPassword($this->faker->password);
        $account->save();

        $values = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
        ];

        $request = Request::createFromEnvironment(Environment::mock())
            ->withQueryParams(['uid' => $account->getUid()])
            ->withParsedBody($values)
        ;

        $response = $this->controller->update($request, new Response());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('application/json', $response->getHeader('Content-Type')[0]);
        $this->assertNotEmpty($this->getResponseBodyAsString($response));

        $newAccount = AccountQuery::create()->findOneByUid($account->getUid());
        $this->assertEquals($values['first_name'], $newAccount->getFirstName());
        $this->assertEquals($values['last_name'], $newAccount->getLastName());
    }

    public function testDelete()
    {
        $account = new Account();
        $account->setUid(uniqid());
        $account->setEmail($this->faker->email);
        $account->setFirstName($this->faker->firstName);
        $account->setLastName($this->faker->lastName);
        $account->setPassword($this->faker->password);
        $account->save();

        $request = Request::createFromEnvironment(Environment::mock())
            ->withQueryParams(['uid' => $account->getUid()])
        ;

        $response = $this->controller->destroy($request, new Response());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEmpty(AccountQuery::create()->findOneByUid($account->getUid()));
    }
}