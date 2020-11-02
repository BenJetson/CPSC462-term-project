<?php

class Comment
{
    /** @var int */
    public $comment_id;
    /** @var DateTime */
    public $posted_at;
    /** @var string */
    public $author_name;
    /** @var string */
    public $author_monogram;
    /** @var int */
    public $author_id;
    /** @var string */
    public $body;
    /** @var bool */
    public $is_reply;

    public function __construct()
    {
        $this->comment_id = (int) $this->comment_id;
        $this->author_id = (int) $this->author_id;
        $this->posted_at = new DateTime($this->posted_at);
        $this->is_reply = (bool) $this->is_reply;
    }
}
