<?php

require_once 'FormProcessor.php';
require_once __DIR__ . "/../db/user.php";

class ChangePasswordFP extends FormProcessor
{
    const OP_CHANGE_PASS = "change_password";

    protected static $operation_map = [
        self::OP_CHANGE_PASS => [
            "handler" => "static::processPasswordChange",
            "req_fields" => [
                ["current_password"],
                ["password"],
                ["confirm_password"],
            ],
            "opt_fields" => [],
            "req_admin" => false,
        ],
    ];

    protected static function processPasswordChange(PDO $pdo, User $user)
    {
        $authorized = password_verify(
            $_POST["current_password"],
            $user->pass_hash
        );

        if (!$authorized) {
            throw new InvalidArgumentException(
                "Current password is incorrect. Password change aborted."
            );
        }

        if ($_POST["password"] !== $_POST["confirm_password"]) {
            throw new InvalidArgumentException(
                "Passwords do not match!"
            );
        } elseif ($_POST["password"] === strtolower($_POST["password"])) {
            throw new InvalidArgumentException(
                "Password does not contain a capital letter!"
            );
        }

        $pass_hash = password_hash(
            $_POST["password"],
            PASSWORD_DEFAULT
        );

        change_user_password($pdo, $user->user_id, $pass_hash);

        header("Location: login.php");
    }
}
