<?php

function getConnection() {
    try {
        $sql_host = SQL_HOST;
        $sql_database = SQL_DATABASE;
        $sql_user = SQL_USER;
        $sql_pwd = SQL_PASSWORD;

        $connection = new PDO("mysql:host=$sql_host;dbname=$sql_database", $sql_user, $sql_pwd);
    } catch (PDOException $e) {
        // TODO: Improve error handling
        die("ERROR: " . $e->getMessage());
    }

    // $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // $connection->exec("SET NAMES utf8");

    return $connection;
}