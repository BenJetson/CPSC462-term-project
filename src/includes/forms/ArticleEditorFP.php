<?php

require_once 'FormProcessor.php';
require_once __DIR__ . "/../db/article.php";
require_once __DIR__ . "/../types/Article.php";
require_once __DIR__ . "/../types/User.php";

class ArticleEditorFP extends FormProcessor
{
    const OP_EDIT = "article-edit";

    protected static $operation_map = [
        self::OP_EDIT => [
            "handler" => "static::processArticleEdit",
            "req_fields" => [
                ["category_id", FILTER_VALIDATE_INT],
                ["title"],
                ["body"],
            ],
            "opt_fields" => [
                ["article_id", FILTER_VALIDATE_INT],
            ],
            "req_admin" => true,
        ]
    ];

    private static function redirectToArticleList($category_id)
    {
        header("Location: article-list.php?category_id=$category_id");
    }

    protected static function processArticleEdit(PDO $pdo, User $user)
    {
        $article = new Article();

        $article->article_id = null;
        if (isset($_POST["article_id"])) {
            $article->article_id = (int) $_POST["article_id"];
        }
        $article->category_id = (int) $_POST["category_id"];
        $article->author_id = $user->user_id;
        $article->title = $_POST["title"];
        $article->body = $_POST["body"];

        if (is_null($article->article_id)) {
            create_article($pdo, $article);
        } else {
            update_article($pdo, $article);
        }

        self::redirectToArticleList($article->category_id);
    }
}
