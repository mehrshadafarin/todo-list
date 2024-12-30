<?php
require_once __DIR__.'/../config/db_connect.php';

class UserManager
{
    protected $conn;

    public function __construct()
    {
        // Initialize the database connection
        $this->conn = DatabaseFactory::createConnection();
    }

    public function checkUserId($userId)
    {
        $sql = "SELECT id FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Log in a user by verifying username and password.
     * @param string $username The username to log in.
     * @param string $password The plain-text password.
     * @return array|bool ['status' => bool , 'user_id' => int|null] or false if the user does not exist.
     */
    public function loginUser($username, $password)
    {
        $sql = "SELECT id, password FROM users WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                return ['status' => true, 'user_id' => $user['id']];
            } else {
                return ['status' => false, 'user_id' => null];
            }
        } else {
            return false;
        }
    }

    /**
     * Create a new user record in the database.
     * @param string $username The username for the new user.
     * @param string $password The plain-text password.
     * @return array ['status' => bool , 'user_id' => int|null]
     */
    public function createUser($username, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $username, $hashedPassword);

        if ($stmt->execute()) {
            return ['status' => true, 'user_id' => $stmt->insert_id];
        } else {
            return ['status' => false, 'user_id' => null];
        }
    }

    /**
     * Delete a user and all associated tasks from the database.
     * @param int $userId The ID of the user to delete.
     * @return bool True if the user was deleted successfully, false otherwise.
     */
    public function deleteUser($userId)
    {
        // Begin the transaction to ensure atomicity (both user and associated tasks are deleted)
        $this->conn->begin_transaction();

        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);

        if ($stmt->execute()) {
            $this->conn->commit();  // Commit the transaction
            return true;
        } else {
            $this->conn->rollback();  // Rollback if any error occurs
            return false;
        }
    }

}
?>