<?php

class ArticleCategory
{
    public $article_category_id;
    public $title;

    public function __construct()
    {
        $this->article_category_id = (int) $this->article_category_id;
    }
}
