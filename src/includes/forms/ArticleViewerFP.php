<?php

require_once 'FormProcessor.php';
require_once __DIR__ . "/../db/article.php";
require_once __DIR__ . "/../types/User.php";

class ArticleViewerFP extends FormProcessor
{
    const OP_RATE = "rate";
    const OP_COMMENT = "comment";

    protected static $operation_map = [
        self::OP_RATE => 'static::processRatingForm',
        self::OP_COMMENT => 'static::processCommentForm',
    ];

    protected static function processRatingForm(PDO $pdo, User $user)
    {
        echo "Processing rating form";

        if (!isset($_POST["article_id"])) {
            self::userError($user, "missing article_id field");
        }

        if (!isset($_POST["stars"])) {
            self::userError($user, "missing stars field");
        }

        set_article_rating($pdo, $user, $_POST["article_id"], $_POST["stars"]);

        // TODO redirect?
    }

    protected static function processCommentForm(PDO $pdo, User $user)
    {
        echo "Processing comment form";
    }
}
