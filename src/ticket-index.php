<?php

require 'includes/init.php';

require_once 'includes/components/Navbar.php';
require_once 'includes/components/HelpTicketList.php';
require_once 'includes/db/help-ticket.php';
require_once 'includes/db/connect.php';
require_once 'includes/db/user.php';
require_once 'includes/types/HelpTicketFilter.php';
require_once 'includes/pages/Page.php';
require_once 'includes/pages/RequestStatusPage.php';

$pdo = db_connect();

$user = get_user_by_token($pdo);
if ($user === null) {
    $errPage = new RequestStatusPage(HTTPStatus::STATUS_NOT_AUTHORIZED);
    $errPage->render();
    exit();
}

$filter = HelpTicketFilter::constructFromURL($user);
if (!$filter->checkUserAccess()) {
    $errPage = new RequestStatusPage(HTTPStatus::STATUS_FORBIDDEN, $user);
    $errPage->render();
    exit();
}

$help_tickets = get_help_tickets($pdo, $filter);

$title = "Help Tickets";
$page = new Page($title, [
    new Navbar($user, $title),
    new HelpTicketList($help_tickets, $filter, $user),
]);

$page->render();
