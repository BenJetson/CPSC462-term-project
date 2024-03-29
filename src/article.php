<?php

require 'includes/init.php';

require_once 'includes/components/ArticleViewer.php';
require_once 'includes/components/Navbar.php';
require_once 'includes/db/article.php';
require_once 'includes/db/connect.php';
require_once 'includes/db/user.php';
require_once 'includes/forms/ArticleViewerFP.php';
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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    ArticleViewerFP::process($pdo, $user);
    exit();
}

if (!isset($_GET["article_id"])) {
    $errPage = new RequestStatusPage(HTTPStatus::STATUS_BAD_REQUEST, $user);
    $errPage->render();
    exit();
}

$article = get_article_by_id($pdo, $_GET["article_id"]);
if (!$article) {
    $errPage = new RequestStatusPage(HTTPStatus::STATUS_NOT_FOUND, $user);
    $errPage->render();
    exit();
}

$comments = get_comments_for_article($pdo, $_GET["article_id"]);

$title = $article->title;
$page = new Page($title, [
    new Navbar($user, $title),
    new ArticleViewer($article, $comments),
]);

$page->render();
