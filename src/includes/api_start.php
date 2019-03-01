<?php

require(__DIR__ . "/script_start.php");
require(__DIR__ . "/data.php");

function api_start() {
    header("Content-Type: application/json; charset=utf-8");
}

/*
 * Update if older than X seconds
 */
define('UPDATE_IF_OLDER_THAN', '60');
// FIXME: update to realistic stuff in prod



function get_all_watchers($backend) {
    $watchers = get_data_by_backend($backend);

    $final_watchers = array();

    foreach ($watchers as $watcher) {
        // Ignore watchers that were last updated less than UPDATE_IF_OLDER_THAN seconds ago
        if($watcher["updated_ago"] > UPDATE_IF_OLDER_THAN) {
            array_push($final_watchers, $watcher);
        } else {
            continue;
        }
    }

    return $final_watchers;
}