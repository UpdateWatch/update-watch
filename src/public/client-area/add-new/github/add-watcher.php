<?php

require("../../../../includes/script_start.php");
require("../../../../includes/github_api.php");
require("../../../../includes/database.php");

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

    if(!is_numeric($version)) {
        die("Version is not a number.");
    }

    // Ask Github API about releases
    $owner = explode("/", $repository)[0];
    $repo = explode("/", $repository)[1];

    $running = get_release($owner, $repo, $version);
    $running_unix = strtotime($running["published_at"]);
    $running_name = github_release_name_generator($running);

    $latest = get_release($owner, $repo, "latest");
    $latest_unix = strtotime($latest["published_at"]);
    $latest_name = github_release_name_generator($latest);

    // Open MySQL connection
    $connection = getConnection();

    $query = $connection->prepare("INSERT INTO `watchers` (`owner`, `backend`, `subject`, `url`, " . 
        "`latest_version_number`, `latest_version_text`, `latest_version_url`, " .
        "`running_version_number`, `running_version_text`, `running_version_url`, `last_updated`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);");

    $query->execute(array(USER_ID, "github-release", "$owner/$repo", "https://github.com/$owner/$repo",
        $latest_unix, $latest_name, $latest["html_url"],
        $running_unix, $running_name, $running["html_url"],
        time()));

    header("Location: /client-area/?msg=Watcher has been successfully added.");

} else {
    
    client_area_logged_out_handler();
    
}