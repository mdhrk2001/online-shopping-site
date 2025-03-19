<?php

$serverName = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "gmi_db";

// Create connection using mysqli object-oriented approach
$conn = new mysqli($serverName, $dbUsername, $dbPassword, $dbName);

// Check connection and throw an exception if it fails
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    // Optionally, you can set the character set to UTF-8 for better compatibility with different languages
    $conn->set_charset("utf8mb4");
}

?>
