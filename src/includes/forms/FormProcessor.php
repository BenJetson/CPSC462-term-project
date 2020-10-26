<?php

require_once __DIR__ . "/../types/User.php";
require_once __DIR__ . "/../types/HTTPStatus.php";
require_once __DIR__ . "/../pages/RequestStatusPage.php";


abstract class FormProcessor
{
    const OPERATION = "op";

    /** @var array[string]callable(PDO, User) */
    protected static $operation_map;

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

        $recipe = static::$operation_map[$operation];
        if (
            !isset($recipe["handler"]) ||
            !isset($recipe["req_fields"]) ||
            !isset($recipe["opt_fields"])
        ) {
            throw new RuntimeException(
                "recipe for operation '$operation' is incomplete"
            );
        }

        $handler = $recipe["handler"];
        if (!is_callable($handler)) {
            throw new RuntimeException(
                "handler for '$operation' is not callable"
            );
        }

        try {
            self::checkForExtraFields($recipe, $operation);

            foreach ($recipe["req_fields"] as $field) {
                self::validateField($field, $operation, true);
            }

            foreach ($recipe["opt_fields"] as $field) {
                self::validateField($field, $operation, false);
            }

            call_user_func_array($handler, [$pdo, $user]);
        } catch (InvalidArgumentException $iae) {
            (new RequestStatusPage(
                HTTPStatus::STATUS_BAD_REQUEST,
                $user,
                $iae->getMessage()
            ))->render();
            exit();
        }
    }

    private static function checkForExtraFields($recipe, $operation)
    {
        $fieldDefs = array_merge(
            $recipe["req_fields"],
            $recipe["opt_fields"],
            [[self::OPERATION]]
        );
        $fieldList = [];

        foreach ($fieldDefs as $field) {
            if (!is_array($field) || count($field) < 1) {
                throw new RuntimeException(
                    "recipe for operation '$operation' contains bad fields"
                );
            }

            array_push($fieldList, $field[0]);
        }

        foreach (array_keys($_POST) as $key) {
            if (!in_array($key, $fieldList)) {
                throw new InvalidArgumentException(
                    "Operation '$operation' received unknown field '$key'."
                );
            }
        }
    }

    private static function validateField($field, $operation, $required)
    {
        $name = $field[0];
        $filter = isset($field[1]) ? $field[1] : null;

        if (!isset($_POST[$name])) {
            if ($required) {
                throw new InvalidArgumentException(
                    "Required field '$name' is not set."
                );
            }
            return; // Field was optional and not set; no validation necessary.
        }

        if ($filter !== null) {
            $filteredVal = filter_var($_POST[$name], $filter);
            if ($filteredVal === false) {
                throw new InvalidArgumentException(
                    "Field '$name' did not match the requested format."
                );
            }
            $_POST[$name] = $filteredVal;
        }
    }
}
