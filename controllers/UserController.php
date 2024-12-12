<?php
require_once 'models/user.php';
require_once 'models/encryption.php';

class UserController
{
    // Check if the user is logged in by verifying the cookie
    public function checkLogin()
    {
        if (isset($_COOKIE['user_id'])) {
            if (decrypt($_COOKIE['user_id'])) {
                UserFactory::getInstance(userId: decrypt($_COOKIE['user_id']));
                return true;
            }
        }
        return false;
    }

    // Log in or create the user
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];


            $user = new User($username, $password);
            $result = $user->login();

            if ($result == 0) {
                $errorMessage = "Invalid credentials or user creation failed.";
                header('Location: /');
                include 'views/login.php';
                exit();
            }
            if ($result == -1) {
                if (!($user->create())) {
                    $errorMessage = "Invalid credentials or user creation failed.";
                    header('Location: /');
                    include 'views/login.php';
                    exit();
                }
            }
            setcookie('user_id', encrypt($user->getUserId()), time() + (86400 * 30), '/'); // Cookie valid for 30 days
            header('Location: /tasks');
            exit();
        }
        header('Location: /');
        include 'views/login.php';
        exit();


    }

    // Log out the user
    public function logout()
    {
        setcookie('user_id', '', time() - 3600, '/'); // Clear cookie
        header('Location: /');
        include 'views/login.php';
        exit();
    }

    // Show tasks page for logged-in user
    public function tasks()
    {

        $user = UserFactory::getInstance();
        $tasks = $user->getUserTasks();



        require_once 'views/tasks.php'; // Display the tasks page
        exit();
    }
}
