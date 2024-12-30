<?php



class DatabaseConnection
{
    private static ?DatabaseConnection $instance = null; // Singleton instance
    private ?mysqli $connection = null; // The actual database connection

    // Private constructor to prevent direct instantiation
    private function __construct()
    {
        $env = require_once 'loadEnv.php';
        $host = $env['host'];
        $dbname = $env['dbname'];
        $user = $env['user'];
        $pass = $env['pass'];


        // Create a new database connection
        $this->connection = new mysqli($host, $user, $pass, $dbname);

        // Check for connection errors
        if ($this->connection->connect_error) {
            error_log("Database connection error: " . $this->connection->connect_error);
            die("A database connection issue occurred. Please try again later.");
        }
    }

    // Get the singleton instance of the class
    public static function getInstance(): DatabaseConnection
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Get the database connection
    public function getConnection(): mysqli
    {
        return $this->connection;
    }

    // Close the connection when the object is destroyed
    public function __destruct()
    {
        if ($this->connection !== null) {
            $this->connection->close();
        }
    }

    // Prevent cloning of the singleton instance
    private function __clone()
    {
    }

    // Prevent unserialization of the singleton instance
    public function __wakeup()
    {
    }
}

// Factory for creating the database connection
class DatabaseFactory
{
    public static function createConnection(): mysqli
    {
        $dbInstance = DatabaseConnection::getInstance(); // Get singleton instance
        return $dbInstance->getConnection(); // Return the actual connection
    }
}

?>