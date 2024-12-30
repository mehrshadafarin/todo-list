<?php
require_once __DIR__.'/../models/user.php';

class TaskController
{
    public function tasks()
    {
        $user = UserFactory::getInstance();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {


            switch ($_POST['action']) {
                case 'add':
                    $taskName = $_POST['task_name'];
                    $dueDate = $_POST['due_date'];
                    $user->createTask($taskName, $dueDate);
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
                case 'complete':
                    $taskId = $_POST['task_id'];
                    $user->markTaskCompleted($taskId);
                    break;
                case 'delete':
                    $taskId = $_POST['task_id'];
                    $user->deleteTask($taskId);
                    break;
                default:
                    break;
            }

        }
        $this->showTasks();
    }




    private function showTasks()
    {
        $user = UserFactory::getInstance();
        $tasks = $user->getUserTasks();
        $pairs = $user->getPairs();
        require_once 'views/tasks.php';
        exit();
    }

}
