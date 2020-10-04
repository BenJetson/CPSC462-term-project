<?php

class HTTPStatus
{
    const STATUS_OK = 200;
    const STATUS_NO_CONTENT = 204;

    const STATUS_BAD_REQUEST = 400;
    const STATUS_NOT_AUTHORIZED = 401;
    const STATUS_FORBIDDEN = 403;
    const STATUS_NOT_FOUND = 404;

    const STATUS_INTERNAL_SERVER_ERROR = 500;
    const STATUS_NOT_IMPLEMENTED = 501;

    private static $descriptionMap = array(
        self::STATUS_OK => "OK",
        SELF::STATUS_NO_CONTENT => "No Content",
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
            return "Unknown (TODO)";
        }
        return self::$descriptionMap[$statusCode];
    }

    public static function isError($statusCode)
    {
        // HTTP status code 400 and above represent errors.
        return $statusCode >= 400;
    }
}
