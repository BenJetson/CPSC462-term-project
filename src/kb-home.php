<?php

require 'includes/init.php';

require_once 'includes/components/ArticleCategoryList.php';
require_once 'includes/components/Navbar.php';
require_once 'includes/db/article.php';
require_once 'includes/db/connect.php';
require_once 'includes/db/user.php';
require_once 'includes/pages/Page.php';
require_once 'includes/pages/RequestStatusPage.php';
require_once 'includes/types/Article.php';

$pdo = db_connect();

$user = get_user_by_token($pdo);
if ($user === null) {
    $errPage = new RequestStatusPage(HTTPStatus::STATUS_NOT_AUTHORIZED);
    $errPage->render();
    exit();
}

$categories = get_article_categories($pdo);

$title = "Knowledge Base";
$page = new Page($title, [
    new Navbar($user, $title),
    new ArticleCategoryList($categories),
]);

$page->render();
