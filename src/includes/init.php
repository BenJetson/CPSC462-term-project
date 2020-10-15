<?php

// For security purposes, we do not want any logs from the system to be
// leaked to the end user.
ini_set('display_errors', 0);

// Always start or resume the PHP session when a user requests a page.
session_start();

// If a log file is specified, write the application errors there.
if (isset($_SERVER["LOG_FILE"])) {
    ini_set("error_log", $_SERVER["LOG_FILE"]);
    ini_set("log_errors", 1);
}
