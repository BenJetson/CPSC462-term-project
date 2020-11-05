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

/**
 * get_all_users fetches a list of all user accounts registered with the system.
 *
 * @param PDO $pdo the database connection to use.
 *
 * @return User[]
 *
 * @throws PDOException when the database encounters an error.
 */
function get_all_users(PDO $pdo)
{
    $stmt = $pdo->prepare(GET_USER_QUERY);
    $stmt->execute();

    $users = $stmt->fetchAll(PDO::FETCH_CLASS, 'User');

    if (!$users || count($users) === 0) {
        return array();
    }
    return $users;
}

/**
 * get_active_admin_users fetches a list of all users with administrative
 * privileges that do not have their accounts disabled.
 *
 * @param PDO $pdo the database connection to use.
 *
 * @return User[]
 *
 * @throws PDOException when the database encounters an error.
 */
function get_active_admin_users(PDO $pdo)
{
    $stmt = $pdo->prepare(GET_USER_QUERY . "
        WHERE is_admin = TRUE AND is_disabled = FALSE
    ");

    $stmt->execute();

    $users = $stmt->fetchAll(PDO::FETCH_CLASS, 'User');

    if (!$users || count($users) === 0) {
        return array();
    }
    return $users;
}

/**
 * get_user_by_id fetches the user object associated with the given ID number.
 *
 * @param PDO $pdo the database connection to use.
 * @param int $userID the ID number of the user to be fetched.
 *
 * @return ?User
 *
 * @throws PDOException when the database encounters an error.
 */
function get_user_by_id(PDO $pdo, $userID)
{
    $stmt = $pdo->prepare(GET_USER_QUERY . "
        WHERE user_id = :user_id
    ");

    $stmt->bindParam("user_id", $userID);
    $stmt->execute();

    return $stmt->fetchObject("User");
}

/**
 * get_user_by_email will fetch the user object associated with the given email
 * address. Useful for login when only the email address is known.
 *
 * @param PDO $pdo the database connection to use.
 * @param string $email the email address of the user to fetch.
 *
 * @return ?User
 *
 * @throws PDOException when the database encounters an error.
 */
function get_user_by_email(PDO $pdo, $email)
{
    $stmt = $pdo->prepare(GET_USER_QUERY . "
        WHERE email = :email
    ");

    $stmt->bindParam("email", $email);
    $stmt->execute();

    return $stmt->fetchObject("User");
}

/**
 * get_user_by_token will fetch the user object associated with the presently
 * logged in user, or null if no user is logged in.
 *
 * @param PDO $pdo the database connection to use.
 *
 * @return ?User
 *
 * @throws PDOException when the database encounters an error.
 */
function get_user_by_token(PDO $pdo)
{
    $token = AccessToken::fetchFromCookie();
    if ($token === null) {
        return null;
    }

    return get_user_by_id($pdo, $token->user_id);
}

/**
 * update_user_bump_lockout will increment the number of failed login attempts
 * for a user. Guaranteed to be atomic because this happens directly on the
 * database server.
 *
 * @param PDO $pdo the database connection to use.
 * @param int $user_id the ID number of the user to update.
 *
 * @return void
 *
 * @throws PDOException when the database encounters an error.
 */
function update_user_bump_lockout(PDO $pdo, $user_id)
{
    $stmt = $pdo->prepare("
        UPDATE user
        SET
            pass_attempts = pass_attempts + 1,
            pass_locked_at = NOW()
        WHERE user_id = :user_id
    ");

    $stmt->bindParam("user_id", $user_id);
    $stmt->execute();
}

/**
 * update_user_clear_lockout will clear the password lockout for a user by
 * setting the number of failed attempts to zero.
 *
 * @param PDO $pdo the database connection to use.
 * @param int $user_id the ID number of the user to update.
 *
 * @return void
 *
 * @throws PDOException when the database encounters an error.
 */
function update_user_clear_lockout(PDO $pdo, $user_id)
{
    $stmt = $pdo->prepare("
        UPDATE user
        SET pass_attempts = 0
        WHERE user_id = :user_id
    ");

    $stmt->bindParam("user_id", $user_id);
    $stmt->execute();
}

/**
 * create_user will create a new user account for this application. The created
 * user shall have unconfirmed email status and no administrative privileges.
 *
 * @param PDO $pdo the database connection to use.
 * @param User $user the profile details of the new user account, including the
 *      hashed password.
 *
 * @return void
 *
 * @throws PDOException when the database encounters an error.
 */
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
    $stmt->bindParam(":dob", $user->dob->format("Y-m-d"));
    $stmt->bindParam(":telephone", $user->telephone);
    $stmt->bindParam(":address_line_1", $user->address_line_1);
    $stmt->bindParam(":address_line_2", $user->address_line_2);
    $stmt->bindParam(":address_city", $user->address_city);
    $stmt->bindParam(":address_state", $user->address_state);
    $stmt->bindParam(":address_zip", $user->address_zip);
    $stmt->bindParam(":pass_hash", $user->pass_hash);

    // TODO check for SQLState error code 23000 for unique violation and inform
    // the user that the email is already registered and they should login.
    //
    // https://dev.mysql.com/doc/connector-j/5.1/en/connector-j-reference-error-sqlstates.html
    $stmt->execute();
}

/**
 * update_user_profile will update an existing user's profile in the database
 * using the data enclosed within the given User object. Fields are coalesced
 * with the current stored values, so null fields will result in no change.
 *
 * @param PDO $pdo the database connection to use.
 * @param User $user the user profile information to update.
 *
 * @return void
 *
 * @throws PDOException when the database encounters a problem.
 */
function update_user_profile(PDO $pdo, User $user)
{
    $stmt = $pdo->prepare("
        UPDATE user
        SET
            email = COALESCE(:email, email),
            first_name = COALESCE(:first_name, first_name),
            last_name = COALESCE(:last_name, last_name),
            dob = COALESCE(:dob, dob),
            telephone = COALESCE(:telephone, telephone),

            address_line_1 = COALESCE(:address_line_1, address_line_1),
            address_line_2 = COALESCE(:address_line_2, address_line_2),
            address_city = COALESCE(:address_city, address_city),
            address_state = COALESCE(:address_state, address_state),
            address_zip = COALESCE(:address_zip, address_zip)
        WHERE user_id = :user_id
    ");

    // Falsy fields will be coalesced to NULL, which will result in the field
    // not being updated in the database (existing value prevails).
    $stmt->bindParam(":email", $user->email ?: null);
    $stmt->bindParam(":first_name", $user->first_name ?: null);
    $stmt->bindParam(":last_name", $user->last_name ?: null);
    $stmt->bindParam(":dob", isset($user->dob)
        ? $user->dob->format("Y-m-d")
        : null);
    $stmt->bindParam(":telephone", $user->telephone ?: null);

    $stmt->bindParam(":address_line_1", $user->address_line_1 ?: null);
    $stmt->bindParam(":address_line_2", $user->address_line_2);
    $stmt->bindParam(":address_city", $user->address_city ?: null);
    $stmt->bindParam(":address_state", $user->address_state ?: null);
    $stmt->bindParam(":address_zip", $user->address_zip ?: null);

    $stmt->bindParam(":user_id", $user->user_id);

    // TODO check for SQLState error code 23000 for unique violation and inform
    // the user that the email is already registered and cannot be used.
    //
    // https://dev.mysql.com/doc/connector-j/5.1/en/connector-j-reference-error-sqlstates.html

    $stmt->execute();
}

/**
 * change_user_password will update the password for a user.
 *
 * @param PDO $pdo the database connection to use.
 * @param int $user_id the ID number of the user to update.
 * @param string $new_pass_hash output of password_hash for the new password.
 *
 * @return void
 *
 * @throws PDOException when the database encounters an error.
 */
function change_user_password(PDO $pdo, $user_id, $new_pass_hash)
{
    // Trigger will update the pass_updated_at automatically.

    $stmt = $pdo->prepare("
        UPDATE user
        SET
            pass_hash = :pass_hash,
            pass_attempts = 0
        WHERE user_id = :user_id
    ");

    $stmt->bindParam(":pass_hash", $new_pass_hash);
    $stmt->bindParam(":user_id", $user_id);

    $stmt->execute();
}

/**
 * set_user_management_attributes can be used to set a user's disabled/enabled
 * status and their administrative privilege level.
 *
 * @param PDO $pdo the database connection to use.
 * @param int $user_id the ID number of the user to update.
 * @param bool $is_disabled whether or not the user's account is disabled.
 * @param bool $is_admin whether or not the user should have administrative
 *      privileges to the application.
 *
 * @return void
 *
 * @throws Exception when the root user (ID 1) is given.
 * @throws PDOException when the database encounters an error.
 */
function set_user_management_attributes(
    PDO $pdo,
    $user_id,
    $is_disabled,
    $is_admin
) {
    if ($user_id === 1) {
        throw new Exception("cannot alter admin status of system owner");
    }

    $stmt = $pdo->prepare("
        UPDATE user
        SET
            is_disabled = :is_disabled,
            is_admin = :is_admin
        WHERE user_id = :user_id
    ");

    $stmt->bindParam(":is_disabled", boolval($is_disabled));
    $stmt->bindParam(":is_admin", boolval($is_admin));
    $stmt->bindParam(":user_id", $user_id);

    $stmt->execute();
}
