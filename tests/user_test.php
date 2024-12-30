<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../models/user.php';

class user_test extends TestCase
{
    private $user1;
    private $user2;

    protected function setUp(): void
    {

        // Create a new User instance
        $this->user1 = new User('testUser1', 'testPassword');
        $this->user2 = new User('testUser2', 'testPassword');
        // Ensure the user is created or logged in before running tests
        $this->user1->create();
        $this->user2->create();
    }

    protected function tearDown(): void
    {
        $this->user1->delete();
        $this->user2->delete();
    }

    public function testCreateAndDeleteTask()
    {
        // Create a task
        $taskName = "Test Task";
        $dueDate = "2025-01-01";
        $result = $this->user1->createTask($taskName, $dueDate);
        $this->assertTrue($result, "Task creation failed");

        // Retrieve tasks to ensure it was created
        $tasks = $this->user1->getUserTasks();
        $this->assertNotEmpty($tasks, "Task list is empty");
        $this->assertEquals($taskName, $tasks[0]['task'], "Task name mismatch");

        // Delete the task
        $taskId = $tasks[0]['id'];
        $deleteResult = $this->user1->deleteTask($taskId);
        $this->assertTrue($deleteResult, "Failed to delete the task");

        // Ensure the task is no longer in the task list
        $tasksAfterDeletion = $this->user1->getUserTasks();
        $this->assertEmpty($tasksAfterDeletion, "Task was not deleted");
    }



    public function testChangeTask()
    {
        // Create a task
        $taskName = "Task to Complete";
        $dueDate = "2025-03-01";
        $this->user1->createTask($taskName, $dueDate);

        // Mark task as completed
        $tasks = $this->user1->getUserTasks();
        $taskId = $tasks[0]['id'];


        $this->user1->changeTask($taskId, "task2");
        $this->user1->changeDueDate($taskId, "2025-03-02");
        $this->user1->markTaskCompleted($taskId);
        $tasks = $this->user1->getUserTasks();
        $this->assertEquals('completed', $tasks[0]['status'], 'task status did not change.');
        $this->assertEquals('task2', $tasks[0]['task'], 'task did not change.');
        $this->assertEquals('2025-03-02', $tasks[0]['due_date'], 'task date did not change.');
        $this->user1->deleteTask($tasks[0]['id']);

    }

    public function testDeleteCompletedTasks()
    {
        $taskName = "Task to Complete";
        $dueDate = "2025-03-01";
        $this->user1->createTask($taskName, $dueDate);
        $tasks = $this->user1->getUserTasks();
        $taskId = $tasks[0]['id'];
        $this->user1->markTaskCompleted($taskId);
        $this->user1->deleteCompletedTasks();
        $tasks = $this->user1->getUserTasks();
        $this->assertEmpty($tasks, 'completed task were not deleted');
    }

    public function testPairUsersAndAssignTasks()
    {
        // Step 1: Pair the two users
        $pairResult = $this->user1->createPair('testUser2');
        $this->assertNotFalse($pairResult, "Failed to create a pair between user1 and user2");

        // Step 2: Assign tasks to each other
        $taskNameForUser2 = "Task for User2";
        $dueDateForUser2 = "2025-05-01";
        $assignResult1 = $this->user1->assignTask($this->user2->getUserId(), $taskNameForUser2, $dueDateForUser2);
        $this->assertTrue($assignResult1, "Failed to assign task from user1 to user2");

        $taskNameForUser1 = "Task for User1";
        $dueDateForUser1 = "2025-05-02";
        $assignResult2 = $this->user2->assignTask($this->user1->getUserId(), $taskNameForUser1, $dueDateForUser1);
        $this->assertTrue($assignResult2, "Failed to assign task from user2 to user1");

        // Step 3: Modify tasks
        // User2 modifies the task assigned by User1
        $tasksForUser2 = $this->user2->getUserTasks();
        $this->assertNotEmpty($tasksForUser2, "No tasks found for user2 after assignment");
        $taskIdForUser2 = $tasksForUser2[0]['id'];
        $newTaskNameForUser2 = "Modified Task for User2";
        $newDueDateForUser2 = "2025-05-03";
        $modifyResult1 = $this->user2->changeTask($taskIdForUser2, $newTaskNameForUser2);
        $modifyDueDateResult1 = $this->user2->changeDueDate($taskIdForUser2, $newDueDateForUser2);
        $this->assertTrue($modifyResult1, "Failed to modify task name for user2");
        $this->assertTrue($modifyDueDateResult1, "Failed to modify due date for user2");

        // User1 modifies the task assigned by User2
        $tasksForUser1 = $this->user1->getUserTasks();
        $this->assertNotEmpty($tasksForUser1, "No tasks found for user1 after assignment");
        $taskIdForUser1 = $tasksForUser1[0]['id'];
        $newTaskNameForUser1 = "Modified Task for User1";
        $newDueDateForUser1 = "2025-05-04";
        $modifyResult2 = $this->user1->changeTask($taskIdForUser1, $newTaskNameForUser1);
        $modifyDueDateResult2 = $this->user1->changeDueDate($taskIdForUser1, $newDueDateForUser1);
        $this->assertTrue($modifyResult2, "Failed to modify task name for user1");
        $this->assertTrue($modifyDueDateResult2, "Failed to modify due date for user1");

        // Verify modified tasks
        $tasksForUser2 = $this->user2->getUserTasks();
        $this->assertEquals($newTaskNameForUser2, $tasksForUser2[0]['task'], "Task name for user2 not updated");
        $this->assertEquals($newDueDateForUser2, $tasksForUser2[0]['due_date'], "Due date for user2 not updated");

        $tasksForUser1 = $this->user1->getUserTasks();
        $this->assertEquals($newTaskNameForUser1, $tasksForUser1[0]['task'], "Task name for user1 not updated");
        $this->assertEquals($newDueDateForUser1, $tasksForUser1[0]['due_date'], "Due date for user1 not updated");

        // Step 4: Delete the pair
        $pairs = $this->user1->getPairs();
        $this->assertNotEmpty($pairs, "No pairs found for user1 after pairing");
        $pairId = $pairs[0]['id'];
        $deletePairResult = $this->user1->deletePair($pairId);
        $this->assertTrue($deletePairResult, "Failed to delete the pair");

        // Cleanup tasks
        $this->user1->deleteTask($taskIdForUser1);
        $this->user2->deleteTask($taskIdForUser2);
    }


}
