<?php

require_once 'FormProcessor.php';
require_once __DIR__ . "/../db/article.php";
require_once __DIR__ . "/../types/User.php";

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
            "req_fields" => [],
            "opt_fields" => [],
        ],
    ];

    protected static function processRatingForm(PDO $pdo, User $user)
    {
        set_article_rating($pdo, $user, $_POST["article_id"], $_POST["stars"]);
        // TODO redirect?
    }

    protected static function processCommentForm(PDO $pdo, User $user)
    {
        echo "Processing comment form";
    }
}
