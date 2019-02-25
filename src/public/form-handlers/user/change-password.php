<?php

// Check details before proceeding

$password = $_POST["password"];
$new_password = $_POST["new_password"];

// If email is not set
if(empty($new_password)) {
    header("Location: /client-area/user/change-email.php?error=New password was not set.");

    die();
}

// If password is not set
if(empty($password)) {
    header("Location: /client-area/user/change-email.php?error=Password was not set.");

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

// Email address
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

$password_hash = password_hash($new_password, PASSWORD_DEFAULT);

// Validate password, if user exists.
if (password_verify($password, $result["password"])) {
    // Set new email address
    $kysely = $connection->prepare("UPDATE users SET password = ? WHERE email = ?");
    $kysely->execute(array($password_hash, $email));

    // Unset session to force relogin.
    unset($_SESSION["logged_in"]);

    // TODO: Send notification to email address about the change

    header("Location: /user/login.php?pwd_change=1");

    die();
} else {
    header("Location: /client-area/user/change-email.php?error=Invalid password.");

    die();
}