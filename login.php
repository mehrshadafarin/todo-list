<?php
require_once 'private/encryption.php';
require_once 'private/db_functions.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = loginOrCreateUser($username, $password);

    if ($result['status']) {
        $encrypted_user_id = encrypt_cookie($result['user_id']);
        setcookie('user_data', $encrypted_user_id, time() + (86400 * 30), '/'); // Cookie valid for 30 days
        header('Location: tasks.php');
        exit();
    } else {
        $errorMessage = $result['message'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Register</title>
    <link rel="stylesheet" href="assets/login.css">
</head>
<body>
    <div class="container">
        <h1>Login or Create Account</h1>
        <?php if (!empty($errorMessage)): ?>
            <p class="error"><?= htmlspecialchars($errorMessage) ?></p>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit" class="btn">Login / Register</button>
        </form>
    </div>
</body>
</html>
