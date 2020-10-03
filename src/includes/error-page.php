<?php

require_once 'page.php';
require_once 'components/Navbar.php';
require_once 'components/AlertBox.php';

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
        self::STATUS_NOT_IMPLEMENTED => "Not Implemented"

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

class RequestStatusPage
{
    private $statusCode;
    private $message;
    private $user;

    private static $defaultMessages = array(
        HTTPStatus::STATUS_OK =>
        "Your request was processed successfully.",
        HTTPStatus::STATUS_NO_CONTENT =>
        "Your request was processed successfully.",
        HTTPStatus::STATUS_BAD_REQUEST =>
        "Your request contained invalid data which could not be processed.",
        HTTPStatus::STATUS_NOT_AUTHORIZED =>
        "You must login to access the requested resource.",
        HTTPStatus::STATUS_FORBIDDEN =>
        "You do not have permission to access the requested resource.",
        HTTPStatus::STATUS_NOT_FOUND =>
        "The requested resource could not be located on this server.",
        HTTPStatus::STATUS_INTERNAL_SERVER_ERROR =>
        "The server encountered an unexpected error when handling the
            request. Please contact the administrator for assistance.",
        HTTPStatus::STATUS_NOT_IMPLEMENTED =>
        "This feature has not yet been implemented on the server. Please
            contact the administrator for assistance.",
    );

    public function __construct($statusCode, $user = null, $message = null)
    {
        $this->statusCode = $statusCode;
        $this->message = $message;
        $this->user = $user;
    }

    public function render()
    {
        http_response_code($this->statusCode);

        $title = "$this->statusCode ";
        $title .= HTTPSTatus::describe($this->statusCode);

        $message = $this->message;
        if (!isset($message)) {
            $message = self::$defaultMessages[$this->statusCode];
        }

        $page = new Page($title, [
            new Navbar($this->user, $title),
            new AlertBox(
                HTTPStatus::isError($this->statusCode)
                    ? AlertBox::TYPE_DANGER
                    : AlertBox::TYPE_INFO,
                null,
                $message
            ),
        ]);

        $page->render();
    }
}
