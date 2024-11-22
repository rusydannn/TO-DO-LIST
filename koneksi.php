<?php

function getConnection(): mysqli{
    $host = 'localhost';
    $db_name = 'todo_pert4';
    $username = 'root';
    $password = '';
    $conn = new mysqli(hostname: $host, username: $username, password: $password, database: $db_name);

    if($conn->connect_error) {
        die("Connection Error: " . $conn->connect_error);
    }
    return $conn;
}
?>