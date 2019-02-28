<?php

require("../../../../includes/script_start.php");
require("../../../../includes/github_api.php");
require("../../../../includes/database.php");

// If logged in, show client area.
// If not logged in, redirect to login page.

if(LOGGED_IN) {

    function name_generator($data) {
        if($data["name"]) {
            $name = $data["name"] . "(" . $data["tag_name"] . ")";
        } else {
            $name = $data["tag_name"];
        }

        return $name;
    }

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
    $running_name = name_generator($running);

    // FIXME: Get latest non-preview version
    $latest = get_release($owner, $repo, $version);
    $latest_unix = strtotime($latest["published_at"]);
    $latest_name = name_generator($latest);

    // echo "<pre>";
    // print_r($running);


    // Open MySQL connection
    $connection = getConnection();

    $query = $connection->prepare("INSERT INTO `watchers` (`owner`, `backend`, `subject`, `url`, " . 
        "`latest_version_number`, `latest_version_text`, `latest_version_url`, " .
        "`running_version_number`, `running_version_text`, `running_version_url`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);");

    // TODO: Make "last executed" to the DB for cronjob
    $query->execute(array(USER_ID, "github", "$owner/$repo", "https://github.com/$owner/$repo",
        $latest_unix, $latest_name, $latest["html_url"],
        $running_unix, $running_name, $running["html_url"]));

    header("Location: /client-area/?msg=Watcher has been successfully added.");

} else {
    
    client_area_logged_out_handler();
    
}