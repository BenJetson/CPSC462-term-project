<?php

require 'includes/init.php';

require_once 'includes/db/connect.php';
require_once 'includes/db/user.php';
require_once 'includes/pages/RequestStatusPage.php';

$pdo = db_connect();

$user = get_user_by_token($pdo);

$page = new RequestStatusPage(HTTPStatus::STATUS_NOT_FOUND, $user);
$page->render();
