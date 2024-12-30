<?php
require_once __DIR__.'/../config/db_connect.php';
require_once 'task_manager.php';

class pairManager
{
    protected $conn;
    private TaskManager $taskManager;



    public function __construct()
    {
        $this->conn = DatabaseFactory::createConnection();
        $this->taskManager = new TaskManager();
    }

    public function createPair(int $userId, string $username)
    {

        $sql = "SELECT id FROM users WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
        } else {
            return false;
        }


        $sql = "INSERT INTO user_pairs (user_id_1, user_id_2) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $user['id']);
        $stmt->execute();
        return $stmt->insert_id;

    }

    /**
     * Deletes a pair from the `user_pairs` table and removes all tasks where 
     * either user in the pair is the assigner. The operation is performed within 
     * a transaction to ensure atomicity: if any step fails, no changes are committed.
     *
     * @param int $pairId The ID of the pair to delete.
     * @return bool Returns true if the deletion was successful, false otherwise.
     * If any error occurs during the transaction, the changes are rolled back.
     */
    public function deletePair(int $pairId)
    {
        try {
            $this->conn->begin_transaction();

            $sqlGetPair = "SELECT user_id_1, user_id_2 FROM user_pairs WHERE id = ?";
            $stmtGetPair = $this->conn->prepare($sqlGetPair);
            $stmtGetPair->bind_param("i", $pairId);
            $stmtGetPair->execute();
            $result = $stmtGetPair->get_result();

            if ($result->num_rows === 0) {
                return false;
            }

            $pair = $result->fetch_assoc();
            $userId1 = $pair['user_id_1'];
            $userId2 = $pair['user_id_2'];

            $sqlDeleteTasks = "DELETE FROM tasks WHERE assigner_id = ? OR assigner_id = ?";
            $stmtDeleteTasks = $this->conn->prepare($sqlDeleteTasks);
            $stmtDeleteTasks->bind_param("ii", $userId1, $userId2);
            $stmtDeleteTasks->execute();

            $sqlDeletePair = "DELETE FROM user_pairs WHERE id = ?";
            $stmtDeletePair = $this->conn->prepare($sqlDeletePair);
            $stmtDeletePair->bind_param("i", $pairId);
            $stmtDeletePair->execute();

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    /**
     * get all pairs related to user
     * @param int $userId
     * @return array An array of pairs with 'id', 'user_id', username for the given user.
     */
    public function getPairs(int $userId)
    {

        $sql = "SELECT user_pairs.id, user_pairs.user_id_1, user_pairs.user_id_2, u1.username AS username_1, u2.username AS username_2 FROM (SELECT * FROM user_pairs WHERE user_id_1 = ? OR user_id_2 = ?) AS user_pairs JOIN users AS u1 ON user_pairs.user_id_1 = u1.id JOIN users AS u2 ON user_pairs.user_id_2 = u2.id;";


        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();


        $pairs = [];
        while ($row = $result->fetch_assoc()) {
            $pairs[] = array(
                "id" => $row['id'],
                "user_id" => ($row['user_id_1'] == $userId) ? $row['user_id_2'] : $row['user_id_1'],
                "username" => ($row['user_id_1'] == $userId) ? $row['username_2'] : $row['username_1']
            );
        }


        $stmt->close();


        return $pairs;
    }





}
?>