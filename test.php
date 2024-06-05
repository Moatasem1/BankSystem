<?php

require "App/Helpers/DatabaseUtility.php";

try {
    $DB = new App\Helpers\DatabaseUtility("localhost", "root", "asdfc6091", "banksystem");

    $stmt = $DB->prepare(
        "insert into users (first_name,last_name,email,phone) 
        values(?,?,?,?)",
        ["motaz", "al-naimat", "motazalnaimat@gmail.com", "0777257991"]
    );

    $insertedId = 0;
    $DB->execute($stmt, $insertedId);
    echo $insertedId;
} catch (Exception $exception) {
    echo "error: " . $exception->getMessage();
}
