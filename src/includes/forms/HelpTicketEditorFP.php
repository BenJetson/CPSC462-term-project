<?php

require_once 'FormProcessor.php';
require_once __DIR__ . "/../db/help-ticket.php";
require_once __DIR__ . "/../types/HelpTicket.php";
require_once __DIR__ . "/../types/User.php";

class HelpTicketEditorFP extends FormProcessor
{
    const OP_CREATE = "ticket-create";

    protected static $operation_map = [
        self::OP_CREATE => [
            "handler" => "static::processCreateTicket",
            "req_fields" => [
                ["subject"],
                ["body"],
            ],
            "opt_fields" => [],
            "req_admin" => false,
        ],
    ];

    protected static function processCreateTicket(PDO $pdo, User $user)
    {
        $help_ticket = new HelpTicket();

        $help_ticket->subject = $_POST["subject"];
        $help_ticket->body = $_POST["body"];
        $help_ticket->submitter_id = $user->user_id;

        create_help_ticket($pdo, $help_ticket);

        // TODO consider redirecting to the ticket after creation, but
        // that would require the DB driver to return the ID.
        header("Location: ticket-index.php");
    }
}
