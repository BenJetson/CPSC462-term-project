<?php

require 'includes/init.php';

require_once 'includes/components/ArticleCategoryList.php';
require_once 'includes/components/Navbar.php';
require_once 'includes/db/article.php';
require_once 'includes/db/connect.php';
require_once 'includes/db/user.php';
require_once 'includes/forms/ArticleCategoryListFP.php';
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
    ArticleCategoryListFP::process($pdo, $user);
    exit();
}

$categories = get_article_categories($pdo);

$title = "Knowledge Base";
$page = new Page($title, [
    new Navbar($user, $title),
    new ArticleCategoryList($user, $categories),
]);

$page->render();
