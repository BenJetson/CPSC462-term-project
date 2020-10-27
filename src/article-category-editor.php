<?php

require 'includes/init.php';

require_once 'includes/components/ArticleCategoryEditor.php';
require_once 'includes/components/Navbar.php';
require_once 'includes/db/article.php';
require_once 'includes/db/connect.php';
require_once 'includes/db/user.php';
require_once 'includes/forms/ArticleCategoryEditorFP.php';
require_once 'includes/pages/Page.php';
require_once 'includes/pages/RequestStatusPage.php';
require_once 'includes/types/ArticleCategory.php';

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
    ArticleCategoryEditorFP::process($pdo, $user);
    exit();
}

$is_edit_mode = isset($_GET["category_id"]);

$category = new ArticleCategory();
if ($is_edit_mode) {
    $category = get_article_category_by_id($pdo, $_GET["category_id"]);

    if (!$category) {
        $errPage = new RequestStatusPage(HTTPStatus::STATUS_NOT_FOUND);
        $errPage->render();
        exit();
    }
}

$title = $is_edit_mode ? "Article Category Editor" : "New Article Category";
$page = new Page($title, [
    new Navbar($user, $title),
    new ArticleCategoryEditor($category),
]);

$page->render();
