<?php

require "App/Helpers/DatabaseUtility.php";
require "App/models/Client.php";

try {
    $DB = new App\Helpers\DatabaseUtility("localhost", "root", "asdfc6091", "banksystem");
    $client =  App\Models\Client::getClientByAccountNumber($DB, 1007);
    if ($client) {
        $client->delete();
        echo $client->getEmail();
    }
} catch (Exception $exception) {
    echo "error: " . $exception->getMessage();
}
