<?php

require_once '../../includes/database.php';

$username = USERNAME;

// Open MySQL connection

$connection = getConnection();

// Send query

$query = $connection->prepare("SELECT * FROM watchers WHERE owner = ?");
$query->execute(array(
    $username
));

$data = array();

while ($watcher_data = $query->fetch()) {
    $latest_status = $watcher_data["running_version_text"] == $watcher_data["latest_version_text"];

    $watcher = array(
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
