<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>To Do List</title>
    <!-- <link rel="stylesheet" href="views/styles.css"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>

<body>
    <div class="box" id="heading">
        <h1><?php echo htmlspecialchars("To Do"); ?></h1>
    </div>

    <div class="box">
        <form class="item" action="/tasks" method="post">
            <input type="hidden" name="action" value="add">
            <input type="text" name="task_name" placeholder="New Item" autocomplete="off">
            <input type="text" class="due_date" name="due_date" placeholder="Due Date" required>
            <button class="btn" type="submit">+</button>
        </form>



        <?php foreach ($tasks as $task): ?>
            <form action="/tasks" method="post">
                <div class="item">
                    <input type="checkbox" onchange="this.form.submit()">
                    <input type="hidden" name="task_id" value="<?php echo htmlspecialchars($task['id']); ?>">
                    <input type="hidden" id="action_<?php echo htmlspecialchars($task['id']); ?>" name="action" value="delete">

                    <input type="text" name="task_name" value="<?php echo htmlspecialchars($task['task']); ?>"
                        onchange="setAction('<?php echo htmlspecialchars($task['id']); ?>', 'update_task'); this.form.submit()">

                    <input type="string" class="due_date" name="due_date" value="<?php echo htmlspecialchars($task['due_date']); ?>"
                        onchange="setAction('<?php echo htmlspecialchars($task['id']); ?>', 'update_time'); this.form.submit()">

                    <p><?php echo htmlspecialchars($task['assigner_username']); ?></p>
                </div>
            </form>
        <?php endforeach; ?>

        <script>
            function setAction(taskId, actionValue) {
                document.getElementById('action_' + taskId).value = actionValue;
            }
        </script>

    </div>



    <script>
        flatpickr(".due_date", {
            dateFormat: "Y-m-d",
            altInput: false,
        });
    </script>
</body>

</html>