<?php
session_start();

unset($_SESSION["logged_in"]);
unset($_SESSION["logged_in_user_id"]);

header("Location: /");
?>