<?php
require_once __DIR__.'/../config/db_connect.php';

class TaskManager
{
    protected $conn;

    /**
     * Constructor to initialize the database connection.
     */
    public function __construct()
    {
        $this->conn = DatabaseFactory::createConnection();
    }

    /**
     * Create a task record for a user with a due date.
     *
     * @param int $userId The ID of the user who owns the task.
     * @param string $taskName The name of the task to be created.
     * @param string $dueDate The due date of the task in 'YYYY-MM-DD' format.
     * @return bool True if the task creation is successful, false otherwise.
     */
    public function createTask(int $userId, string $taskName, string $dueDate, int $assignerId = null): bool
    {
        $sql = "INSERT INTO tasks (user_id, assigner_id, task, due_date) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iiss", $userId, $assignerId, $taskName, $dueDate);

        return $stmt->execute();
    }

    /**
     * Delete a task record for a user.
     *
     * @param int $taskId The ID of the task to be deleted.
     * @return bool True if task deletion is successful, false otherwise.
     */
    public function deleteTask(int $userId, int $taskId): bool
    {
        $sql = "DELETE FROM tasks WHERE id = ? AND (user_id = ? OR assigner_id = ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iii", $taskId, $userId, $userId);
        return $stmt->execute();
    }

    /**
     * Fetch tasks for a specific user.
     *
     * @param int $userId The ID of the user whose tasks should be fetched.
     * @return array An array of tasks with 'id', 'task', 'due_date', 'status, assigner_id, assigner_username' for the given user.
     */
    public function getUserTasks(int $userId): array
    {

        $sql = "SELECT tasks.id, tasks.task, tasks.due_date, tasks.status, tasks.assigner_id, assigner.username AS assigner_username FROM (SELECT * FROM tasks WHERE tasks.user_id = ?) AS tasks LEFT JOIN users AS assigner ON tasks.assigner_id = assigner.id";


        $stmt = $this->conn->prepare($sql);
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


    /**
     * Mark a task as completed.
     *
     * @param int $taskId The ID of the task to mark as completed.
     * @return bool True if the task is marked as completed successfully, false otherwise.
     */
    public function markTaskCompleted(int $userId, int $taskId): bool
    {
        $sql = "UPDATE tasks SET status = 'completed' WHERE id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $taskId, $userId);
        return $stmt->execute();
    }

    /**
     * Delete all completed tasks for a specific user.
     * @param int $userId The ID of the user whose completed tasks will be deleted.
     * @return bool True if tasks were deleted successfully, false otherwise.
     */
    public function deleteCompletedTasks($userId)
    {
        $sql = "DELETE FROM tasks WHERE user_id = ? AND status = 'completed'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);

        return $stmt->execute();
    }

    public function changeTask(int $userId, $taskId, string $taskName)
    {
        $sql = "UPDATE tasks SET task = ? WHERE id = ? AND (user_id = ? OR assigner_id = ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("siii", $taskName, $taskId, $userId, $userId);
        return $stmt->execute();
    }

    

    public function changeDueDate(int $userId, $taskId, $dueDate)
    {
        $sql = "UPDATE tasks SET due_date = ? WHERE id = ? AND (user_id = ? OR assigner_id = ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("siii", $dueDate, $taskId, $userId, $userId);
        return $stmt->execute();
    }



    public function getAssignedTasks(int $assignerId, int $userId)
    {

        $sql = "SELECT * FROM tasks WHERE user_id = ? AND assigner_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $assignerId);
        $stmt->execute();
        $result = $stmt->get_result();

        $tasks = [];
        while ($row = $result->fetch_assoc()) {
            $tasks[] = $row;
        }

        $stmt->close();
        return $tasks;
    }
}
