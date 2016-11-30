<?php

use PHPUnit\Framework\TestCase;
use SciMS\Models\Account;

class AccountTest extends TestCase {
    public function testJsonSerialize() {
        $expected = [
            'uid' => '123456azerty',
            'email' => 'john.doe@example.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'biography' => 'John Doe\'s biography.'
        ];

        $account = new Account();
        $account->setUid($expected['uid']);
        $account->setEmail($expected['email']);
        $account->setFirstName($expected['first_name']);
        $account->setLastName($expected['last_name']);
        $account->setBiography($expected['biography']);

        parent::assertEquals($expected, $account->jsonSerialize());
    }
}