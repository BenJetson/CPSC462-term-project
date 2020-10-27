<?php

class User
{
    public $user_id;
    public $is_admin;
    public $is_disabled;

    public $email;
    public $email_changed_at;
    public $email_confirmed;

    public $first_name;
    public $last_name;
    public $dob;
    public $telephone;

    public $address_line_2;
    public $address_line_1;
    public $address_city;
    public $address_state;
    public $address_zip;

    public $pass_hash;
    public $pass_changed_at;
    public $pass_attempts;
    public $pass_locked_at;

    public function __construct()
    {
        // Ensure that these are cast to the correct types.
        $this->user_id = (int) $this->user_id;
        $this->is_admin = (bool) $this->is_admin;
        $this->is_disabled = (bool) $this->is_disabled;

        $this->email_changed_at = new DateTime($this->email_changed_at); // FIXME check this datetime

        $this->dob = date_parse($this->dob); // FIXME check this date format

        $this->pass_changed_at = new DateTime($this->pass_changed_at); // FIXME check this datetime
        $this->pass_attempts = (int) $this->pass_attempts;
        $this->pass_locked_at = new DateTime($this->pass_locked_at); // FIXME check this datetime

    }

    public function fullName()
    {
        return $this->first_name . " " . $this->last_name;
    }
}
