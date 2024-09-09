<?php
// Include the database configuration file
require_once 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get task ID from the URL
$taskId = isset($_GET['task_id']) ? $_GET['task_id'] : null;
if (!$taskId) {
    echo "Task not found.";
    exit;
}

// Fetch task details and updates
try {
    // Fetch task details
    $stmt = $pdo->prepare("
        SELECT t.*, m.name AS assigned_to_name, u.name AS assigned_by_name
        FROM tasks t
        LEFT JOIN members m ON t.assigned_to = m.id
        LEFT JOIN users u ON t.assigned_by = u.id
        WHERE t.id = :task_id
    ");
    $stmt->bindParam(':task_id', $taskId);
    $stmt->execute();
    $task = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$task) {
        echo "Task not found.";
        exit;
    }

    // Fetch task updates
    $stmt = $pdo->prepare("SELECT tu.*, m.name FROM task_updates tu JOIN members m ON tu.user_id = m.id WHERE tu.task_id = :task_id ORDER BY tu.created_at DESC");
    $stmt->bindParam(':task_id', $taskId);
    $stmt->execute();
    $taskUpdates = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching task: " . $e->getMessage();
}

// Handle task status update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

        // Redirect back to the same page to prevent resubmission
        header("Location: task_detail.php?task_id=$taskId");
        exit;
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Error updating task: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center">
    <div class="w-full max-w-2xl bg-gray-800 p-8 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-6">Task Details: <?= htmlspecialchars($task['title']); ?></h2>

        <!-- Task Details Section -->
        <div>
            <p class="text-sm text-gray-400"><strong>Assigned To:</strong> <?= htmlspecialchars($task['assigned_to_name']); ?></p>
            <p class="text-sm text-gray-400"><strong>Assigned By:</strong> <?= htmlspecialchars($task['assigned_by_name']); ?></p>
            <p class="text-sm text-gray-400"><strong>Created At:</strong> <?= date('D, M j - h:i A', strtotime($task['created_at'])); ?></p>
            <p class="text-sm text-gray-400"><strong>Due Date:</strong> <?= date('M j, Y - h:i A', strtotime($task['due_date'])); ?></p>
            <p class="text-sm text-gray-400"><strong>Status:</strong> <?= htmlspecialchars($task['status']); ?></p>
            <p class="text-sm text-gray-400"><strong>Category:</strong> <?= htmlspecialchars($task['category']); ?></p>
            <p class="text-sm text-gray-400"><strong>Priority:</strong> <?= htmlspecialchars($task['priority']); ?></p>
            <p class="text-sm text-gray-400"><strong>Description:</strong> <?= htmlspecialchars($task['description']); ?></p>
        </div>

        <!-- Status Update Section -->
        <form method="POST" class="mt-6">
            <div class="flex items-center space-x-4">
                <button type="submit" name="status" value="In Progress" class="bg-yellow-500 px-4 py-2 rounded-lg text-white">In Progress</button>
                <button type="submit" name="status" value="Completed" class="bg-green-500 px-4 py-2 rounded-lg text-white">Complete</button>
            </div>
            <textarea name="update_message" placeholder="Enter your update..." class="w-full mt-4 p-3 bg-gray-900 text-white rounded-lg focus:ring-2 focus:ring-green-500"></textarea>
            <button type="submit" class="w-full bg-green-500 py-3 rounded-lg text-white font-bold mt-4">Submit Update</button>
        </form>

        <!-- Task Updates Section -->
        <div class="mt-6">
            <h3 class="text-lg font-bold text-white mb-2">Task Updates</h3>
            <?php if (count($taskUpdates) > 0): ?>
                <ul class="space-y-4">
                    <?php foreach ($taskUpdates as $update): ?>
                        <li class="text-gray-400">
                            <span class="font-bold text-white"><?= htmlspecialchars($update['name']); ?></span>
                            <span class="text-sm text-gray-500">(<?= date('M j, Y - h:i A', strtotime($update['created_at'])); ?>)</span> - 
                            <?= htmlspecialchars($update['message']); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-gray-500">No updates yet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
