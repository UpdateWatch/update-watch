<?php

class GithubApiErrorException extends Exception {}

// Github API context
// https://secure.php.net/context
function github_api_context() {
    // Create a stream
    // https://stackoverflow.com/a/2107792/6451184
    $opts = [
        "http" => [
            "method" => "GET",
            "header" => "User-Agent: UpdateWatch2\r\n" .
                "Accept: application/vnd.github.v3+json\r\n" .
                "Authorization: token " . GITHUB_KEY . "\r\n" .
                "Time-Zone: UTC"
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
    if($github_api_response === FALSE) {
        throw new GithubApiErrorException("Error while fetching data from the API. Make sure that the repository exists.");
    }

    $github_api_response = json_decode($github_api_response, TRUE);

    return $github_api_response;
}

function get_release($owner, $repo, $version) {
    $context = github_api_context();

    $github_api_response = get_repo($owner, $repo);

    $github_api_url = str_replace("{/id}", "/$version", $github_api_response["releases_url"]);
    $release = @file_get_contents($github_api_url, false, $context);

    // If API returns an error
    if($release === FALSE) {
        throw new GithubApiErrorException("Can't fetch release");
    }

    $release = json_decode($release, TRUE);

    return $release;
}

// Used for update & add scripts
function github_release_name_generator($data) {
    if($data["name"]) {
        $name = $data["name"] . "(" . $data["tag_name"] . ")";
    } else {
        $name = $data["tag_name"];
    }

    return $name;
}