<?php

require 'includes/init.php';

require_once 'includes/components/Navbar.php';
require_once 'includes/db/help-ticket.php';
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
}

$errPage = new RequestStatusPage(HTTPStatus::STATUS_NOT_IMPLEMENTED, $user);
$errPage->render();
