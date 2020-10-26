<?php

class ArticleCategory
{
    public $article_category_id;
    public $title;
    public $descr;
    public $color;
    public $icon;

    public function __construct()
    {
        $this->article_category_id = (int) $this->article_category_id;
    }
}
