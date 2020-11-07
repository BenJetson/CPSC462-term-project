<?php

require_once 'FormProcessor.php';
require_once __DIR__ . "/../db/user.php";
require_once __DIR__ . "/../types/User.php";
require_once __DIR__ . "/../pages/RequestStatusPage.php";


class UserProfileFP extends FormProcessor
{
    const OP_REGISTER = "register";
    const OP_EDIT = "edit_profile";

    protected static $operation_map = [
        self::OP_REGISTER => [
            "handler" => "static::processRegistration",
            "req_fields" => [
                ["first_name"],
                ["last_name"],
                ["email", FILTER_VALIDATE_EMAIL],
                ["telephone"], // TODO consider regex validation, may need refactor
                ["dob"],
                ["address_line_1"],
                ["city"],
                ["state"],
                ["zip", FILTER_VALIDATE_INT],
                ["password"],
                ["confirm_password"],
            ],
            "opt_fields" => [
                ["address_line_2"],
                ["tos_accept"],
            ],
            "req_admin" => false,
        ],
        self::OP_EDIT => [
            "handler" => "static::processProfileEdit",
            "req_fields" => [
                ["user_id", FILTER_VALIDATE_INT],
                ["first_name"],
                ["last_name"],
                ["email", FILTER_VALIDATE_EMAIL],
                ["telephone"], // TODO consider regex validation, may need refactor
                ["dob"],
                ["address_line_1"],
                ["city"],
                ["state"],
                ["zip", FILTER_VALIDATE_INT],
            ],
            "opt_fields" => [
                ["address_line_2"],
            ],
            // Will check admin for non-self updates in handler.
            "req_admin" => false,
        ],
    ];

    private static function getUserFromForm($is_registration)
    {
        $user = new User();

        $user->first_name     = $_POST["first_name"];
        $user->last_name      = $_POST["last_name"];
        $user->email          = $_POST["email"];
        $user->telephone      = $_POST["telephone"];
        $user->dob            = new DateTime($_POST["dob"]);
        $user->address_line_1 = $_POST["address_line_1"];
        $user->address_line_2 = $_POST["address_line_2"];
        $user->address_city   = $_POST["city"];
        $user->address_state  = $_POST["state"];
        $user->address_zip    = $_POST["zip"];
        if ($is_registration) {
            $user->pass_hash      = password_hash(
                $_POST["password"],
                PASSWORD_DEFAULT
            );
        }

        return $user;
    }

    protected static function processRegistration(PDO $pdo, User $user)
    {
        if ($user->user_id > 0) {
            throw new InvalidArgumentException(
                "New user registration was aborted because you are already " .
                    "logged in to an account. If you would like to register " .
                    "a new user, log out first."
            );
        }

        if (!isset($_POST["tos_accept"]) || $_POST["tos_accept"] !== "on") {
            throw new InvalidArgumentException(
                "You must accept the terms of service in order to register."
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

        $user = self::getUserFromForm(true);
        create_user($pdo, $user);

        header("Location: login.php");
    }

    protected static function processProfileEdit(PDO $pdo, User $user)
    {
        $profile = self::getUserFromForm(false);
        $profile->user_id = $_POST["user_id"];

        if (
            $profile->user_id !== $user->user_id
            && !$user->is_admin
        ) {
            // FIXME perhaps there might be a better way to handle forbidden
            // like throwing a sentinel exception or something
            (new RequestStatusPage(
                HTTPStatus::STATUS_FORBIDDEN,
                $user
            ))->render();
            exit();
        }

        update_user_profile($pdo, $profile);

        $href = "profile.php";
        if (intval($profile->user_id) !== $user->user_id) {
            $href .= "?user_id=$profile->user_id";
        }

        header("Location: $href");
    }
}
