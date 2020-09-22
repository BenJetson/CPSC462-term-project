<?php

require_once 'includes/db-connect.php';
require_once 'includes/login.php';
require_once 'includes/page.php';
require_once 'includes/components/navbar.php';
require_once 'includes/components/noscript-warning.php';
require_once 'includes/components/login.php';


define("REMEMBER_ME_COOKIE", "remember-me-email");

$wasLoggedOut = AccessToken::destroyCookie();
$loginAttempted = isset($_POST["email"]) && isset($_POST["password"]);
$grantStatus = false;

if ($loginAttempted) {
    $grantStatus = password_grant(
        $db,
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
