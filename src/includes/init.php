<?php

// For security purposes, we do not want any logs from the system to be
// leaked to the end user.
ini_set('display_errors', 0);

// Start output buffer so that the error handler can flush.
ob_start();

// This function will be called on any uncaught errors/exceptions. It shall
// render a nice page for the user to let them know an error has happened.
function handle_error()
{
    ob_clean();

    require_once 'pages/RequestStatusPage.php';
    (new RequestStatusPage(
        HTTPStatus::STATUS_INTERNAL_SERVER_ERROR
    ))->render();
}

// Set error/exception handlers to use above function.
set_error_handler("handle_error");
set_exception_handler("handle_error");

// Always start or resume the PHP session when a user requests a page.
session_start();

// If a log file is specified, write the application errors there.
if (isset($_SERVER["LOG_FILE"])) {
    ini_set("error_log", $_SERVER["LOG_FILE"]);
    ini_set("log_errors", 1);
}
