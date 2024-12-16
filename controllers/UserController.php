<?php
require_once 'models/user.php';
require_once 'models/encryption.php';

class UserController
{
    // Check if the user is logged in by verifying the cookie
    public function checkLogin()
    {
        if (isset($_COOKIE['user_id'])) {
            $user = UserFactory::getInstance(userId: decrypt($_COOKIE['user_id']));
            if ($user->checkId()) {

                return true;
            } else {
                setcookie('user_id', '', time() - 3600, '/'); // Clear cookie
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


            $user = UserFactory::getInstance($username, $password);
            $result = $user->login();

            if ($result == 0) {
                $errorMessage = "Invalid credentials or user creation failed.";
                include 'views/login.php';
                exit();
            } else if ($result == -1) {
                $user->create();
                setcookie('user_id', encrypt($user->getUserId()), time() + (86400 * 30), '/'); // Cookie valid for 30 days
                header('Location: /tasks');
                exit();
            } else {
                setcookie('user_id', encrypt($user->getUserId()), time() + (86400 * 30), '/'); // Cookie valid for 30 days
                header('Location: /tasks');
                exit();
            }
        }
        include 'views/login.php';
        exit();


    }

    // Log out the user
    public function logout()
    {
        setcookie('user_id', '', time() - 3600, '/'); // Clear cookie
        header('Location: /');
        exit();
    }


    public function deleteAccount()
    {
        $user = UserFactory::getInstance();
        $user->delete();
        setcookie('user_id', '', time() - 3600, '/'); // Clear cookie
        header('Location: /');
        exit();
    }

}
