<?php
require_once 'private/db_functions.php';
require_once 'private/encryption.php';


session_start();

if (!isset($_COOKIE['user_data'])) {
    
    header('Location: login.php');
    exit();
}
$userId = decrypt_cookie($_COOKIE['user_data']);
$tasks = getUserTasks($userId);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['task_name'])) {
        $taskName = $_POST['task_name'];
        createTask($userId, $taskName);
        header('Location: tasks.php');
        exit();
    } elseif (isset($_POST['task_id'])) {
        $taskId = $_POST['task_id'];
        deleteTask($taskId);
        header('Location: tasks.php');
        exit();
    }
}
?>


<?php include("assets/header.php"); ?>

<div class="box" id="heading">
    <h1>
        <?php echo htmlspecialchars("To Do"); ?>
    </h1>
</div>


<div class="box">
    <form class="item" action="tasks.php" method="post">
        <input type="text" name="task_name" placeholder="New Item" autocomplete="off">
        <button class="btn" type="submit" name="list">+</button>
    </form>



    <?php foreach ($tasks as $item): ?>
        <form action="tasks.php" method="post">
            <div class="item">
                <input 
                    type="checkbox" 
                    name="task_id"
                    value="<?php echo htmlspecialchars($item['id']); ?>" 
                    onchange="this.form.submit()">
                <p>
                    <?php echo htmlspecialchars($item['task']); ?>
                </p>
            </div>
        </form>
    <?php endforeach; ?>
</div>
