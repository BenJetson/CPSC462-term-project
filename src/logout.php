<?php

require_once 'includes/components/Navbar.php';
require_once 'includes/components/LogoutNotice.php';
require_once 'includes/pages/Page.php';
require_once 'includes/login.php';

AccessToken::destroyCookie();

$title = "Logout";
$page = new Page($title, [
    new Navbar(null, $title),
    new LogoutNotice(),
]);

$page->render();
