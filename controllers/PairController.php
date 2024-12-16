<?php
require_once 'models/user.php';

class PairController
{

    public function addPair()
    {
        $user = UserFactory::getInstance();
        $userName = $_POST['username'];
        $user->createPair($userName);
        header('Location: /tasks');
        exit();

    }
    public function deletePair()
    {
        $user = UserFactory::getInstance();
        $user = UserFactory::getInstance();
        $pairId = $_POST['pair_id'];
        $user->deletePair($pairId);
        header('Location: /tasks');
        exit();
    }
    public function pairTasks()
    {
        $user = UserFactory::getInstance();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_POST['pair_user_id'];

            switch ($_POST['action']) {

                case 'add':
                    $taskName = $_POST['task_name'];
                    $dueDate = $_POST['due_date'];
                    $user->assignTask($userId, $taskName, $dueDate);
                    break;
                case 'update_time':
                    $dueDate = $_POST['due_date'];
                    $taskId = $_POST['task_id'];
                    $user->changeDueDate($taskId, $dueDate);
                    break;
                case 'update_task':
                    $taskName = $_POST['task_name'];
                    $taskId = $_POST['task_id'];
                    $user->changeTask($taskId, $taskName);
                    break;
                case 'delete':
                    $taskId = $_POST['task_id'];
                    $user->deleteTask($taskId);
                    break;
                case 'add_pair':
                    $this->addPair();
                    break;
                case 'delete_pair':
                    $this->deletePair();
                    break;
                default:
                    break;
            }

        }
        $this->showPairTasks();

    }


    private function showPairTasks()
    {
        $pairUserId = $_POST['pair_user_id'];
        $pairUserName = $_POST['pair_username'];
        
        $user = UserFactory::getInstance();
        $tasks = $user->getAssignedTasks($pairUserId);
        require_once 'views/pairTasks.php';
        exit();
    }
}
