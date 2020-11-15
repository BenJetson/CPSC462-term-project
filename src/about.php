<?php

require 'includes/init.php';

require_once 'includes/components/AboutInfo.php';
require_once 'includes/components/Navbar.php';
require_once 'includes/db/connect.php';
require_once 'includes/db/user.php';
require_once 'includes/pages/Page.php';

$pdo = db_connect();

$user = get_user_by_token($pdo);

$title = "About";
$page = new Page($title, [
    new Navbar($user, $title),
    new AboutInfo(),
]);

$page->render();
