<?php

require 'includes/init.php';

require_once 'includes/components/Navbar.php';
require_once 'includes/components/Homepage.php';
require_once 'includes/db/connect.php';
require_once 'includes/db/user.php';
require_once 'includes/pages/RequestStatusPage.php';
require_once 'includes/pages/Page.php';

$pdo = db_connect();

$user = get_user_by_token($pdo);
if ($user === null) {
    $errPage = new RequestStatusPage(HTTPStatus::STATUS_NOT_AUTHORIZED);
    $errPage->render();
    exit();
}

// TODO make a real page for this!
$page = new Page("Home", [
    new Navbar($user, null),
    new Homepage($user),
]);

$page->render();
