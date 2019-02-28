<?php

require_once '../../includes/database.php';

$user_id = USER_ID;

// Open MySQL connection

$connection = getConnection();

// Send query

$query = $connection->prepare("SELECT * FROM watchers WHERE owner = ?");
$query->execute(array(
    $user_id
));

$data = array();

while ($watcher_data = $query->fetch()) {
    $latest_status = $watcher_data["latest_version_number"] == $watcher_data["running_version_number"];

    $watcher = array(
        "id" => $watcher_data["id"],
        "backend" => $watcher_data["backend"],
        "subject" => $watcher_data["subject"],
        "url" => $watcher_data["url"],

        "latest_version" => array(
            "number" => $watcher_data["latest_version_number"],
            "text" => $watcher_data["latest_version_text"],
            "url" => $watcher_data["latest_version_url"],
        ),

        "running_version" => array(
            "number" => $watcher_data["running_version_number"],
            "text" => $watcher_data["running_version_text"],
            "url" => $watcher_data["running_version_url"],
        ),

        "latest" => $latest_status
    );

    array_push($data, $watcher);
}
