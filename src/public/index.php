<?php

require("../includes/script_start.php");

// If logged in, redirect to client area.
// If not logged in, show frontpage.

if(LOGGED_IN) {

    header("Location: /client-area?from=root");
    die();

} else {

    echo $twig->render('index.html');

}