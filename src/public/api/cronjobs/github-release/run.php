<?php

require("../../../../includes/api_start.php");
require("../../../../includes/github_api.php");


api_start();

// Watchers that need updating
$watchers = get_all_watchers("github-release");

$counter = 0;

foreach($watchers as $watcher) {
    $repository = $watcher["subject"];
    $owner = explode("/", $repository)[0];
    $repo = explode("/", $repository)[1];

    try {
        $latest = get_release($owner, $repo, "latest");
    } catch(GithubApiErrorException $e) {
        if($e->getMessage() == "Error while fetching data from the API. Make sure that the repository exists.") {
            // Repo probably deleted
            continue;
        } else {
            throw $e;
        }
    }
    
    $latest_unix = strtotime($latest["published_at"]);
    $latest_name = github_release_name_generator($latest);

    // Ignore if current latest version in DB
    // aka no database update needed
    if($latest_unix == $watcher["latest_version"]["number"]) {
        continue;
    }

    // If update is needed:


    // Open MySQL connection
    $connection = getConnection();

    $query = $connection->prepare(
        "UPDATE watchers SET latest_version_number = ?, latest_version_text = ?, latest_version_url = ?, last_updated = ? WHERE id = ?"
    );

    $query->execute(array(
        $latest_unix, $latest_name, $latest["html_url"], time(), $watcher["id"], 
    ));

    $counter++;
}

echo json_encode(
    array(
        "objects_updated" => $counter
    )
);

?>