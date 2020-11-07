<?php

class Backup
{
    public $requestor_name;
    public $requestor_id;

    public $timestamp;

    public $tier;

    public function __construct(User $user)
    {
        $this->requestor_name = $user->fullName();
        $this->requestor_id = $user->user_id;

        $this->timestamp = (new DateTime("now"))->format("Y-m-d h:i:s A T");

        $this->tier = $_SERVER["TIER"];
    }
}
