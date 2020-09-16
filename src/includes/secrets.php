<?php

require_once 'autoload.php';

Dotenv\Dotenv::createImmutable(
    $_SERVER["SECRET_DIR"],
    ".env"
)->load();
