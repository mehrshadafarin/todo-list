<?php
require_once 'private/encryption.php';

session_start();


if (isset($_COOKIE['user_data'])) {
    $decrypted_user_id = decrypt_cookie($_COOKIE['user_data']);
    
    if ($decrypted_user_id) {
        header('Location: tasks.php');
        exit();
    }
}

header('Location: login.php');
exit();
?>
