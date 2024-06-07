<?php

require "App/Helpers/DatabaseUtility.php";
require "App/models/Client.php";

try {
    $DB = new App\Helpers\DatabaseUtility("localhost", "root", "asdfc6091", "banksystem");


    if ($client) {
        $client->delete();
    }
} catch (Exception $exception) {
    echo "error: " . $exception->getMessage();
}

/**
 * login
 * if client 
 * show transaction menu [withdraw,deposite,see balance]
 * if admin 
 * [show client,add new client,delete client,find client,
 * manage admins[add admin,delete admin,find admin,update admin,show admin,set admin permission]]
 */

/**
 * transfer to create class with (date,time,sourceAccountNumber,DestinationAccountNumber,Amount,finalsrcBalance,finaldestBalance,admin)
 */
