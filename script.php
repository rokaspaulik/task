<?php

use RokasApp\Model\Storage;

require_once realpath("vendor/autoload.php");

if (!isset($argv[1])) {
    echo "\nargument missing (path to csv file)";
}

// echo file_get_contents($argv[1]);

$storage = new Storage();

$row = 1;
if (($handle = fopen($argv[1], "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        echo "\n$num fields in line $row: \n";
        $row++;

        $date = $data[0];
        $user = $data[1];
        $userType = $data[2];
        $cashOperationType = $data[3];
        $cashOperationAmount = $data[4];
        $cashOperationCurrency = $data[5];
    }
    fclose($handle);
}