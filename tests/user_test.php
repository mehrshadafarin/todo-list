<?php
use PHPUnit\Framework\TestCase;
use Dotenv\Dotenv;
require_once __DIR__ . '/../models/user.php';

class user_test extends TestCase
{
    private $user;

    protected function setUp(): void
    {
       
        // Create a new User instance
        $this->user = new User('testUser', 'testPassword');
        
        // Ensure the user is created or logged in before running tests
        $this->user->create();
    }

    protected function tearDown(): void
    {
        // Clean up after tests by deleting the test user
        $this->user->delete();
    }

    public function testCreateAndDeleteTask()
    {
        // Create a task
        $taskName = "Test Task";
        $dueDate = "2025-01-01";
        $result = $this->user->createTask($taskName, $dueDate);
        $this->assertTrue($result, "Task creation failed");

        // Retrieve tasks to ensure it was created
        $tasks = $this->user->getUserTasks();
        $this->assertNotEmpty($tasks, "Task list is empty");
        $this->assertEquals($taskName, $tasks[0]['name'], "Task name mismatch");

        // Delete the task
        $taskId = $tasks[0]['id'];
        $deleteResult = $this->user->deleteTask($taskId);
        $this->assertTrue($deleteResult, "Failed to delete the task");

        // Ensure the task is no longer in the task list
        $tasksAfterDeletion = $this->user->getUserTasks();
        $this->assertEmpty($tasksAfterDeletion, "Task was not deleted");
    }

    public function testAssignAndRetrieveTasks()
    {
        // Create a pair
        $pairUsername = "pairUser";
        $this->user->createPair($pairUsername);

        // Assign a task to the paired user
        $taskName = "Assigned Task";
        $dueDate = "2025-02-01";
        $assignResult = $this->user->assignTask(2, $taskName, $dueDate); // Assuming paired user ID is 2
        $this->assertTrue($assignResult, "Task assignment failed");

        // Retrieve assigned tasks
        $assignedTasks = $this->user->getAssignedTasks(2);
        $this->assertNotEmpty($assignedTasks, "Assigned tasks list is empty");
        $this->assertEquals($taskName, $assignedTasks[0]['name'], "Assigned task name mismatch");
    }

    public function testCompleteTask()
    {
        // Create a task
        $taskName = "Task to Complete";
        $dueDate = "2025-03-01";
        $this->user->createTask($taskName, $dueDate);

        // Mark task as completed
        $tasks = $this->user->getUserTasks();
        $taskId = $tasks[0]['id'];
        $completeResult = $this->user->markTaskCompleted($taskId);
        $this->assertTrue($completeResult, "Failed to mark task as completed");
    }

    public function testCreateAndDeletePair()
    {
        // Create a pair
        $pairUsername = "newPairUser";
        $createPairResult = $this->user->createPair($pairUsername);
        $this->assertTrue($createPairResult, "Failed to create pair");

        // Retrieve pairs to confirm
        $pairs = $this->user->getPairs();
        $this->assertNotEmpty($pairs, "Pairs list is empty");
        $this->assertEquals($pairUsername, $pairs[0]['username'], "Pair username mismatch");

        // Delete the pair
        $pairId = $pairs[0]['id'];
        $deletePairResult = $this->user->deletePair($pairId);
        $this->assertTrue($deletePairResult, "Failed to delete pair");
    }
}
