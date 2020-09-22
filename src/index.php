<?php

require_once 'includes/db-connect.php';
require_once 'includes/db/user.php';
require_once 'includes/page.php';
require_once 'includes/error-page.php';
require_once 'includes/components/navbar.php';

$user = get_user_by_token($db);
if ($user === null) {
    $errPage = new RequestStatusPage(HTTPStatus::STATUS_NOT_AUTHORIZED);
    $errPage->render();
    exit();
}

// TODO make a real page for this!
$page = new RequestStatusPage(
    HTTPStatus::STATUS_OK,
    $user,
    "Welcome to the helpdesk! This homepage is still a work in progress."
);

$page->render();
