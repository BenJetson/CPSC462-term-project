<?php

require 'includes/init.php';

require_once 'includes/components/Navbar.php';
require_once 'includes/components/Homepage.php';
require_once 'includes/db/connect.php';
require_once 'includes/db/user.php';
require_once 'includes/pages/Page.php';

$pdo = db_connect();

$user = get_user_by_token($pdo);
if ($user === null) {
    header("Location: login.php");
    exit();
}

// TODO make a real page for this!
$page = new Page("Home", [
    new Navbar($user, null),
    new Homepage($user),
]);

$page->render();
