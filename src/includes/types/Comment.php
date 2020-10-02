<?php

abstract class Comment
{
    protected $posted_at;
    protected $author_name;
    protected $body;

    public function __construct()
    {
        $this->posted_at = new DateTime($this->posted_at);
    }

    public abstract function getID();
}
