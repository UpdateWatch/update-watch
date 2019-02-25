<?php

require("../../includes/script_start.php");

// If logged in, show client area.
// If not logged in, redirect to login page.

if(LOGGED_IN) {

    echo $twig->render('client-area/index.html');

} else {
    
    client_area_logged_out_handler();
    
}