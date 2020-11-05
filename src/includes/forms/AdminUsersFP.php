<?php

require_once 'FormProcessor.php';
require_once __DIR__ . "/../db/user.php";
require_once __DIR__ . "/../types/User.php";
require_once __DIR__ . "/../pages/RequestStatusPage.php";

class AdminUsersFP extends FormProcessor
{
    const OP_MANAGE = "manage_account";

    protected static $operation_map = [
        self::OP_MANAGE => [
            "handler" => "static::processManageAccount",
            "req_fields" => [
                ["user_id", FILTER_VALIDATE_INT],
                ["is_disabled"],
                ["is_admin"],
            ],
            "opt_fields" => [],
            "req_admin" => true,
        ],
    ];

    protected static function processManageAccount(PDO $pdo, User $user)
    {
        $user_id = intval($_POST["user_id"]);
        $is_disabled = $_POST["is_disabled"] === "on";
        $is_admin = $_POST["is_admin"] === "on";

        if ($user_id === 1) {
            throw new InvalidArgumentException(
                "Cannot use account management on system owner user."
            );
        } else if ($is_disabled && $is_admin) {
            throw new InvalidArgumentException(
                "Disabled accounts cannot be administrators."
            );
        }

        set_user_management_attributes($pdo, $user_id, $is_disabled, $is_admin);

        header("Location: admin-users.php");
    }
}
