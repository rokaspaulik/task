<?php

use RokasApp\Factory\UserFactory;
use RokasApp\Model\Currency;
use RokasApp\Model\User;
use RokasApp\Service\CashOperation;

require_once realpath("vendor/autoload.php");

$user = UserFactory::create(1, User::USER_TYPE_NATURAL);

$op = new CashOperation("2020-05-26", $user, CashOperation::CASH_TYPE_IN, Currency::CURRENCY_EUR, 1000);

// echo $user;
echo $op;