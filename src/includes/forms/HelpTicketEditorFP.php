<?php

require_once 'FormProcessor.php';
require_once __DIR__ . "/../db/help-ticket.php";
require_once __DIR__ . "/../types/HelpTicket.php";
require_once __DIR__ . "/../types/User.php";

class HelpTicketEditorFP extends FormProcessor
{
    const OP_EDIT = "ticket-edit";
}
