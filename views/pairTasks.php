<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>To Do List</title>
    <link rel="stylesheet" href="views/pairTasks.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>

<body>

    <h1><?php echo htmlspecialchars($pairUserName); ?></h1>

    <div class="box">
        <form class="item" action="/pair" method="post">
            <input type="hidden" name="pair_username" value="<?php echo htmlspecialchars($pairUserName); ?>">
            <input type="hidden" name="pair_user_id" value="<?php echo htmlspecialchars($pairUserId); ?>">
            <input type="hidden" name="action" value="add">
            <input type="text" name="task_name" placeholder="New Item" autocomplete="off" required>
            <input type="text" class="due_date" name="due_date" placeholder="Due Date" required>
            <button class="btn" type="submit">+</button>
        </form>



        <?php foreach ($tasks as $task): ?>
            <form action="/pair" method="post">
                <div class="item">
                    <input type="hidden" name="pair_username" value="<?php echo htmlspecialchars($pairUserName); ?>">
                    <input type="hidden" name="pair_user_id" value="<?php echo htmlspecialchars($pairUserId); ?>">
                    <input type="hidden" name="action" value="">
                    <input type="button" disabled="true" value="<?php echo htmlspecialchars($task['status']); ?>">
                    <input type="checkbox" onchange="setAction('delete'); this.form.submit()">
                    <input type="hidden" name="task_id" value="<?php echo htmlspecialchars($task['id']); ?>">
                    <input type="text" name="task_name" value="<?php echo htmlspecialchars($task['task']); ?>"
                        onchange="setAction('update_task'); this.form.submit()">
                    <input type="string" class="due_date" name="due_date"
                        value="<?php echo htmlspecialchars($task['due_date']); ?>"
                        onchange="setAction('update_time'); this.form.submit()">
                </div>
            </form>
        <?php endforeach; ?>

    </div>


    <script src="views/pairTasks.js"></script>
</body>

</html>