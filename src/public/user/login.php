<?php

require("../../includes/script_start.php");

if(LOGGED_IN) {

    header("Location: /client-area?from=login");
    die();

} else {

    echo $twig->render('user/login.html', [
        'error_msg' => $_GET["error"],
        'welcome' => $_GET["welcome"],
        'email_change' => $_GET['email_change'],
        'pwd_change' => $_GET['pwd_change'],
        'acc_deletion' => $_GET['delete'],
    ]);

}