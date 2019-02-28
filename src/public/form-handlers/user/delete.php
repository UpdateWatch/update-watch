<?php

// Check details before proceeding

$password = $_POST["password"];

// If password is not set
if(empty($password)) {
    header("Location: /client-area/user/delete.php?error=Password was not set.");

    die();
}



// Load essential stuff

require_once '../../../includes/script_start.php';
require_once '../../../includes/database.php';

// Check logged in status

if(!LOGGED_IN) {
    // This should be impossible, but maybe someone deletes cookies before submitting the form...
    header("Location: /client-area/user/delete.php?error=Login to delete your account.");

    die();
}

// Open MySQL connection

$connection = getConnection();

// Check if current password is correct

$user_id = USER_ID;

$query = $connection->prepare("SELECT * FROM users WHERE id = ?");
$query->execute(array(
    $user_id
));

$result = $query->fetch();

// Check if user exists
if(empty($result["email"])) {
    // TODO: Make report to Sentry or something
    header("Location: /client-area/user/delete.php?error=Internal error.");

    die();
}

// Validate password, if user exists.
if (password_verify($password, $result["password"])) {
    // Delete account database
    $query = $connection->prepare("DELETE FROM users WHERE id = ?");
    $query->execute(array($user_id));

    $query = $connection->prepare("DELETE FROM watchers WHERE owner = ?");
    $query->execute(array($user_id));

    // Unset session
    unset($_SESSION["logged_in"]);
    unset($_SESSION["logged_in_user_id"]);

    // TODO: Send notification to email address about the deletion

    header("Location: /user/login.php?delete=1");

    die();
} else {
    header("Location: /client-area/user/delete.php?error=Invalid password.");

    die();
}