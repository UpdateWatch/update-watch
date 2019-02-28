<?php

require("../../../../includes/script_start.php");

// If logged in, show client area.
// If not logged in, redirect to login page.

if(LOGGED_IN) {

    $repository = $_GET["repository"];
    $version = $_GET["version"];

    if(empty($repository)) {
        die("Repository is empty.");
    }

    if(empty($version)) {
        die("Version is empty.");
    }

    print_r($_GET);

} else {
    
    client_area_logged_out_handler();
    
}