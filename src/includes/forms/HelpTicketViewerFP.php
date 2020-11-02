<?php

require_once 'FormProcessor.php';
require_once __DIR__ . "/../db/help-ticket.php";
require_once __DIR__ . "/../pages/RequestStatusPage.php";
require_once __DIR__ . "/../types/User.php";
require_once __DIR__ . "/../types/Comment.php";

class HelpTicketViewerFP extends FormProcessor
{
    const OP_ASSIGN = "assign";
    const OP_REPLY = "reply";
    const OP_CLOSE = "close";
    const OP_REOPEN = "reopen";

    protected static $operation_map = [
        self::OP_ASSIGN => [
            "handler" => "static::processAssign",
            "req_fields" => [
                ["help_ticket_id", FILTER_VALIDATE_INT],
                ["assignee_id", FILTER_VALIDATE_INT],
            ],
            "opt_fields" => [
                ["comment"]
            ],
            "req_admin" => true,
        ],
        self::OP_REOPEN => [
            "handler" => "static::processReopen",
            "req_fields" => [
                ["help_ticket_id", FILTER_VALIDATE_INT],
                ["comment"]
            ],
            "opt_fields" => [],
            "req_admin" => false,
        ],
        self::OP_CLOSE => [
            "handler" => "static::processClose",
            "req_fields" => [
                ["help_ticket_id", FILTER_VALIDATE_INT],
                ["comment"]
            ],
            "opt_fields" => [],
            "req_admin" => false,
        ],
        self::OP_REPLY => [
            "handler" => "static::processReply",
            "req_fields" => [
                ["help_ticket_id", FILTER_VALIDATE_INT],
                ["comment"]
            ],
            "opt_fields" => [],
            "req_admin" => false,
        ],
    ];

    private static function redirectToTicket($help_ticket_id)
    {
        header("Location: ticket.php?help_ticket_id=$help_ticket_id");
    }

    protected static function processAssign(PDO $pdo, User $user)
    {
        $help_ticket_id = $_POST["help_ticket_id"];

        $help_ticket = get_help_ticket_by_id($pdo, $help_ticket_id);
        if ($help_ticket->is_closed) {
            throw new InvalidArgumentException(
                "Cannot assign a ticket that is closed."
            );
        }

        // If the form sends a zero for unassigned, it will become NULL.
        $assignee = $_POST["assignee_id"] ?: null;

        if ($assignee === $help_ticket->assignee_id) {
            throw new InvalidArgumentException(
                "Ticket is already assigned to $help_ticket->assignee_name."
            );
        }

        assign_help_ticket($pdo, $help_ticket_id, $assignee);

        // FIXME consider making these journal comments have the root user as
        // the author. Comment contents would be something like
        // Billy Bob (ID #4983) closed this ticket.
        // Then put the REASON as a separate comment by the user.

        $comment = new Comment();
        $comment->author_id = $user->user_id;

        $old_assignee = $help_ticket->assignee_name ?: "Unassigned";
        $new_assignee = $assignee
            ? get_user_by_id($pdo, $assignee)->fullName()
            : "Unassigned";

        $comment->body = "Changed assignee from $old_assignee to " .
            "$new_assignee with reason :\n\n" . $_POST["comment"];

        create_help_ticket_comment($pdo, $help_ticket_id, $comment);

        self::redirectToTicket($help_ticket_id);
    }

    protected static function processClose(PDO $pdo, User $user)
    {
        $help_ticket_id = $_POST["help_ticket_id"];

        $help_ticket = get_help_ticket_by_id($pdo, $help_ticket_id);

        if (!$help_ticket->checkUserAccess($user)) {
            // FIXME perhaps there might be a better way to handle forbidden
            // like throwing a sentinel exception or something
            (new RequestStatusPage(
                HTTPStatus::STATUS_FORBIDDEN,
                $user,
                "You do not have permission to view ticket #$help_ticket_id."
            ))->render();
            exit();
        }

        if ($help_ticket->is_closed) {
            throw new InvalidArgumentException(
                "Cannot close a ticket that is already closed!"
            );
        }

        close_help_ticket($pdo, $help_ticket_id, $user->user_id);

        // FIXME consider making these journal comments have the root user as
        // the author. Comment contents would be something like
        // Billy Bob (ID #4983) closed this ticket.
        // Then put the REASON as a separate comment by the user.

        $comment = new Comment();
        $comment->author_id = $user->user_id;
        $comment->body = "Closed this help ticket with reason:\n\n"
            . $_POST["comment"];

        create_help_ticket_comment($pdo, $help_ticket_id, $comment);

        self::redirectToTicket($help_ticket_id);
    }

    protected static function processReopen(PDO $pdo, User $user)
    {
        $help_ticket_id = $_POST["help_ticket_id"];

        $help_ticket = get_help_ticket_by_id($pdo, $help_ticket_id);

        if (!$help_ticket->checkUserAccess($user)) {
            // FIXME perhaps there might be a better way to handle forbidden
            // like throwing a sentinel exception or something
            (new RequestStatusPage(
                HTTPStatus::STATUS_FORBIDDEN,
                $user,
                "You do not have permission to view ticket #$help_ticket_id."
            ))->render();
            exit();
        }

        if (!$help_ticket->is_closed) {
            throw new InvalidArgumentException(
                "Cannot reopen a ticket that is already open!"
            );
        }

        reopen_help_ticket($pdo, $help_ticket_id);

        $comment = new Comment();
        $comment->author_id = $user->user_id;
        $comment->body = "Reopened this help ticket with reason:\n\n"
            . $_POST["comment"];

        create_help_ticket_comment($pdo, $help_ticket_id, $comment);

        self::redirectToTicket($help_ticket_id);
    }

    protected static function processReply(PDO $pdo, User $user)
    {
        $help_ticket_id = $_POST["help_ticket_id"];

        $help_ticket = get_help_ticket_by_id($pdo, $help_ticket_id);

        if (!$help_ticket->checkUserAccess($user)) {
            // FIXME perhaps there might be a better way to handle forbidden
            // like throwing a sentinel exception or something
            (new RequestStatusPage(
                HTTPStatus::STATUS_FORBIDDEN,
                $user,
                "You do not have permission to view ticket #$help_ticket_id."
            ))->render();
            exit();
        }

        if ($help_ticket->is_closed) {
            throw new InvalidArgumentException(
                "Cannot reply to ticket that is closed!"
            );
        }

        $comment = new Comment();
        $comment->author_id = $user->user_id;
        $comment->body = $_POST["comment"];

        create_help_ticket_comment($pdo, $help_ticket_id, $comment);

        self::redirectToTicket($help_ticket_id);
    }
}
