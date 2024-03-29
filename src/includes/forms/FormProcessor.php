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
        // Sanitize all form fields to remove/escape any HTML.
        foreach ($_POST as $key => &$value) {
            $_POST[$key] = htmlspecialchars(strip_tags($value));
        }

        // Check to see which form operation we should perform.
        if (!isset($_POST[self::OPERATION])) {
            (new RequestStatusPage(
                HTTPStatus::STATUS_BAD_REQUEST,
                $user,
                "No form operation specified!"
            ))->render();
            exit();
        }

        $operation = $_POST[self::OPERATION];

        // Determine recipe for handling this operation.
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
            !isset($recipe["opt_fields"]) ||
            !isset($recipe["req_admin"])
        ) {
            throw new RuntimeException(
                "recipe for operation '$operation' is incomplete"
            );
        }

        // If the recipe requires admin privileges, check if user is an admin.
        if ($recipe["req_admin"] && !$user->is_admin) {
            (new RequestStatusPage(
                HTTPStatus::STATUS_FORBIDDEN,
                $user,
                "Only administrative users may perform this action."
            ))->render();
            exit();
        }

        // Fetch the handler function for this operation from the recipe, and
        // ensure that it is callable.
        $handler = $recipe["handler"];
        if (!is_callable($handler)) {
            throw new RuntimeException(
                "handler for '$operation' is not callable"
            );
        }

        try {
            // Validate all form fields to ensure that only desired fields are
            // provided and that required fields have a value.
            self::checkForExtraFields($recipe, $operation);

            foreach ($recipe["req_fields"] as $field) {
                self::validateField($field, $operation, true);
            }

            foreach ($recipe["opt_fields"] as $field) {
                self::validateField($field, $operation, false);
            }

            // Call the handler.
            call_user_func_array($handler, [$pdo, $user]);
        } catch (InvalidArgumentException $iae) {
            // Catch InvalidArgumentExceptions thrown by either the field
            // validation or the handler and show the user an error message.
            (new RequestStatusPage(
                HTTPStatus::STATUS_BAD_REQUEST,
                $user,
                $iae->getMessage()
            ))->render();
            exit();
        }

        // Check to see if the handler set a redirect, and if not, display a
        // generic success message and write a warning to the console.
        $redirect_set = false;
        foreach (headers_list() as $header) {
            if (strpos($header, "Location") === 0) {
                $redirect_set = true;
            }
        }

        if (!$redirect_set) {
            error_log("no redirect was set after processing for form " .
                " operation '$operation' of " . get_called_class());

            header("Refresh: 5; index.php");
            (new RequestStatusPage(
                HTTPStatus::STATUS_OK,
                $user,
                "The form data you sent has been saved. You will be returned " .
                    "to the homepage momentarily."
            ))->render();
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

        if ($required && strlen($_POST[$name]) < 1) {
            throw new InvalidArgumentException(
                "Required field '$name' cannot be empty."
            );
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
