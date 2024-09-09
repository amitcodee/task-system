<?php
// config.php

// Database configuration
$host = 'localhost';       // Your database host (e.g., localhost)
$dbname = 'task_management'; // Your database name
$username = 'root';         // Your database username
$password = '';             // Your database password

// Try to establish a connection using PDO (PHP Data Objects)
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If connection fails, show the error message
    die("Connection failed: " . $e->getMessage());
}

// Additional global settings can be added here, like timezone, session, etc.
date_default_timezone_set('UTC'); // Set the default timezone
session_start(); // Start a session for user authentication and session data handling

?>
