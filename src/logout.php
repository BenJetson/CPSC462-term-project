<?php

require_once 'includes/components/Navbar.php';
require_once 'includes/components/LogoutNotice.php';
require_once 'includes/login.php';
require_once 'includes/page.php';

AccessToken::destroyCookie();

$title = "Logout";
$page = new Page($title, [
    new Navbar(null, $title),
    new LogoutNotice(),
]);

$page->render();
