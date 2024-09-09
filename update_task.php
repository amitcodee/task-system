<?php
// Include the database configuration file
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskId = $_POST['task_id'];
    $newStatus = $_POST['status'];
    $updateMessage = htmlspecialchars($_POST['update_message']);
    $userId = $_SESSION['user_id']; // Assuming the logged-in user is posting the update

    try {
        // Start transaction
        $pdo->beginTransaction();

        // Update the task status
        $stmt = $pdo->prepare("UPDATE tasks SET status = :status WHERE id = :id");
        $stmt->bindParam(':status', $newStatus);
        $stmt->bindParam(':id', $taskId);
        $stmt->execute();

        // Insert the task update
        $stmt = $pdo->prepare("INSERT INTO task_updates (task_id, user_id, message, created_at) VALUES (:task_id, :user_id, :message, NOW())");
        $stmt->bindParam(':task_id', $taskId);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':message', $updateMessage);
        $stmt->execute();

        // Commit transaction
        $pdo->commit();

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
?>
