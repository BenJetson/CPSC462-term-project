<?php

require 'includes/init.php';

require_once 'includes/components/ArticleViewer.php';
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

if (!isset($_GET["article_id"])) {
    $errorPage = new RequestStatusPage(HTTPStatus::STATUS_BAD_REQUEST);
    $errPage->render();
    exit();
}

$article = get_article_by_id($pdo, $_GET["article_id"]);
if (!$article) {
    $errPage = new RequestStatusPage(HTTPStatus::STATUS_NOT_FOUND);
    $errPage->render();
    exit();
}

$title = $article->title;
$page = new Page($title, [
    new Navbar($user, $title),
    new ArticleViewer($article),
]);

$page->render();
