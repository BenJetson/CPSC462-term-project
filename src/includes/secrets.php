<?php

$dotenv = Dotenv\Dotenv::createImmutable($_SERVER["SECRET_DIR"], ".env");
$dotenv->load();
