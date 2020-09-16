<?php


require_once 'includes/secrets.php';
require_once 'includes/db-connect.php';
require_once 'includes/db/user.php';

echo "[";

$user = get_user_by_id($db, 1);

echo json_encode($user, true);

echo ",";

$user2 = get_user_by_email($db, "bfgodfr@clemson.edu");

echo json_encode($user2, true);

echo "]";
