<?php

class HTTPStatus
{
    // Success
    const STATUS_OK = 200;
    const STATUS_NO_CONTENT = 204;

    // Redirects
    const STATUS_MOVED_PERMANENTLY = 301;
    const STATUS_FOUND = 302;
    const STATUS_SEE_OTHER = 303;

    // User Errors
    const STATUS_BAD_REQUEST = 400;
    const STATUS_NOT_AUTHORIZED = 401;
    const STATUS_FORBIDDEN = 403;
    const STATUS_NOT_FOUND = 404;

    // Server Errors
    const STATUS_INTERNAL_SERVER_ERROR = 500;
    const STATUS_NOT_IMPLEMENTED = 501;

    private static $descriptionMap = array(
        self::STATUS_OK => "OK",
        self::STATUS_NO_CONTENT => "No Content",

        self::STATUS_MOVED_PERMANENTLY => "Moved Permanently",
        self::STATUS_FOUND => "Found",
        self::STATUS_SEE_OTHER => "See Other",

        self::STATUS_BAD_REQUEST => "Bad Request",
        self::STATUS_NOT_AUTHORIZED => "Not Authorized",
        self::STATUS_FORBIDDEN => "Forbidden",
        self::STATUS_NOT_FOUND => "Not Found",

        self::STATUS_INTERNAL_SERVER_ERROR => "Internal Server Error",
        self::STATUS_NOT_IMPLEMENTED => "Not Implemented",
    );

    public static function describe($statusCode)
    {
        if (!isset(self::$descriptionMap[$statusCode])) {
            error_log("no known description for status code $statusCode");
            return "Unknown Status";
        }
        return self::$descriptionMap[$statusCode];
    }

    public static function isError($statusCode)
    {
        // HTTP status code 400 and above represent errors.
        return $statusCode >= 400;
    }
}
