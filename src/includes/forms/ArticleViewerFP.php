<?php

require_once 'FormProcessor.php';
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
    }

    protected static function processCommentForm(PDO $pdo, User $user)
    {
        echo "Processing comment form";
    }
}
