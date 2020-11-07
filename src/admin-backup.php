<?php

require 'includes/init.php';

require_once 'includes/components/Navbar.php';
require_once 'includes/db/connect.php';
require_once 'includes/db/backup.php';
require_once 'includes/db/user.php';
require_once 'includes/pages/Page.php';
require_once 'includes/pages/RequestStatusPage.php';


$pdo = db_connect();

$user = get_user_by_token($pdo);
if ($user === null) {
    $errPage = new RequestStatusPage(HTTPStatus::STATUS_NOT_AUTHORIZED);
    $errPage->render();
    exit();
} else if (!$user->is_admin) {
    $errPage = new RequestStatusPage(HTTPStatus::STATUS_FORBIDDEN, $user);
    $errPage->render();
    exit();
}

if (isset($_GET["download"])) {
    // Create the backup file at a temporary location.
    $out_file = create_backup_archive($pdo, $user);

    // Send the headers to initiate a file download on the client.
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="backup.zip"');
    header('Content-Length: ' . filesize($out_file));

    // Read the contents of the file and send to the client as the download.
    readfile($out_file);

    // This temporary file is no longer needed. Delete it.
    unlink($out_file);

    exit();
}

$downloadHref = "admin-backup.php?download";
header("Refresh: 3; $downloadHref");

$page = new RequestStatusPage(
    HTTPStatus::STATUS_OK,
    $user,
    <<< EOF
Your backup will begin to download shortly.
<br />
<br />
If it does not start after a few moments, you may
<a class="alert-link" href="$downloadHref">start the download manually</a>.
EOF
);
$page->render();
