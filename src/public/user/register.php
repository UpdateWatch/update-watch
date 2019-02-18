<?php

require("../../includes/script_start.php");

if(LOGGED_IN) {

    header("Location: /client-area?from=register");
    die();

} else {

    echo $twig->render('user/register.html');

}