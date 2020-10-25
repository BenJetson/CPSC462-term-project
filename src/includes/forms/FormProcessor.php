<?php

require_once __DIR__ . "/../types/User.php";
require_once __DIR__ . "/../types/HTTPStatus.php";
require_once __DIR__ . "/../pages/RequestStatusPage.php";


abstract class FormProcessor
{
    const OPERATION = "op";

    /** @var array[string]callable(PDO, User) */
    protected static $operation_map;

    protected static function userError(User $user, $description)
    {
        // FIXME better way to do this?
        (new RequestStatusPage(
            HTTPStatus::STATUS_BAD_REQUEST,
            $user,
            $description
        ))->render();
        exit();
    }

    public static function process(PDO $pdo, User $user)
    {
        if (!isset($_POST[self::OPERATION])) {
            (new RequestStatusPage(
                HTTPStatus::STATUS_BAD_REQUEST,
                $user,
                "No form operation specified!"
            ))->render();
            exit();
        }

        $operation = $_POST[self::OPERATION];

        if (!isset(static::$operation_map[$operation])) {
            (new RequestStatusPage(
                HTTPStatus::STATUS_BAD_REQUEST,
                $user,
                "No known handler for operation '$operation'."
            ))->render();
            exit();
        }

        $handler = static::$operation_map[$operation];
        if (!is_callable($handler)) {
            throw new RuntimeException(
                "handler for '$operation' is not callable"
            );
        }

        call_user_func_array($handler, [$pdo, $user]);
    }
}
