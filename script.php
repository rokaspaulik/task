<?php

use RokasApp\Factory\CashOperationFactory;
use RokasApp\Factory\UserFactory;
use RokasApp\Repository\Storage;
use RokasApp\Service\FeeCalculator;

require_once realpath("vendor/autoload.php");

if (!isset($argv[1])) {
    echo "\nargument missing (path to csv file)";
}

$storage = new Storage();
$feeCalculator = new FeeCalculator();

$row = 1;
if (($handle = fopen($argv[1], "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        $row++;

        $date = $data[0];
        $user = (int)$data[1];
        $userType = $data[2];
        $cashOperationType = $data[3];
        $cashOperationAmount = $data[4];
        $cashOperationCurrency = $data[5];

        $user = $storage->findOrCreateUser($user, $userType);
        $cashOperation = CashOperationFactory::create($date, $user, $cashOperationType, $cashOperationCurrency, $cashOperationAmount);
        echo "\n" . $feeCalculator->calculate($cashOperation);
    }
    fclose($handle);
}
