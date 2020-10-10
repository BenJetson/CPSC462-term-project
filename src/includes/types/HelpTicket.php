<?php

// TODO need to integrate comments here

class HelpTicket
{
    public $help_ticket_id;
    public $submitter_id;
    public $submitter_name;
    public $assignee_id;
    public $assignee_name;
    public $submitted_at;
    public $is_closed;
    public $closed_by_submitter;
    public $closed_at;
    public $subject;
    public $body;

    public function __construct()
    {
        $this->help_ticket_id = (int) $this->help_ticket_id;
        $this->submitter_id = (int) $this->submitter_id;
        $this->assignee_id = (int) $this->assignee_id;
        $this->submitted_at = new DateTime($this->submitted_at);
        $this->is_closed = (bool) $this->is_closed;
        $this->closed_by_submitter = (bool) $this->closed_by_submitter;
        $this->closed_at = new DateTime($this->closed_at);
    }
}
