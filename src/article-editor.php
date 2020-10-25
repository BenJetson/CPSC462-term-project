<?php

require 'includes/init.php';

require_once 'includes/components/ArticleEditor.php';
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
} else if (!$user->is_admin) {
    $errPage = new RequestStatusPage(HTTPStatus::STATUS_FORBIDDEN);
    $errPage->render();
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    echo "Got a POST, no processing logic for the data though. :-/"; // FIXME
    // TODO redirect back to article list
    exit();
}

$article = new Article();
if (isset($_GET["article_id"])) {
    $article = get_article_by_id($pdo, $_GET["article_id"]);

    if (!$article) {
        $errPage = new RequestStatusPage(HTTPStatus::STATUS_NOT_FOUND);
        $errPage->render();
        exit();
    }
}

$title = "Article Editor";
$page = new Page($title, [
    new Navbar($user, $title),
    new ArticleEditor($article),
]);

$page->render();