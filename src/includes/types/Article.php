<?php

class Article
{
    /** @var int */
    public $article_id;

    /** @var string  */
    public $category_title;
    /** @var int */
    public $category_id;

    /** @var string  */
    public $author_name;
    /** @var int */
    public $author_id;

    /** @var string */
    public $title;
    /** @var string */
    public $body;

    /** @var int */
    public $rating;
    /** @var int */
    public $rating_count;

    /** @var DateTime */
    public $created_at;
    /** @var DateTime */
    public $updated_at;

    public function __construct()
    {
        $this->article_id = (int) $this->article_id;
        $this->category_id = (int) $this->category_id;
        $this->author_id = (int) $this->author_id;

        $this->rating = (int) $this->rating;
        $this->rating_count = (int) $this->rating_count;

        $this->created_at = new DateTime($this->created_at);
        $this->updated_at = new DateTime($this->updated_at);
    }
}
