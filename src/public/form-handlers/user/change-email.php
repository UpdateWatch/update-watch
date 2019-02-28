<?php

// Check details before proceeding

$new_email = $_POST["email"];
$password = $_POST["password"];

// If email is not set
if(empty($new_email)) {
    header("Location: /client-area/user/change-email.php?error=New email was not set.");

    die();
}

// If password is not set
if(empty($password)) {
    header("Location: /client-area/user/change-email.php?error=Password was not set.");

    die();
}

// Check validity of the new email
if(!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
    header("Location: /client-area/user/change-email.php?error=Invalid email address.");

    die();
}



// Load essential stuff

require_once '../../../includes/script_start.php';
require_once '../../../includes/database.php';

// Check logged in status

if(!LOGGED_IN) {
    // This should be impossible, but maybe someone deletes cookies before submitting the form...
    header("Location: /client-area/user/change-email.php?error=Login to change password.");

    die();
}

// Old email address
$email = USERNAME;

// Open MySQL connection

$connection = getConnection();

// Check if current password is correct

$query = $connection->prepare("SELECT * FROM users WHERE email = ?");
$query->execute(array(
    $email
));

$result = $query->fetch();

// Check if user exists
if(empty($result["email"])) {
    // TODO: Make report to Sentry or something
    header("Location: /client-area/user/change-email.php?error=Internal error.");

    die();
}

// Validate password, if user exists.
if (password_verify($password, $result["password"])) {
    // Set new email address
    $kysely = $connection->prepare("UPDATE users SET email = ? WHERE email = ?");
    $kysely->execute(array($new_email, $email));

    // Unset session to force relogin.
    unset($_SESSION["logged_in"]);
    unset($_SESSION["logged_in_user_id"]);

    // TODO: Send notification to old&new addresses about the change

    header("Location: /user/login.php?email_change=1");

    die();
} else {
    header("Location: /client-area/user/change-email.php?error=Invalid password.");

    die();
}