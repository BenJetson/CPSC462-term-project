<?php

require_once __DIR__ . '/../secrets.php';

function db_connect()
{
    $pdo = new PDO(
        'mysql:host=' . $_SERVER["MYSQL_ADDR"] .
            ';dbname=' . $_SERVER["MYSQL_DATABASE"],
        $_SERVER["MYSQL_USER"],
        $_SERVER["MYSQL_PASSWORD"],
        [
            PDO::MYSQL_ATTR_INIT_COMMAND => "
                SET NAMES utf8;
                SET time_zone = 'America/New_York';
            ",
        ]
    );

    // Make the database driver throw an exception when the database encounters
    // an error when processing a query.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $pdo;
}
