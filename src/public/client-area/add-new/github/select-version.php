<?php

require("../../../../includes/script_start.php");
require("../../../../includes/github_api.php");

// If logged in, show client area.
// If not logged in, redirect to login page.

if(LOGGED_IN) {

    $url = $_GET["url"];

    // Check if URL is set
    if(empty($url)) {
        header("Location: ./?error=Please enter valid URL.");
        die();
    } else {
        // Validate URL
        if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
            header("Location: ./?error=Please enter valid URL.");
            die();
        } else {
            // Check that it's from `github.com` and that it's "https".
            $parse = parse_url($url);
            if($parse['host'] === "github.com" && $parse["scheme"] === "https") {
                $path = explode("/", $parse["path"]);
                $owner = $path[1];
                $repo = $path[2];

                if(empty($owner)) {
                    header("Location: ./?error=Please enter valid URL.");
                    die();
                }

                if(empty($repo)) {
                    header("Location: ./?error=Please enter valid URL.");
                    die();
                }

                // Ask Github API about the repository
                try {
                    $github_api_response = get_repo($owner, $repo);
                } catch(GithubApiErrorException $e) {
                    header("Location: ./?error=" . $e->getMessage());
                    die();
                }

                // Ask Github API about releases
                $context = github_api_context();
                
                $github_api_url = explode("{", str_replace("{/id}", "", $github_api_response["releases_url"]))[0];
                $releases = @file_get_contents($github_api_url, false, $context);

                // If API returns an error
                if($releases === FALSE) {
                    // Redirect to previous page with error message
                    header("Location: ./?error=Error while fetching data from the API.&rel");
                    
                    // If some stupid "headers already sent" errors
                    echo '<meta http-equiv="refresh" content="0; url=./?error=Error while fetching data from the API.&rel">';
                    
                    // Show error if redirects disabled
                    echo "<b>ERROR</b>: Error while fetching data from the API. Make sure that the repo exists.";
                    
                    die();
                }

                $releases = json_decode($releases, TRUE);

                if(empty($releases[0])) {
                    header("Location: ./?error=Releases are not used by this repository.");
                    
                    die();
                }

                // echo "<pre>";
                // print_r($github_api_response);
                // print_r($releases);
                // echo "</pre>";
                // die();

                // TODO: Check that repo uses releases
                echo $twig->render('client-area/add-new/github/select-current-version.html', [
                    'repo' => "$owner/$repo",
                    'releases' => $releases /* ,
                    "DEBUG" => print_r($releases, true) */
                ]);
            } else {
                header("Location: ./?error=Please enter Github URL.");
                die();
            }
        }
    }

} else {
    
    client_area_logged_out_handler();
    
}