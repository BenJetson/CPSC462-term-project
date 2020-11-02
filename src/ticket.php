<?php

require 'includes/init.php';

require_once 'includes/components/HelpTicketViewer.php';
require_once 'includes/components/Navbar.php';
require_once 'includes/db/connect.php';
require_once 'includes/db/help-ticket.php';
require_once 'includes/db/user.php';
require_once 'includes/forms/HelpTicketViewerFP.php';
require_once 'includes/pages/Page.php';
require_once 'includes/pages/RequestStatusPage.php';
require_once 'includes/types/HelpTicket.php';

$pdo = db_connect();

$user = get_user_by_token($pdo);
if ($user === null) {
    $errPage = new RequestStatusPage(HTTPStatus::STATUS_NOT_AUTHORIZED);
    $errPage->render();
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    HelpTicketViewerFP::process($pdo, $user);
    exit();
}

if (!isset($_GET["help_ticket_id"])) {
    $errPage = new RequestStatusPage(HTTPStatus::STATUS_BAD_REQUEST, $user);
    $errPage->render();
    exit();
}

$help_ticket = get_help_ticket_by_id($pdo, $_GET["help_ticket_id"]);
if (!$help_ticket) {
    // TODO should this be a forbidden? not sure.
    $errPage = new RequestStatusPage(HTTPStatus::STATUS_NOT_FOUND, $user);
    $errPage->render();
    exit();
}

if (!$help_ticket->checkUserAccess($user)) {
    $errPage = new RequestStatusPage(HTTPStatus::STATUS_FORBIDDEN, $user);
    $errPage->render();
    exit();
}

$admin_users = [];
if ($user->is_admin) {
    $admin_users = get_active_admin_users($pdo);
}

$comments = get_comments_for_help_ticket($pdo, $help_ticket->help_ticket_id);

$title = "Ticket #$help_ticket->help_ticket_id";
$page = new Page($title, [
    new Navbar($user, null),
    new HelpTicketViewer($user, $help_ticket, $comments, $admin_users),
]);

$page->render();
