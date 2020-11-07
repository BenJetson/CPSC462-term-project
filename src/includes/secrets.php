<?php

require_once 'autoload.php';

// Check to make sure that the secret file location is defined. If the
// secret directory is not set, this application must fail.
if (!isset($_SERVER["SECRET_DIR"]) || empty($_SERVER["SECRET_DIR"])) {
    throw new RuntimeException("cannot determine location of secret file: " .
        "SECRET_DIR is not set");
}

// Load the secrets from the secret file into the $_SERVER array.
Dotenv\Dotenv::createImmutable(
    $_SERVER["SECRET_DIR"],
    ".env"
)->load();

// The named secrets below are required by this application.
$required_secrets = [
    "LOGIN_SECRET",
    "MYSQL_ADDR",
    "MYSQL_USER",
    "MYSQL_PASSWORD",
    "MYSQL_DATABASE",
];

// Check to see if each of the required secrets exist. If any required value is
// not set, this application must fail.
foreach ($required_secrets as $key) {
    if (!isset($_SERVER[$key]) || empty($_SERVER[$key])) {
        throw new RuntimeException("missing secret value for $key");
    }
}
