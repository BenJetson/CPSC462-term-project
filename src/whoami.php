<?php

require_once 'includes/db-connect.php';
require_once 'includes/login.php';
require_once 'includes/page.php';
require_once 'includes/components/navbar.php';
require_once 'includes/components/auth-status.php';


$token = AccessToken::fetchFromCookie();

$user = null;
if ($token !== null) {
    $user = get_user_by_id($db, $token->user_id);
}

$title = "Authentication Status";
$page = new Page($title, [
    new Navbar($user, $title),
    new AuthStatus($token, $user),
]);

$page->render();
