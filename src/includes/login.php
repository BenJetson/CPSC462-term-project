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
            "ip"        => $this->issued_at,
        );
    }

    public function setCookie()
    {
        $token_plain = json_encode($this);
        $key = base64_decode($_SERVER["LOGIN_SECRET"]);
        $token_cipher = safe_encrypt($token_plain, $key);

        // setcookie(self::COOKIE_NAME, $token_cipher, array(
        //     "httponly" => true,
        //     "secure"   => $_SERVER["HTTPS"],
        //     "domain"   => "localhost", // FIXME
        //     "path"     => "~bfgodfr/4620/project", // FIXME
        //     "expires"  => time() + (60 * 60 * 24 * 365), // FIXME
        // ));
        setcookie(
            self::COOKIE_NAME, //key
            $token_cipher, // value
            time() + (60 * 60 * 24 * 365), // FIXME expires
            "~bfgodfr/4620/project", // FIXME path
            "localhost", // FIXME domain
            $_SERVER["HTTPS"], //https
            true // HTTPONly
        );
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
}

function password_grant(PDO $pdo, $email, $password)
{
    $user = get_user_by_email($pdo, $email);

    if (!$user) {
        return false;
    }

    if ($user->is_disabled) {
        return false;
    }

    // TODO: Check to see if attempts exceeded.

    $matches = password_verify($password, $user->pass_hash);

    if (!$matches) {
        // TODO: Bump attempts and lockout time.
        return false;
    }

    $token = new AccessToken($user->user_id);
    $token->setCookie();

    // Password accepted. Grant an access token.
    return true;
}
