<?php

require("../../../../includes/script_start.php");

// If logged in, show client area.
// If not logged in, redirect to login page.

if(LOGGED_IN) {
    
    echo $twig->render('client-area/add-new/github/select-repo.html', [
        'error_msg' => $_GET["error"]
    ]);

} else {
    
    client_area_logged_out_handler();
    
}