<?php

abstract class Comment
{
    protected $posted_at;
    protected $author_name;
    protected $author_id;
    protected $body;

    public function __construct()
    {
        $this->author_id = (int) $this->author_id;
        $this->posted_at = new DateTime($this->posted_at);
    }

    public abstract function getID();
}
