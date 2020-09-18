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
    const VALID_PERIOD = "P1H";

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

        return $now < $expiryTime;
        // FIXME check this logic
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

function password_grant(PDO $pdo, $email, $password)
{
    $user = get_user_by_email($pdo, $email);

    if (!$user) {
        AccessToken::destroyCookie();
        return false;
    }

    if ($user->is_disabled) {
        AccessToken::destroyCookie();
        return false;
    }

    // TODO: Check to see if attempts exceeded.

    $matches = password_verify($password, $user->pass_hash);

    if (!$matches) {
        // TODO: Bump attempts and lockout time.

        AccessToken::destroyCookie();
        return false;
    }

    $token = new AccessToken($user->user_id);
    $token->setCookie();

    // Password accepted. Grant an access token.
    return true;
}
