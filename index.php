<?php
require_once 'controllers/UserController.php';

// Create an instance of UserController
$userController = new UserController();

if ($userController->checkLogin()) {
    if ($_SERVER['REQUEST_URI'] == '/tasks') {
        $userController->tasks();
    }

} else {
    $userController->login();
}
?>