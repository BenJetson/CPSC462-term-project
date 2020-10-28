<?php

require_once 'FormProcessor.php';
require_once __DIR__ . "/../db/article.php";
require_once __DIR__ . "/../types/User.php";

class ArticleListFP extends FormProcessor
{
    const OP_DELETE = "delete-article";

    protected static $operation_map = [
        self::OP_DELETE => [
            "handler" => "static::processDeleteArticle",
            "req_fields" => [
                ["article_id", FILTER_VALIDATE_INT],
            ],
            "opt_fields" => [],
            "req_admin" => true,
        ],
    ];

    protected static function processDeleteArticle(PDO $pdo, User $user)
    {
        $article = get_article_by_id($pdo, $_POST["article_id"]);
        delete_article($pdo, $_POST["article_id"]);
        header("Location: article-list.php?category_id=" .
            $article->category_id);
    }
}
