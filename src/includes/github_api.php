<?php

// Github API context
// https://secure.php.net/context
function github_api_context() {
    // Create a stream
    // https://stackoverflow.com/a/2107792/6451184
    $opts = [
        "http" => [
            "method" => "GET",
            "header" => "User-Agent: UpdateWatch\r\n" .
                "Accept: application/vnd.github.v3+json"
            // FIXME: Authentication for rate limit
        ]
    ];
    $context = stream_context_create($opts);

    return $context;
}


function get_repo($owner, $repo) {
    $context = github_api_context();

    $github_api_url = "https://api.github.com/repos/$owner/$repo";
    $github_api_response = @file_get_contents($github_api_url, false, $context);

    // If API returns an error
    // TODO: Make exception so it's handled elsewhere
    if($github_api_response === FALSE) {
        // Redirect to previous page with error message
        header("Location: ./?error=Error while fetching data from the API. Make sure that the repository exists.&repo");
        
        // If some stupid "headers already sent" errors
        echo '<meta http-equiv="refresh" content="0; url=./?error=Error while fetching data from the API. Make sure that the repository exists.&repo">';
        
        // Show error if redirects disabled
        echo "<b>ERROR</b>: Error while fetching data from the API. Make sure that the repo exists.";
        
        die();
    }

    $github_api_response = json_decode($github_api_response, TRUE);

    return $github_api_response;
}