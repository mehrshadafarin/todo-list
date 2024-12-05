<?php
// Load configuration from .env file
require_once './private/env_loader.php'; // Ensure this path matches your project structure

// Database connection variables from the environment
$env = loadEnv();
$host = $env['DB_HOST'];
$dbname = $env['DB_NAME'];
$user = $env['DB_USER'];
$pass = $env['DB_PASS'];

// Establish connection to the database
$conn = new mysqli($host, $user, $pass, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    error_log(message: "Database connection error: " . $conn->connect_error);
    die("A database connection issue occurred. Please try again later.");
}
?>