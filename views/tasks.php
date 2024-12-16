<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>To Do List</title>
    <link rel="stylesheet" href="views/tasks.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>

<body>

    <div class="box">
        <form class="item" action="/tasks" method="post">
            <input type="hidden" name="action" value="add">
            <input type="text" name="task_name" placeholder="New Item" autocomplete="off" required>
            <input type="text" class="due_date" name="due_date" placeholder="Due Date" required>
            <button class="btn" type="submit">+</button>
        </form>



        <?php foreach ($tasks as $task): ?>
            <form action="/tasks" method="post">
                <div class="item">
                    <input type="hidden" name="action" value="">
                    <input type="checkbox" onchange="setAction('delete'); this.form.submit()">
                    <input type="button" name="status" value="<?php echo htmlspecialchars($task['status']); ?>"
                        onclick="setAction('complete'); this.form.submit()">
                    <input type="hidden" name="task_id" value="<?php echo htmlspecialchars($task['id']); ?>">
                    <input type="text" name="task_name" value="<?php echo htmlspecialchars($task['task']); ?>"
                        onchange="setAction('update_task'); this.form.submit()">
                    <input type="string" class="due_date" name="due_date"
                        value="<?php echo htmlspecialchars($task['due_date']); ?>"
                        onchange="setAction('update_time'); this.form.submit()">
                    <p><?php echo htmlspecialchars($task['assigner_username']); ?></p>
                </div>
            </form>
        <?php endforeach; ?>

    </div>



    <div class="pairs_box">
        <?php foreach ($pairs as $pair): ?>
            <form action="/pair" method="post">
                <div class="item">
                    <input type="hidden" name="action" value="">
                    <input type="button" value="delete" onclick="setAction('delete_pair'); this.form.submit()">
                    <input type="hidden" name="pair_id" value="<?php echo htmlspecialchars($pair['id']); ?>">
                    <input type="hidden" name="pair_user_id" value="<?php echo htmlspecialchars($pair['user_id']); ?>">
                    <input type="submit" name="pair_username" value="<?php echo htmlspecialchars($pair['username']); ?>">
                </div>
            </form>
        <?php endforeach; ?>

        <form action="/pair" method="post">
            <h3>add a new pair</h>
                <input type="hidden" name="action" value="add_pair">
                <input type="text" name="username" placeholder="username" autocomplete="off" required>
                <button class="btn" type="submit">+</button>
        </form>

    </div>


    <script src="views/tasks.js"></script>
</body>

</html>