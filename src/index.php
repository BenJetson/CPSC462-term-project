<?php

require __DIR__ . '/vendor/autoload.php';

// use Dotenv\Dotenv;

$dotenv = Dotenv\Dotenv::createImmutable("/home/bfgodfr/secrets", "demo.env");
$dotenv->load();

?>

<?= $_SERVER["SUPER_SECRET"] ?>
