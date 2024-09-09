<?php
// Include the database configuration file
require_once 'config.php';

if (isset($_GET['task_id'])) {
    $taskId = $_GET['task_id'];

    try {
        $stmt = $pdo->prepare("SELECT tu.*, m.name FROM task_updates tu JOIN members m ON tu.user_id = m.id WHERE tu.task_id = :task_id ORDER BY tu.created_at DESC");
        $stmt->bindParam(':task_id', $taskId);
        $stmt->execute();
        $updates = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($updates);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>
