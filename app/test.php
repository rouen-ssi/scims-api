<?php

include '../vendor/autoload.php';
include '../config/config.php';

use SciMS\Models\User;

$user = new User();
$user->setUid('FEJK4468156DZD');
$user->setEmail('user@example.com');
$user->setFirstName('Mathieu');
$user->setLastName('Brochard');
$user->setPassword('52343fezfzs');
$user->save();
$user->delete();

echo 'Congratulations, it\' working!';
