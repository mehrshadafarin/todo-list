<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>To Do List</title>
    <link rel="stylesheet" href="view/styles.css">
</head>

<body>
    <div class="box" id="heading">
        <h1><?php echo htmlspecialchars("To Do"); ?></h1>
    </div>

    <div class="box">
        <form class="item" action="tasks.php" method="post">
            <input type="text" name="task_name" placeholder="New Item" autocomplete="off">
            <button class="btn" type="submit">+</button>
        </form>

        <?php foreach ($tasks as $task): ?>
            <form action="tasks.php" method="post">
                <div class="item">
                    <input type="checkbox" name="task_id" value="<?php echo htmlspecialchars($task['id']); ?>"
                        onchange="this.form.submit()">
                    <p><?php echo htmlspecialchars($task['task']); ?></p>
                </div>
            </form>
        <?php endforeach; ?>
    </div>
</body>
</html>
