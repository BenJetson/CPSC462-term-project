<?php

require 'includes/init.php';

require_once 'includes/components/UserProfileForm.php';
require_once 'includes/components/Navbar.php';
require_once 'includes/db/connect.php';
require_once 'includes/forms/UserProfileFP.php';
require_once 'includes/pages/Page.php';
require_once 'includes/login.php';

$pdo = db_connect();
AccessToken::destroyCookie();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = new User();
    UserProfileFP::process($pdo, $user);

    exit();
}

$title = "Register";
$page = new Page($title, [
    new Navbar(null, $title),
    new UserProfileForm("register.php", UserProfileFP::OP_REGISTER,  null),
]);

$page->render();
