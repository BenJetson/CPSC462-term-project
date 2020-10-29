<?php

require_once 'FormProcessor.php';
require_once __DIR__ . "/../db/article.php";
require_once __DIR__ . "/../types/User.php";

class ArticleCategoryListFP extends FormProcessor
{
    const OP_DELETE = "delete-category";

    protected static $operation_map = [
        self::OP_DELETE => [
            "handler" => "static::processDeleteCategory",
            "req_fields" => [
                ["category_id", FILTER_VALIDATE_INT],
            ],
            "opt_fields" => [],
            "req_admin" => true,
        ],
    ];

    protected static function processDeleteCategory(PDO $pdo, User $user)
    {
        delete_article_category($pdo, $_POST["category_id"]);
        header("Location: article-index.php");
    }
}
