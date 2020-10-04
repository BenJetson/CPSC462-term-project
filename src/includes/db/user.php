<?php

require_once __DIR__ . "/../login.php";
require_once __DIR__ . "/../types/User.php";

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

function get_all_users(PDO $pdo)
{
    $stmt = $pdo->prepare(GET_USER_QUERY);
    $stmt->execute();

    $users = $stmt->fetchAll(PDO::FETCH_CLASS, 'User');

    if (count($users) === 0) {
        return array();
    }
    return $users;
}

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

function get_user_by_token(PDO $pdo)
{
    $token = AccessToken::fetchFromCookie();
    if ($token === null) {
        return null;
    }

    return get_user_by_id($pdo, $token->user_id);
}

function update_user_bump_lockout(PDO $pdo, $userID)
{
    $stmt = $pdo->prepare("
        UPDATE user
        SET
            pass_attempts = pass_attempts + 1,
            pass_locked_at = NOW()
        WHERE user_id = :user_id
    ");

    $stmt->bindParam("user_id", $userID);
    $stmt->execute();
}

function update_user_clear_lockout(PDO $pdo, $userID)
{
    $stmt = $pdo->prepare("
        UPDATE user
        SET pass_attempts = 0
        WHERE user_id = :user_id
    ");

    $stmt->bindParam("user_id", $userID);
    $stmt->execute();
}

function create_user(PDO $pdo, User $user)
{
    $stmt = $pdo->prepare("
        INSERT INTO user (
            is_admin,

            email,
            first_name,
            last_name,
            dob,
            telephone,

            address_line_1,
            address_line_2,
            address_city,
            address_state,
            address_zip,

            pass_hash
        ) VALUES (
            :is_admin,

            :email,
            :first_name,
            :last_name,
            :dob,
            :telephone,

            :address_line_1,
            :address_line_2,
            :address_city,
            :address_state,
            :address_zip,

            :pass_hash
        )
    ");

    $stmt->bindParam(":is_admin", $user->is_admin);
    $stmt->bindParam(":email", $user->email);
    $stmt->bindParam(":first_name", $user->first_name);
    $stmt->bindParam(":last_name", $user->last_name);
    $stmt->bindParam(":dob", $user->dob);
    $stmt->bindParam(":telephone", $user->telephone);
    $stmt->bindParam(":address_line_1", $user->address_line_1);
    $stmt->bindParam(":address_line_2", $user->address_line_2);
    $stmt->bindParam(":address_city", $user->address_city);
    $stmt->bindParam(":address_state", $user->address_state);
    $stmt->bindParam(":address_zip", $user->address_zip);
    $stmt->bindParam(":pass_hash", $user->pass_hash);

    $stmt->execute();
}

function update_user_profile(PDO $pdo, User $user)
{
    $stmt = $pdo->prepare("
        UPDATE user
        SET
            email = :email,
            first_name = :first_name,
            last_name = :last_name,
            dob = :dob,
            telephone = :telephone,

            address_line_1 = :address_line_1,
            address_line_2 = :address_line_2,
            address_city = :address_city,
            address_state = :address_state,
            address_zip = :address_zip
        WHERE user_id = :user_id
    ");

    $stmt->bindParam(":email", $user->email);
    $stmt->bindParam(":first_name", $user->first_name);
    $stmt->bindParam(":last_name", $user->last_name);
    $stmt->bindParam(":dob", $user->dob);
    $stmt->bindParam(":telephone", $user->telephone);

    $stmt->bindParam(":address_line_1", $user->address_line_1);
    $stmt->bindParam(":address_line_2", $user->address_line_2);
    $stmt->bindParam(":address_city", $user->address_city);
    $stmt->bindParam(":address_state", $user->address_state);
    $stmt->bindParam(":address_zip", $user->address_zip);

    $stmt->bindParam(":user_id", $user->user_id);

    $stmt->execute();
}

function change_user_password(PDO $pdo, $user_id, $new_pass_hash)
{
    // Trigger will update the pass_updated_at automatically.

    $stmt = $pdo->prepare("
        UPDATE USER
        SET pass_hash = :pass_hash
        WHERE user_id = :user_id
    ");

    $stmt->bindParam(":pass_hash", $new_pass_hash);
    $stmt->bindParam(":user_id", $user_id);

    $stmt->execute();
}

function set_user_admin(PDO $pdo, $user_id, $is_admin)
{
    if ($user_id === 1) {
        throw new Exception("cannot alter admin status of system owner");
    }

    $stmt = $pdo->prepare("
        UPDATE USER
        SET is_admin = :is_admin
        WHERE user_id = :user_id
    ");

    $stmt->bindParam(":is_admin", boolval($is_admin));
    $stmt->bindParam(":user_id", $user_id);

    $stmt->execute();
}

function set_user_disabled(PDO $pdo, $user_id, $is_disabled)
{
    if ($user_id === 1) {
        throw new Exception("cannot disable account of system owner");
    }

    $stmt = $pdo->prepare("
        UPDATE USER
        SET is_disabled = :is_disabled
        WHERE user_id = :user_id
    ");

    $stmt->bindParam(":is_disabled", boolval($is_disabled));
    $stmt->bindParam(":user_id", $user_id);

    $stmt->execute();
}
