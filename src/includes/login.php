<?php

require_once 'autoload.php';
require_once 'secrets.php';
require_once 'crypto.php';
require_once 'db/user.php';

class AccessToken implements \JsonSerializable
{
    public $user_id;
    public $issued_at;
    public $ip;

    const COOKIE_NAME = "helpdesk_session_token";
    const VALID_PERIOD = "PT1H";

    public function __construct($user_id)
    {
        $this->user_id = $user_id;
        $this->issued_at = new DateTime("now");
        $this->ip = $_SERVER["REMOTE_ADDR"];
    }

    public function hasExpired()
    {
        $validFor = new DateInterval(self::VALID_PERIOD);
        $expiryTime = $this->issued_at->add($validFor);
        $now = new DateTime("now");

        return $expiryTime < $now;
    }

    public function isValidForCurrentIP()
    {
        return $this->ip === $_SERVER["REMOTE_ADDR"];
    }

    public static function newFromJSON($json_data)
    {
        $obj = json_decode($json_data);

        $token = new AccessToken($obj->user_id);
        $token->issued_at = new DateTime($obj->issued_at); // FIXME check this

        return $token;
    }

    public function jsonSerialize()
    {
        return array(
            "user_id"   => $this->user_id,
            "issued_at" => $this->issued_at->format(DateTime::RFC3339),
            "ip"        => $this->ip,
        );
    }

    public function setCookie()
    {
        // Encode the cookie into JSON and encrypt it using the secret.
        $token_plain = json_encode($this);
        $key = base64_decode($_SERVER["LOGIN_SECRET"]);
        $token_cipher = safe_encrypt($token_plain, $key);

        // The option values needed to set the cookie.
        $expires = time() + (60 * 60 * 24 * 365);
        $path = "";
        $domain = "";
        $secure = isset($_SERVER["HTTPS"]) ? $_SERVER["HTTPS"] : false;
        $httpOnly = true;

        // Send the cookie to the client so it will store the cookie.
        // This data will be available on the next page load.
        setcookie(
            self::COOKIE_NAME,
            $token_cipher,
            $expires,
            $path,
            $domain,
            $secure,
            $httpOnly
        );

        // Set the cookie manually on the $_COOKIE array so that scripts invoked
        // by the current page load can access the token.
        $_COOKIE[self::COOKIE_NAME] = $token_cipher;
    }

    public static function fetchFromCookie()
    {
        if (!isset($_COOKIE[self::COOKIE_NAME])) {
            return null;
        }

        $token_cipher = $_COOKIE[self::COOKIE_NAME];
        $key = base64_decode($_SERVER["LOGIN_SECRET"]);
        $token_plain = safe_decrypt($token_cipher, $key);

        $obj = json_decode($token_plain);
        if (
            !property_exists($obj, "user_id")
            || !property_exists($obj, "issued_at")
            || !property_exists($obj, "ip")
        ) {
            return null;
        }

        $token = new AccessToken((int) $obj->user_id);
        $token->issued_at = new DateTime($obj->issued_at);
        $token->ip = (string) $obj->ip;

        if ($token->hasExpired()) {
            self::destroyCookie();
            return null;
        }

        return $token;
    }

    public static function destroyCookie()
    {
        if (isset($_COOKIE[self::COOKIE_NAME])) {
            // Unset cookie in PHP.
            unset($_COOKIE[self::COOKIE_NAME]);

            // Send a null cookie that expires yesterday to the browser so that
            // the browser will delete its local copy of the cookie.
            setcookie(self::COOKIE_NAME, null, time() - (60 * 60 * 24));

            return true;
        }
        return false;
    }
}

class PasswordGrantStatus
{
    public $granted;
    public $reason;

    const REASON_GENERIC = "The email address or password provided was incorrect.";
    const REASON_LOCKOUT = "This account is locked due to multiple failed login attempts. Try again in a few minutes.";
    const REASON_DISABLED = "This accout is currently disabled. Contact the administrator for assistance.";

    public function __construct($granted, $reason)
    {
        $this->granted = $granted;
        $this->reason = $reason;
    }
}

define("LOCKOUT_ATTEMPTS", 5);
define("LOCKOUT_DURATION", "PT5M");

function password_grant(PDO $pdo, $email, $password)
{
    // Always clear the previous access token.
    AccessToken::destroyCookie();

    // User is not yet authenticated, so no information may be revealed about
    // whether the account exists, the lockout state, or anything.
    //
    // Rejection at this point yields "username or password incorrect" message.

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 1) {
        return new PasswordGrantStatus(
            false,
            PasswordGrantStatus::REASON_GENERIC
        );
    }

    $user = get_user_by_email($pdo, $email);
    if (!$user) {
        return new PasswordGrantStatus(
            false,
            PasswordGrantStatus::REASON_GENERIC
        );
    }

    $matches = password_verify($password, $user->pass_hash);

    if (!$matches) {
        update_user_bump_lockout($pdo, $user->user_id);

        return new PasswordGrantStatus(
            false,
            PasswordGrantStatus::REASON_GENERIC
        );
    }

    // User has been authenticated. We will now perform some sanity checks to
    // ensure that the grant may pass.
    //
    // Rejection at this point can, if desired, reveal a reason for failure
    // where appropriate to do so.

    // Check for disabled account.
    if ($user->is_disabled) {
        return new PasswordGrantStatus(
            false,
            PasswordGrantStatus::REASON_DISABLED
        );
    }

    // Check for lockout.
    if ($user->pass_attempts >= LOCKOUT_ATTEMPTS) {
        $lockoutDuration = new DateInterval(LOCKOUT_DURATION);
        $lockoutEnd = $user->pass_locked_at->add($lockoutDuration);
        $now = new DateTime("now");

        if ($now < $lockoutEnd) {
            return new PasswordGrantStatus(
                false,
                PasswordGrantStatus::REASON_LOCKOUT
            );
        }
    }

    // Password accepted. Grant an access token.
    $token = new AccessToken($user->user_id);
    $token->setCookie();

    // Clear lockout status if necessary.
    if ($user->pass_attempts > 0) {
        update_user_clear_lockout($pdo, $user->user_id);
    }

    return new PasswordGrantStatus(true, null);
}
