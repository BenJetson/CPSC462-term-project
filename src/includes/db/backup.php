<?php

require_once __DIR__ . '/../types/Backup.php';
require_once __DIR__ . '/../types/HelpTicketFilter.php';
require_once __DIR__ . '/../types/User.php';
require_once __DIR__ . '/article.php'; // why was this ambiguous?
require_once 'comment.php';
require_once 'help-ticket.php';
require_once 'user.php';

function create_backup_archive(PDO $pdo, User $user)
{
    // Lock the database so that data does not change during the course of
    // the backup operation. This statement also deletes caches to ensure that
    // the backup will not contain stale cached data.
    // $pdo->exec("
    //     LOCK TABLES
    //         user READ,
    //         comment c READ,
    //         article_category READ,
    //         article a READ,
    //         article_comment READ,
    //         help_ticket READ,
    //         help_ticket_comment READ
    // ");

    // Create a temporary file to store the backup archive during creation.
    $out_file = tempnam(sys_get_temp_dir(), "it_helpdesk_bak_");

    // Create a new ZIP archive in this temporary file.
    $zip = new ZipArchive;
    $zip->open($out_file, ZipArchive::CREATE);

    // Add some metadata about the backup being created to the archive.
    $details = new Backup($user);
    $zip->addFromString(
        "backup-details.json",
        json_encode($details, JSON_PRETTY_PRINT)
    );

    // Add user data.
    $users = get_all_users($pdo);
    $zip->addFromString(
        "users.json",
        json_encode($users, JSON_PRETTY_PRINT)
    );

    // Add article category data.
    $article_categories = get_article_categories($pdo);
    $zip->addFromString(
        "article-categories.json",
        json_encode($article_categories, JSON_PRETTY_PRINT)
    );

    // Add article data.
    $articles = get_all_articles($pdo);
    $zip->addFromString(
        "articles.json",
        json_encode($articles, JSON_PRETTY_PRINT)
    );

    // Get all article comments.
    $article_comments = [];
    foreach ($articles as $article) {
        $comments = get_comments_for_article($pdo, $article->article_id);
        $article_comments[$article->article_id] = $comments;
    }
    $zip->addFromString(
        "article-comments.json",
        json_encode($article_comments, JSON_PRETTY_PRINT)
    );

    // Get all help tickets.
    $ticket_filter = new HelpTicketFilter(HelpTicketFilter::ALL, $user);
    $help_tickets = get_help_tickets($pdo, $ticket_filter);
    $zip->addFromString(
        "help-tickets.json",
        json_encode($help_tickets, JSON_PRETTY_PRINT)
    );

    // Get all help ticket comments.
    $ticket_comments = [];
    foreach ($help_tickets as $ticket) {
        $comments = get_comments_for_help_ticket($pdo, $ticket->help_ticket_id);
        $ticket_comments[$ticket->help_ticket_id] = $comments;
    }
    $zip->addFromString(
        "help-ticket-comments.json",
        json_encode($ticket_comments, JSON_PRETTY_PRINT)
    );

    // All data has been added. Close the ZIP file.
    $zip->close();

    // Unlock the tables so that other application processes may use the DB.
    // $pdo->exec("
    //     UNLOCK TABLES
    // ");

    // Pass the file name back to the caller so they may initiate a download.
    return $out_file;
}
