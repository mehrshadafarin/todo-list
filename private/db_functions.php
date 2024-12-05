<?php
require_once 'db_connect.php';

/**
 * Login or create a user with a hashed password.
 * @param string $username
 * @param string $password (plain text)
 * @return array ['status' => bool, 'message' => string, 'user_id' => int|null]
 */
function loginOrCreateUser($username, $password) {
    global $conn;

    // Check if the user exists
    $sql = "SELECT id, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User exists, verify the password
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Successful login
            return ['status' => true, 'message' => 'Login successful.', 'user_id' => $user['id']];
        } else {
            // Incorrect password
            return ['status' => false, 'message' => 'Incorrect password.', 'user_id' => null];
        }
    } else {
        // User does not exist, create the user
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $hashedPassword);

        if ($stmt->execute()) {
            $userId = $stmt->insert_id; // Get the newly created user ID
            return ['status' => true, 'message' => 'User created successfully.', 'user_id' => $userId];
        } else {
            return ['status' => false, 'message' => 'Error creating user.', 'user_id' => null];
        }
    }
}

/**
 * Create a task record for a user.
 * @param int $userId
 * @param string $taskName
 * @return bool True if task creation is successful, false otherwise.
 */
function createTask($userId, $taskName) {
    global $conn;

    $sql = "INSERT INTO tasks (user_id, task) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $userId, $taskName);
    return $stmt->execute();
}

/**
 * Delete a task record for a user.
 * @param int $taskId
 * @return bool True if task deletion is successful, false otherwise.
 */
function deleteTask($taskId) {
    global $conn;

    $sql = "DELETE FROM tasks WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $taskId);
    return $stmt->execute();
}

/**
 * Delete a user and all associated tasks.
 * @param int $userId
 * @return bool True if user deletion is successful, false otherwise.
 */
function deleteUser($userId) {
    global $conn;

    // Start transaction
    $conn->begin_transaction();

    try {
        // Delete tasks associated with the user
        $sql = "DELETE FROM tasks WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();

        // Delete the user
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();

        // Commit transaction
        $conn->commit();
        return true;
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $conn->rollback();
        return false;
    }
}

/**
 * Fetch tasks for a specific user.
 * @param int $userId
 * @return array An array of tasks with 'id' and 'task_name' for the given user.
 */
function getUserTasks($userId) {
    global $conn;

    $sql = "SELECT id, task FROM tasks WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $tasks = [];
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }

    $stmt->close();
    return $tasks;
}
?>
