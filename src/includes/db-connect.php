<?php

// FIXME let's avoid importing this everywhere and let Apache append this to all files
// see also: https://stackoverflow.com/questions/47761110/avoid-to-require-vendor-autoload-php-in-every-file

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable("/home/bfgodfr/secrets", "demo.env");
$dotenv->load();

$db = new PDO(
    'mysql:host=' . $_SERVER["DB_ADDR"] . ';dbname=' . $_SERVER["DB_NAME"],
    $_SERVER["DB_USER"],
    $_SERVER["DB_PASS"]
);

?>
