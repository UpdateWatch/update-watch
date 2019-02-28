<?php

// Check details before proceeding

$email = $_POST["email"];
$password = $_POST["password"];

// If email is not set
if(empty($email)) {
    header("Location: /user/login.php?error=Email was not set.");

    die();
}

// If password is not set
if(empty($password)) {
    header("Location: /user/login.php?error=Password was not set.");

    die();
}



// Load essential stuff

require_once '../../../includes/script_start.php';
require_once '../../../includes/database.php';

// Open MySQL connection

$connection = getConnection();

// Send query

$query = $connection->prepare("SELECT * FROM users WHERE email = ?");
$query->execute(array(
    $email
));

$result = $query->fetch();

// Check if user exists
if(empty($result["email"])) {
    header("Location: /user/login.php?error=Invalid username or password.");

    die();
}

// Validate password, if user exists.
if (password_verify($password, $result["password"])) {
    $_SESSION["logged_in"] = $result["email"];
    $_SESSION["logged_in_user_id"] = $result["id"];
    
    header("Location: /client-area/");

    die();
} else {
    header("Location: /user/login.php?error=Invalid username or password.");

    die();
}