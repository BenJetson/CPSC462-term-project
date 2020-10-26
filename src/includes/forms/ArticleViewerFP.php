<?php

require_once 'FormProcessor.php';
require_once __DIR__ . "/../db/article.php";
require_once __DIR__ . "/../types/User.php";
require_once __DIR__ . "/../types/Comment.php";

class ArticleViewerFP extends FormProcessor
{
    const OP_RATE = "rate";
    const OP_COMMENT = "comment";

    protected static $operation_map = [
        self::OP_RATE => [
            "handler" => "static::processRatingForm",
            "req_fields" => [
                ["article_id", FILTER_VALIDATE_INT],
                ["stars", FILTER_VALIDATE_INT],
            ],
            "opt_fields" => [],
        ],
        self::OP_COMMENT => [
            "handler" => "static::processCommentForm",
            "req_fields" => [
                ["article_id", FILTER_VALIDATE_INT],
                ["comment"],
            ],
            "opt_fields" => [],
        ],
    ];

    private static function redirectToArticle($article_id)
    {
        $href = "article.php?article_id=" . $_POST["article_id"];
        header("Location: $href");
    }

    protected static function processRatingForm(PDO $pdo, User $user)
    {
        set_article_rating($pdo, $user, $_POST["article_id"], $_POST["stars"]);
        self::redirectToArticle($_POST["article_id"]);
    }

    protected static function processCommentForm(PDO $pdo, User $user)
    {
        $comment = new Comment();
        $comment->author_id = $user->user_id;
        $comment->body = $_POST["comment"];

        create_article_comment($pdo, $_POST["article_id"], $comment);
        self::redirectToArticle($_POST["article_id"]);
    }
}
