<?php

require_once 'FormProcessor.php';
require_once __DIR__ . "/../db/restore.php";
require_once __DIR__ . "/../types/User.php";

class AdminRestoreFP extends FormProcessor
{
    const OP_RESTORE = "restore";

    protected static $operation_map = [
        self::OP_RESTORE => [
            "handler" => "static::processRestore",
            "req_fields" => [],
            "opt_fields" => [],
            "req_admin" => true,
        ],
    ];

    protected static function processRestore(PDO $pdo, User $user)
    {
        error_log("Restore from " . $_FILES["restore_file"]["tmp_name"]);
        restore_from_file($pdo, $_FILES["restore_file"]["tmp_name"]);

        header("Location");
    }
}
