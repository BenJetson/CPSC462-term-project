<?php

// For security purposes, we do not want any logs from the system to be
// leaked to the end user.
ini_set('display_errors', 0);

// Start output buffer so that the error handler can flush.
ob_start();

// Set error/exception handlers to use below functions.
set_error_handler("handle_error");
set_exception_handler("handle_exception");
register_shutdown_function("handle_shutdown");

// This function will be called by the error/exception handlers. It shall
// render a nice page for the user to let them know an error has happened,
// flushing any other output already on the page.
function render_server_error()
{
    ob_clean();

    require_once 'pages/RequestStatusPage.php';
    require_once 'types/HTTPStatus.php';
    (new RequestStatusPage(
        HTTPStatus::STATUS_INTERNAL_SERVER_ERROR
    ))->render();
}

function is_fatal_error($errno)
{
    // PHP error codes are frustratingly not discernable via bitwise operations.
    // Thus the only way to know if an error is truly fatal is to see if the
    // error code is on the list of fatal error codes.
    //
    // Source: PHP Manual https://www.php.net/manual/en/ref.errorfunc.php#59192

    return in_array($errno, [
        E_ERROR,
        E_CORE_ERROR,
        E_PARSE,
        E_COMPILE_ERROR,
        E_USER_ERROR,
        E_RECOVERABLE_ERROR,
    ]);
}

function handle_error($errno)
{
    if (is_fatal_error($errno)) {
        render_server_error();
    }

    // Returning false will cascade and trigger the default error handler.
    return false;
}

function handle_exception(Exception $e)
{
    render_server_error();

    // Cascade and call the default exception handler by rethrowing.
    restore_exception_handler();
    throw $e;
}

function handle_shutdown()
{
    // This will catch certain other fatal errors that are not passed to the
    // default error handler, such as compile errors.
    //
    // Inspired by: https://stackoverflow.com/a/7313887

    $error = error_get_last();
    if ($error) {
        handle_error($error["type"]);
    }
}

// Always start or resume the PHP session when a user requests a page.
session_start();

// If a log file is specified, write the application errors there.
if (isset($_SERVER["LOG_FILE"])) {
    ini_set("error_log", $_SERVER["LOG_FILE"]);
    ini_set("log_errors", 1);
}

// Application should be aware of its tier, otherwise it must fail.
if (!isset($_SERVER["TIER"]) || empty($_SERVER["TIER"])) {
    throw new RuntimeException("could not determine tier: must set TIER");
}

// Detect when running on a non-local environment and redirect to HTTPS if the
// connection is using plain, unencrypted HTTP.
//
// Inspired by: https://stackoverflow.com/a/5106355
if (
    $_SERVER["TIER"] !== "local"
    && (!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] !== "on")
) {
    require_once 'types/HTTPStatus.php';

    // Get the host and URI values set by Apache.
    $host = $_SERVER["HTTP_HOST"];
    $uri = $_SERVER["REQUEST_URI"];

    // Rebuild the URL that the user visited, this time with HTTPS!
    $target = "https://$host$uri";

    // Do a 301 redirect to the secure page.
    // Since this is a permanent redirect, the browser can cache this.
    header("Location: $target", true, HTTPStatus::STATUS_MOVED_PERMANENTLY);
    exit();
}
