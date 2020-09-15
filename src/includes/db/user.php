<?php

require_once __DIR__ . "/../types/user.php";

define("GET_USER_QUERY", "
    SELECT
        user_id,
        is_admin,
        is_disabled,

        email,
        email_changed_at,
        email_confirmed,

        first_name,
        last_name,
        dob,
        telephone,

        address_line_1,
        address_line_2,
        address_city,
        address_state,
        address_zip,

        pass_hash,
        pass_changed_at,
        pass_attempts,
        pass_locked_at
    FROM user
");

function get_user_by_id(PDO $pdo, $userID)
{
    $stmt = $pdo->prepare(GET_USER_QUERY . "
        WHERE user_id = :user_id
    ");

    $stmt->bindParam("user_id", $userID);
    $stmt->execute();

    return $stmt->fetchObject("User");
}

function get_user_by_email(PDO $pdo, $email)
{
    $stmt = $pdo->prepare(GET_USER_QUERY . "
        WHERE email = :email
    ");

    $stmt->bindParam("email", $email);
    $stmt->execute();

    return $stmt->fetchObject("User");
}
