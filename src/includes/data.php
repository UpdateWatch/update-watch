<?php

require_once __DIR__ . '/database.php';

function data_formatter($watcher_data) {
    $latest_status = $watcher_data["latest_version_number"] == $watcher_data["running_version_number"];

    $updated_ago = time() - $watcher_data["last_updated"];

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

        "latest" => $latest_status,
        
        "last_updated" => $watcher_data["last_updated"],
        "updated_ago" => $updated_ago
    );

    return $watcher;
}

function get_data_by_owner($owner) {
    // Open MySQL connection
    
    $connection = getConnection();
    
    // Send query

    $query = $connection->prepare("SELECT * FROM watchers WHERE owner = ?");
    $query->execute(array(
        $owner
    ));

    $data = array();

    while ($watcher_data = $query->fetch()) {
        $watcher = data_formatter($watcher_data);

        array_push($data, $watcher);
    }

    return $data;
}

function get_data_by_backend($backend) {
    // Open MySQL connection
    
    $connection = getConnection();
    
    // Send query

    $query = $connection->prepare("SELECT * FROM watchers WHERE backend = ?");
    $query->execute(array(
        $backend
    ));

    $data = array();

    while ($watcher_data = $query->fetch()) {
        $watcher = data_formatter($watcher_data);

        array_push($data, $watcher);
    }

    return $data;
}