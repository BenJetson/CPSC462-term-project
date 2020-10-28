<?php

require 'includes/init.php';

require_once 'includes/components/ArticleList.php';
require_once 'includes/components/Navbar.php';
require_once 'includes/db/article.php';
require_once 'includes/db/connect.php';
require_once 'includes/db/user.php';
require_once 'includes/forms/ArticleListFP.php';
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
    ArticleListFP::process($pdo, $user);
    exit();
}

$category = null;
$articles = null;
if (isset($_GET["category_id"])) {
    $category = get_article_category_by_id($pdo, $_GET["category_id"]);
    if ($category === false) {
        $errPage = new RequestStatusPage(
            HTTPStatus::STATUS_NOT_FOUND,
            $user,
            "No known category matching ID of '" . $_GET["category_id"] . "'."
        );
        $errPage->render();
        exit();
    }

    $articles = get_articles_in_category($pdo, $_GET["category_id"]);
} else {
    if (!$user->is_admin) {
        $errPage = new RequestStatusPage(HTTPStatus::STATUS_FORBIDDEN, $user);
        $errPage->render();
        exit();
    }

    $category = new ArticleCategory();
    $category->title = "All Articles";
    $category->descr = "All articles in the knowledge base, from any category.";
    $category->color = "black";
    $category->icon = "fa-list";

    $articles = get_all_articles($pdo);
}

$title = "KB: $category->title";
$page = new Page($title, [
    new Navbar($user, null),
    new ArticleList($user, $category, $articles),
]);

$page->render();
