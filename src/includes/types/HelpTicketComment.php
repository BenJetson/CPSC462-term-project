<?php

require_once 'Comment.php';

class HelpTicketComment extends Comment
{
    protected $help_ticket_comment_id;

    public function __construct()
    {
        parent::__construct();

        $this->help_ticket_comment_id = (int) $this->help_ticket_comment_id;
    }

    public function getID()
    {
        return $this->help_ticket_comment_id;
    }
}
