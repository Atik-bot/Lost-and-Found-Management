<?php
// Start a new session or resume the existing one
session_start();

// Database credentials
$db_host = 'localhost';
$db_user = 'root'; // Your database username
$db_pass = '';     // Your database password
$db_name = 'lost_and_found';

// Create a new MySQLi connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check if the connection failed
if ($conn->connect_error) {
    // Terminate the script and display an error message
    die("Connection failed: " . $conn->connect_error);
}
?>
