<?php

require 'includes/init.php';

require_once 'includes/components/UserProfileForm.php';
require_once 'includes/components/Navbar.php';
require_once 'includes/db/connect.php';
require_once 'includes/forms/UserProfileFP.php';
require_once 'includes/pages/RequestStatusPage.php';
require_once 'includes/pages/Page.php';
require_once 'includes/login.php';

$pdo = db_connect();

$user = get_user_by_token($pdo);
if ($user === null) {
    $errPage = new RequestStatusPage(HTTPStatus::STATUS_NOT_AUTHORIZED);
    $errPage->render();
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    UserProfileFP::process($pdo, $user);
    exit();
}

$edit_user = $user;
if (array_key_exists("user_id", $_GET)) {
    if (!$user->is_admin) {
        $errPage = new RequestStatusPage(HTTPStatus::STATUS_FORBIDDEN, $user);
        $errPage->render();
        exit();
    }

    $edit_user = get_user_by_id($pdo, $_GET["user_id"]);

    if (!$edit_user) {
        $errPage = new RequestStatusPage(
            HTTPStatus::STATUS_NOT_FOUND,
            $user,
            "No user exists with an ID of " . $_GET["user_id"] . "."
        );
        $errPage->render();
        exit();
    }
}

$title = "Profile Editor";
$page = new Page($title, [
    new Navbar($user, $title),
    new UserProfileForm(
        "profile-editor.php",
        UserProfileFP::OP_EDIT,
        $edit_user
    ),
]);

$page->render();
