<?php
require_once 'controllers/UserController.php';
require_once 'controllers/TaskController.php';
require_once 'controllers/PairController.php';

// Create an instance of UserController
$userController = new UserController();
$taskController = new TaskController();
$pairController = new PairController();


$result = $userController->checkLogin();
if ($result) {
    switch ($_SERVER['REQUEST_URI']) {
        case '/':
            $taskController->tasks();
            break;
        case '/login':
            $userController->login();
            break;
        case '/logout':
            $userController->logout();
            break;
        case '/delete':
            $userController->deleteAccount();
            break;
        case '/tasks':
            $taskController->tasks();
            break;
        case '/pair':
            $pairController->pairTasks();
            break;
        case '/pair/add':
            $pairController->addPair();
            break;
        case '/pair/delete':
            $pairController->deletePair();
            break;
        default:
            break;
    }

} else {
    $userController->login();
}
?>