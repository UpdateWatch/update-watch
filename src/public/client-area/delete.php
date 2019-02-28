<?php

require("../../includes/script_start.php");
require("../../includes/database.php");

// If logged in, show client area.
// If not logged in, redirect to login page.

if(LOGGED_IN) {

    $id = $_GET["id"];

    if(!is_numeric($id)) {
        header("Location: /client-area/?error=Watcher ID is empty or isn't numeric.");
        die();
    }

    // Open MySQL connection
    $connection = getConnection();

    $user_id = USER_ID;

    // Prepare SQL query
    $query = $connection->prepare("SELECT * FROM watchers WHERE owner = ? AND id = ?");
    $query->execute(array(
        $user_id,
        $id,
    ));

    $result = $query->fetch();

    if(isset($result["id"]) && !empty($result["id"])) {
        $query = $connection->prepare("DELETE FROM watchers WHERE owner = ? AND id = ?");
        $query->execute(array(
            $user_id,
            $id,
        ));

        header("Location: /client-area/?msg=Watcher has been deleted.");
        die();
    } else {
        header("Location: /client-area/?error=Watcher not found.");
        die();
    }

} else {
    
    client_area_logged_out_handler();
    
}