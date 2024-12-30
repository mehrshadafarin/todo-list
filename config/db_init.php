<?php
require __DIR__.'/../vendor/autoload.php';



$dotenv = Dotenv\Dotenv::createImmutable(__DIR__."/../");
$dotenv->load();

$host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_NAME'];
$user = $_ENV['DB_USER'];
$pass = $_ENV['DB_PASS'];

try {
    // Connect to MySQL
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create the database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
    $pdo->exec("USE `$dbname`");

    // Read and execute the SQL file
    $sql = file_get_contents('./models/schema.sql');
    $pdo->exec($sql);

    echo "Database and table created successfully.";
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

?>