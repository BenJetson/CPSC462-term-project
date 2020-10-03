<?php

require_once 'includes/components/AuthStatus.php';
require_once 'includes/components/Navbar.php';
require_once 'includes/db/connect.php';
require_once 'includes/db/user.php';
require_once 'includes/pages/Page.php';
require_once 'includes/login.php';


$token = AccessToken::fetchFromCookie();

$pdo = db_connect();

$user = null;
if ($token !== null) {
    $user = get_user_by_id($pdo, $token->user_id);
}

$title = "Authentication Status";
$page = new Page($title, [
    new Navbar($user, $title),
    new AuthStatus($token, $user),
]);

$page->render();
