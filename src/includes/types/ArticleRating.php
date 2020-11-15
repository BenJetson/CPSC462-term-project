<?php

class ArticleRating
{
    public $article_id;
    public $user_id;
    public $stars;

    public function __construct()
    {
        $this->article_id = (int) $this->article_id;
        $this->user_id = (int) $this->user_id;
        $this->stars = (int) $this->stars;
    }
}
