<?php

require_once 'Comment.php';

class ArticleComment extends Comment
{
    protected $article_comment_id;

    public function __construct()
    {
        parent::__construct();

        $this->article_comment_id = (int) $this->article_comment_id;
    }

    public function getID()
    {
        return $this->article_comment_id;
    }
}
