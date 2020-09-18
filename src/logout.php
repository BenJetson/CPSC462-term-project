<?php

require_once 'includes/db-connect.php';
require_once 'includes/login.php';
require_once 'includes/page.php';
require_once 'includes/components/navbar.php';
require_once 'includes/components/logout-notice.php';

AccessToken::destroyCookie();

$title = "Logout";
$page = new Page($title, [
    new Navbar(null, $title),
    new LogoutNotice(),
]);

$page->render();
