<?php

require 'includes/init.php';

require_once 'includes/components/ChangePasswordForm.php';
require_once 'includes/components/Navbar.php';
require_once 'includes/db/connect.php';
require_once 'includes/db/user.php';
require_once 'includes/forms/ChangePasswordFP.php';
require_once 'includes/pages/Page.php';
require_once 'includes/pages/RequestStatusPage.php';

$pdo = db_connect();

$user = get_user_by_token($pdo);
if ($user === null) {
    $errPage = new RequestStatusPage(HTTPStatus::STATUS_NOT_AUTHORIZED);
    $errPage->render();
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    ChangePasswordFP::process($pdo, $user);
    exit();
}

$title = "Change Password";
$page = new Page($title, [
    new Navbar($user, $title),
    new ChangePasswordForm(),
]);

$page->render();
