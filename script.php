<?php

use RokasApp\Factory\UserFactory;
use RokasApp\Model\User;
use RokasApp\Repository\Storage;

require_once realpath("vendor/autoload.php");

// if (!isset($argv[1])) {
//     echo "\nargument missing (path to csv file)";
// }

// echo file_get_contents($argv[1]);

$storage = new Storage();

$user = UserFactory::create(1, User::USER_TYPE_LEGAL, $storage);
// $user = UserFactory::create(2, User::USER_TYPE_NATURAL, $storage);
// $user = UserFactory::create(3, User::USER_TYPE_LEGAL, $storage);
// $user = UserFactory::create(4, User::USER_TYPE_NATURAL, $storage);
// $user = UserFactory::create(5, User::USER_TYPE_LEGAL, $storage);

$user = $storage->findOrCreateUser(2, User::USER_TYPE_NATURAL);
$user = $storage->findOrCreateUser(3, User::USER_TYPE_NATURAL);
$user = $storage->findOrCreateUser(4, User::USER_TYPE_NATURAL);

print_r($storage->getAllUsers());

// $row = 1;
// if (($handle = fopen($argv[1], "r")) !== FALSE) {
//     while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
//         $num = count($data);
//         echo "\n$num fields in line $row: \n";
//         $row++;

//         $date = $data[0];
//         $user = $data[1];
//         $userType = $data[2];
//         $cashOperationType = $data[3];
//         $cashOperationAmount = $data[4];
//         $cashOperationCurrency = $data[5];
//     }
//     fclose($handle);
// }