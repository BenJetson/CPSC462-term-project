<?php

require_once __DIR__ . '/../components/Navbar.php';
require_once __DIR__ . '/../components/AlertBox.php';
require_once __DIR__ . '/../types/HTTPStatus.php';
require_once 'Page.php';

class RequestStatusPage extends Page
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

        $title = "$this->statusCode ";
        $title .= HTTPSTatus::describe($this->statusCode);

        $message = $this->message;
        if (!isset($message)) {
            $message = self::$defaultMessages[$this->statusCode];
        }

        parent::__construct($title, [
            new Navbar($this->user, $title),
            new AlertBox(
                HTTPStatus::isError($this->statusCode)
                    ? AlertBox::TYPE_DANGER
                    : AlertBox::TYPE_INFO,
                null,
                $message
            ),
        ]);
    }

    public function render()
    {
        http_response_code($this->statusCode);
        parent::render();
    }
}
