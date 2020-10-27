<?php

require_once 'FormProcessor.php';
require_once __DIR__ . "/../db/article.php";
require_once __DIR__ . "/../types/ArticleCategory.php";
require_once __DIR__ . "/../types/User.php";

class ArticleCategoryEditorFP extends FormProcessor
{
    const OP_EDIT = "article-category-edit";

    protected static $operation_map = [
        self::OP_EDIT => [
            "handler" => "static::processArticleCategoryEdit",
            "req_fields" => [
                ["title"],
                ["icon"],
                ["color"],
                ["descr"],
            ],
            "opt_fields" => [
                ["category_id", FILTER_VALIDATE_INT],
            ],
            "req_admin" => true,
        ],
    ];

    protected static function processArticleCategoryEdit(PDO $pdo, User $user)
    {
        $category = new ArticleCategory();

        $category->article_category_id = null;
        if (isset($_POST["category_id"])) {
            $category->article_category_id = (int) $_POST["category_id"];
        }
        $category->title = $_POST["title"];
        $category->icon = $_POST["icon"];
        $category->color = $_POST["color"];
        $category->descr = $_POST["descr"];

        if (is_null($category->article_category_id)) {
            create_article_category($pdo, $category);
        } else {
            update_article_category($pdo, $category);
        }

        header("Location: kb-home.php");
    }
}
