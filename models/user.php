<?php
require_once 'db_connect.php';
require_once 'task_manager.php';
require_once 'user_manager.php';
require_once 'pair_manager.php';

class User extends UserManager
{
    private TaskManager $taskManager;
    private pairManager $pairManager;
    private $userId;
    private $username;
    private $password;
    /**
     * creates an instance of user.
     * should either pass (username and password) or (userId) to work
     * @param string $username
     * @param string $password
     * @param int $userId
     */
    public function __construct($username = '', $password = '', $userId = null)
    {
        parent::__construct();
        $this->taskManager = new TaskManager();
        $this->pairManager = new pairManager();
        $this->userId = $userId;
        $this->username = $username;
        $this->password = $password;

        if ($userId) {

        }

    }

    private function getUserId()
    {
        return $this->userId;
    }

    /**
     * Log in a user by verifying username and password.
     * @return int -1 if user does not exist, 0 if wrong password, 1 if successful login
     */
    private function login()
    {
        $result = parent::loginUser($this->username, $this->password);
        if ($result) {
            if ($result['status']) {
                $this->userId = $result['user_id'];
                return 1;
            } else {
                return 0;
            }
        } else {
            return -1;
        }

    }

    /**
     * Create user using UserManager's createUser method
     * @return bool
     */
    private function create()
    {
        $result = parent::createUser($this->username, $this->password);
        if ($result['status']) {
            $this->userId = $result['user_id'];
            return true;
        }
        return false;
    }

    /**
     * Delete user using UserManager's deleteUser method.
     * This also deletes associated tasks.
     */
    private function delete()
    {
        return parent::deleteUser($this->userId);
    }

    /**
     * Get tasks for a user using TaskManager's getUserTasks method
     */
    private function getUserTasks()
    {
        return $this->taskManager->getUserTasks($this->userId);
    }

    /**
     * Add a task for a user using TaskManager's createTask method
     */
    private function createTask($taskName, $dueDate)
    {
        return $this->taskManager->createTask($this->userId, $taskName, $dueDate);
    }


    /**
     * Delete completed tasks for a user using TaskManager's deleteCompletedTasks method
     */
    private function deleteCompletedTasks()
    {
        return $this->taskManager->deleteCompletedTasks($this->userId);
    }

    public function deleteTask(int $taskId): bool
    {
        return $this->taskManager->deleteTask($this->userId, $taskId);
    }


    public function markTaskCompleted(int $taskId): bool
    {
        return $this->taskManager->markTaskCompleted($this->userId, $taskId);
    }

    public function changeTask($taskId, string $taskName)
    {
        return $this->taskManager->changeTask($this->userId, $taskId, $taskName);
    }

    public function changeDueDate($taskId, $dueDate)
    {
        return $this->taskManager->changeDueDate($this->userId, $taskId, $dueDate);
    }


    private function assignTask($userId, $taskName, $dueDate)
    {
        return $this->taskManager->createTask($userId, $taskName, $dueDate, $this->userId);
    }


    private function getAssignedTasks($userId)
    {
        return $this->taskManager->getAssignedTasks($this->userId, $userId);
    }

    private function createPair(string $username)
    {

        return $this->pairManager->createPair($this->userId, $username);

    }

    private function deletePair(int $pairId)
    {
        return $this->pairManager->deletePair($pairId);
    }

    private function getPairs()
    {

        return $this->pairManager->getpairs($this->userId);
    }

}







class UserFactory
{
    private static $userInstance = null;

    private function __construct()
    {
    }

    // Public static method to provide the single instance of User
    public static function getInstance($username = '', $password = '', $userId = null)
    {
        if (self::$userInstance === null) {
            if ($username != '' && $password != '') {
                self::$userInstance = new User($username, $password);
            } else if ($userId != null) {
                self::$userInstance = new User(userId: $userId);
            } else {
                throw new Exception("First call to getInstance() must include a (username and password) or (userId).");
            }

        }
        return self::$userInstance;
    }
}

?>