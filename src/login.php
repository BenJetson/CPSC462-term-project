<?php

require_once 'includes/components/Login.php';
require_once 'includes/components/Navbar.php';
require_once 'includes/components/NoScriptWarning.php';
require_once 'includes/db/connect.php';
require_once 'includes/db/user.php';
require_once 'includes/pages/Page.php';
require_once 'includes/login.php';

// TODO make sure this is everywhere.
ini_set('display_errors', 0);

define("REMEMBER_ME_COOKIE", "remember-me-email");

$pdo = db_connect();

$wasLoggedOut = AccessToken::destroyCookie();
$loginAttempted = isset($_POST["email"]) && isset($_POST["password"]);
$grantStatus = false;

if ($loginAttempted) {
    $grantStatus = password_grant(
        $pdo,
        $_POST["email"],
        $_POST["password"]
    );

    $rememberMeEmail = "";
    if ($grantStatus->granted && isset($_POST["remember-me"]) && $_POST["remember-me"] === "on") {
        $rememberMeEmail = $_POST["email"];
    }

    setcookie(REMEMBER_ME_COOKIE, $rememberMeEmail, 0, "", "", false, true);
    $_COOKIE[REMEMBER_ME_COOKIE] = $rememberMeEmail; // FIXME

    if ($grantStatus->granted) {
        header('Location: whoami.php');
        exit;
    }
}

$isRemembered = isset($_COOKIE[REMEMBER_ME_COOKIE])
    && $_COOKIE[REMEMBER_ME_COOKIE] !== "";
$rememberedEmail = $isRemembered ? $_COOKIE[REMEMBER_ME_COOKIE] : null;

$page = new Page("Login", [
    new Navbar(null, null),
    new NoScriptWarning(),
    new Login(
        $wasLoggedOut,
        $loginAttempted,
        $grantStatus,
        $isRemembered,
        $rememberedEmail
    ),
]);

$page->render();
