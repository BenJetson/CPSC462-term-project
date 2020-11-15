<?php

require 'includes/init.php';

require_once 'includes/components/AdminRestoreForm.php';
require_once 'includes/components/Navbar.php';
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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // TODO call to a formprocessor

    $page = new RequestStatusPage(
        HTTPStatus::STATUS_OK,
        null,
        "The restore operation is now processing. Please wait."
    );
    $page->render();

    exit();
}

$title = "Restore";
$page = new Page($title, [
    new Navbar($user, $title),
    new AdminRestoreForm(),
]);

$page->render();
