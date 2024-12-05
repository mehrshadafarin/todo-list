<?php
require_once 'env_loader.php';

function initializeDatabase() {
    // Load environment variables
    $env = loadEnv();
    $host = $env['DB_HOST'];
    $dbname = $env['DB_NAME'];
    $user = $env['DB_USER'];
    $pass = $env['DB_PASS'];

    try {
        // Connect to MySQL
        $pdo = new PDO("mysql:host=$host", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create the database if it doesn't exist
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
        $pdo->exec("USE `$dbname`");

        // Read and execute the SQL file
        $sql = file_get_contents('./private/schema.sql');
        $pdo->exec($sql);

        echo "Database and table created successfully.";
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>
