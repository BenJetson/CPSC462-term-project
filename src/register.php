<?php

require_once 'includes/components/UserProfileForm.php';
require_once 'includes/components/Navbar.php';
require_once 'includes/db/connect.php';
require_once 'includes/pages/Page.php';

// TODO make sure this is everywhere.
ini_set('display_errors', 0);

$pdo = db_connect();

$page = new Page("Register", [
    new Navbar(null, "Register"),
    new UserProfileForm(null),
]);

$page->render();
