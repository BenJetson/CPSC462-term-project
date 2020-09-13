<?php

$db = new PDO(
    'mysql:host=' . $_SERVER["MYSQL_ADDR"] .
        ';dbname=' . $_SERVER["MYSQL_DATABASE"],
    $_SERVER["MYSQL_USER"],
    $_SERVER["MYSQL_PASSWORD"]
);
