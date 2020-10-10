<?php

class Comment
{
    public $comment_id;
    public $posted_at;
    public $author_name;
    public $author_id;
    public $body;

    public function __construct()
    {
        $this->comment_id = (int) $this->comment_id;
        $this->author_id = (int) $this->author_id;
        $this->posted_at = new DateTime($this->posted_at);
    }
}
