<?php

require_once 'HTTPStatus.php';

// TODO maybe use these?

/**
 * UserErrorException is a throwable which is the base class for all problems
 * relating to a user's request.
 *
 * This throwable will be caught by the global exception handler and render the
 * appropriate error page with an optional user message.
 */
class UserErrorException extends RuntimeException
{
    public $cause;
    public $user_message = null;

    public $status_code = HTTPStatus::STATUS_BAD_REQUEST;

    public function __construct($cause, $user_message = null)
    {
        $this->cause = $cause;
        $this->user_message = $user_message;
    }
}

/**
 * IdentityException is a subclass of UserErrorEception that should be thrown
 * when it is not possible to discern the identify of the user that made a
 * request.
 */
class IdentityException extends UserErrorException
{
    public $status_code = HTTPStatus::STATUS_NOT_AUTHORIZED;
}

/**
 * SecurityException is a subclass of UserErrorException that should be thrown
 * when the identity of a user is known but the user does not have sufficient
 * privileges to complete this request.
 */
class SecurityException extends UserErrorException
{
    public $status_code = HTTPStatus::STATUS_FORBIDDEN;
}
