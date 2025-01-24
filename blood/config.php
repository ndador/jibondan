<?php
$hostname = "http://localhost/blood"; // Adjust your hostname if needed
$dbHost = "localhost";
$dbUser = "root"; // Your database username
$dbPass = "";     // Your database password
$dbName = "blood"; // Your database name

// Create the connection
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
?>
