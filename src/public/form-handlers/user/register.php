<?php

// Check details before proceeding

$email = $_POST["email"];
$password = $_POST["password"];
$password2 = $_POST["password2"];
$tos = $_POST["tos"];

// If email is not set
if(empty($email)) {
    header("Location: /user/register.php?error=Email was not set.");

    die();
}

// If password is not set
if(empty($password)) {
    header("Location: /user/register.php?error=Password was not set.&1");

    die();
}

if(empty($password2)) {
    header("Location: /user/register.php?error=Password was not set.&2");

    die();
}

// Check if password is the same
if($password !== $password2) {
    header("Location: /user/register.php?error=The password confirmation does not match.");

    die();
}

// Check if user agreed to ToS
if($tos !== "on") {
    header("Location: /user/register.php?error=You didn't agree Terms of Service and Privacy Policy.");

    die();
}

// Check validity of email address
if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: /user/register.php?error=Invalid email address.");

    die();
}

// Load essential stuff

require_once '../../../includes/script_start.php';
require_once '../../../includes/database.php';

// Create password hash

$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Open MySQL connection

$connection = getConnection();

// Check if user already exists

$check_query = $connection->prepare("SELECT * FROM users WHERE email = '$email'");
$check_query->execute();

$check_result = $check_query->fetch();

if(!empty($check_result["email"])) {
    header("Location: /user/register.php?error=User with the email address already exists.");

    die();
}

// Create user, if user with the email doesn't exist.

$query = $connection->prepare("INSERT INTO `users` (`email`, `password`) VALUES (?, ?);");
$query->execute(array($email, $password_hash));


header("Location: /user/login.php?welcome=1");
die();