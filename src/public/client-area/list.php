<?php

require("../../includes/script_start.php");




if(LOGGED_IN) {

    echo $twig->render('client-area/list.html');

} else {
    
    client_area_logged_out_handler();
    
}