<?php

require 'includes/init.php';

require_once 'includes/components/Navbar.php';
require_once 'includes/components/AdminHomepage.php';
require_once 'includes/db/connect.php';
require_once 'includes/db/user.php';
require_once 'includes/pages/Page.php';
require_once 'includes/pages/RequestStatusPage.php';


$pdo = db_connect();

$user = get_user_by_token($pdo);
if ($user === null) {
    $errPage = new RequestStatusPage(HTTPStatus::STATUS_NOT_AUTHORIZED);
    $errPage->render();
    exit();
} else if (!$user->is_admin) {
    $errPage = new RequestStatusPage(HTTPStatus::STATUS_FORBIDDEN, $user);
    $errPage->render();
    exit();
}

$title = "Administration";
$page = new Page($title, [
    new Navbar($user, $title),
    new AdminHomepage(),
]);

$page->render();
