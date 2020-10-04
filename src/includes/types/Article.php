<?php

class Article
{
    public $article_id;

    public $category_title;
    public $category_id;

    public $author_name;
    public $author_id;

    public $title;
    public $body;

    public $created_at;
    public $updated_at;

    public function __construct()
    {
        $this->article_id = (int) $this->article_id;
        $this->category_id = (int) $this->category_id;
        $this->author_id = (int) $this->author_id;

        $this->created_at = new DateTime($this->created_at);
        $this->updated_at = new DateTime($this->updated_at);
    }
}
