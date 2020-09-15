<?php

require_once 'secrets.php';

$db = new PDO(
    'mysql:host=' . $_SERVER["MYSQL_ADDR"] .
        ';dbname=' . $_SERVER["MYSQL_DATABASE"],
    $_SERVER["MYSQL_USER"],
    $_SERVER["MYSQL_PASSWORD"]
);

// Make the database driver throw an exception when the database encounters
// an error when processing a query.
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
