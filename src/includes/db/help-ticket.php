<?php

require_once __DIR__ . '/../types/HelpTicket.php';

//
// TODO like all of these help ticket drivers
//

define("GET_HELP_TICKET_QUERY", "
    SELECT
        help_ticket_id,
        submitter,
        assignee,
        submitted_at,
        is_closed,
        closed_by_submitter,
        closed_at,
        subject,
        body
    FROM help_ticket
");

function get_help_tickets(PDO $pdo, User $user) // TODO , HelpTicketFilter $filter)
{
    $stmt = $pdo->prepare(GET_HELP_TICKET_QUERY . "
        /* TODO this should be filtered */
    ");

    $stmt->execute();

    $help_tickets = $stmt->fetchAll(PDO::FETCH_CLASS, "HelpTicket");

    if (count($help_tickets) === 0) {
        return array();
    }
    return $help_tickets;
}

function get_help_ticket_by_id(PDO $pdo, $help_ticket_id)
{
    $stmt = $pdo->prepare(GET_HELP_TICKET_QUERY . "
        WHERE help_ticket_id = :help_ticket_id
    ");

    $stmt->bindParam(":help_ticket_id", $help_ticket_id);

    $stmt->execute();

    return $stmt->fetchObject("HelpTicket");
}

function create_help_ticket(PDO $pdo, HelpTicket $help_ticket)
{
    $stmt = $pdo->prepare("
        INSERT INTO help_ticket(
            submitter,
            subject,
            body
        ) VALUES (
            :submitter,
            :subject,
            :body
        )
    ");

    $stmt->bindParam(":submitter", $help_ticket->submitter_id);
    $stmt->bindParam(":subject", $help_ticket->subject);
    $stmt->bindParam(":body", $help_ticket->body);

    $stmt->execute();
}

function assign_help_ticket(PDO $pdo, $help_ticket_id, $user_id)
{
    $stmt = $pdo->prepare("
        UPDATE help_ticket
        SET assignee = :assignee
        WHERE help_ticket_id = :help_ticket_id
    ");

    $stmt->bindParam(":assignee", $user_id);
    $stmt->bindParam(":help_ticket_id", $help_ticket_id);

    $stmt->execute();
}

function close_help_ticket(PDO $pdo, $help_ticket_id, $user_id)
{
    $stmt = $pdo->prepare("
        UPDATE help_ticket
        SET
            is_closed = TRUE,
            closed_at = NOW(),
            closed_by_submitter = (submitter = :user_id)
        WHERE help_ticket_id = :help_ticket_id
    ");

    $stmt->bindParam(":user_id", $user_id);
    $stmt->bindParam(":help_ticket_id", $help_ticket_id);

    $stmt->execute();
}

function reopen_help_ticket(PDO $pdo, $help_ticket_id)
{
    $stmt = $pdo->prepare("
        UPDATE help_ticket
        SET
            is_closed = FALSE,
            closed_at = NULL,
            closed_by_submitter = NULL
        WHERE help_ticket_id = :help_ticket_id
    ");

    $stmt->bindParam(":help_ticket_id", $help_ticket_id);

    $stmt->execute();
}

function create_help_ticket_comment(PDO $pdo, $help_ticket_id, Comment $comment)
{
    $pdo->beginTransaction();

    try {
        $comment_id = create_comment($pdo, $comment);

        $stmt = $pdo->prepare("
            INSERT INTO help_ticket_comment (
                help_ticket_id,
                comment_id
            ) VALUES (
                :help_ticket_id,
                :comment_id
            )
        ");

        $stmt->bindParam(":help_ticket_id", $help_ticket_id);
        $stmt->bindParam(":comment_id", $comment_id);

        $stmt->execute();
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }

    $pdo->commit();
}

function get_comments_for_help_ticket(PDO $pdo, $help_ticket_id)
{
    $stmt = $pdo->prepare(GET_COMMENT_QUERY . "
        INNER JOIN help_ticket_comment htc
            ON c.comment_id = htc.comment_id
        WHERE htc.help_ticket_id = :help_ticket_id
        ORDER BY c.posted_at DESC
    ");

    $stmt->bindParam(":help_ticket_id", $help_ticket_id);

    $stmt->execute();

    $comments = $stmt->fetchAll(PDO::FETCH_CLASS, "Comment");

    if (!$comments || count($comments) === 0) {
        return array();
    }
    return $comments;
}
