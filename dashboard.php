<?php
// Include the database configuration file
require_once 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get the logged-in user's ID
$user_id = $_SESSION['user_id']; // The logged-in member's ID

// Initialize tasks array
$tasks = [];

try {
    // Fetch tasks for the logged-in user
    $stmt = $pdo->prepare("
        SELECT t.*, tu.user_id
        FROM tasks t
        JOIN task_user tu ON t.id = tu.task_id
        WHERE tu.user_id = :user_id
    ");
    $stmt->bindParam(':user_id', $user_id); // Bind the logged-in user's ID
    $stmt->execute();
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching tasks: " . $e->getMessage();
}

// Filter tasks by status
function filterTasksByStatus($tasks, $status)
{
    return array_filter($tasks, function ($task) use ($status) {
        return isset($task['status']) && $task['status'] === $status;
    });
}

// Calculate task metrics
$totalTasks = count($tasks);
$pendingTasks = count(filterTasksByStatus($tasks, 'Pending'));
$completedTasks = count(filterTasksByStatus($tasks, 'Completed'));
$overdueTasks = count(filterTasksByStatus($tasks, 'Overdue'));
$inProgressTasks = count(filterTasksByStatus($tasks, 'In Progress'));

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> <!-- Font Awesome -->
</head>

<body class="bg-gray-900 text-white min-h-screen flex">

    <!-- Include sidenav -->
    <?php include 'sidenav.php'; ?>

    <!-- Main content -->
    <div class="flex-1 p-6 bg-gray-900">

        <!-- Include header -->
        <?php include 'header.php'; ?>

        <div class="p-6 md:p-12 lg:px-16 lg:py-8">

            <!-- Task Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Total Tasks -->
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                    <h3 class="text-2xl font-bold text-white mb-2">Total Tasks</h3>
                    <p class="text-4xl font-bold text-green-500"><?= $totalTasks; ?></p>
                </div>

                <!-- Pending Tasks -->
                <div class="bg-yellow-500 p-6 rounded-lg shadow-lg">
                    <h3 class="text-2xl font-bold text-white mb-2">Pending Tasks</h3>
                    <p class="text-4xl font-bold text-white"><?= $pendingTasks; ?></p>
                </div>

                <!-- Overdue Tasks -->
                <div class="bg-red-500 p-6 rounded-lg shadow-lg">
                    <h3 class="text-2xl font-bold text-white mb-2">Overdue Tasks</h3>
                    <p class="text-4xl font-bold text-white"><?= $overdueTasks; ?></p>
                </div>

                <!-- Completed Tasks -->
                <div class="bg-green-500 p-6 rounded-lg shadow-lg">
                    <h3 class="text-2xl font-bold text-white mb-2">Completed Tasks</h3>
                    <p class="text-4xl font-bold text-white"><?= $completedTasks; ?></p>
                </div>
            </div>

            <!-- Task List -->
            <?php if (count($tasks) > 0): ?>
                <div class="grid grid-cols-1 gap-4">
                    <?php foreach ($tasks as $task): ?>
                        <div class="bg-gray-800 p-4 rounded-lg">
                            <h3 class="text-xl font-bold text-white">
                                <a href="task_detail.php?task_id=<?= $task['id']; ?>" class="hover:underline">
                                    <?= htmlspecialchars($task['title']); ?>
                                </a>
                            </h3>

                            <p class="text-gray-400"><?= htmlspecialchars($task['description']); ?></p>

                            <!-- Task Details -->
                            <ul class="mt-2">
                                <!-- <li class="text-sm text-gray-400">Assigned To: <?= htmlspecialchars($task['assigned_to_name']); ?></li> -->
                                <li class="text-sm text-gray-400">Due Date: <?= date('M j, Y', strtotime($task['due_date'])); ?></li>
                                <li class="text-sm text-gray-400">Priority: <span class="text-<?= strtolower($task['priority']); ?>"><?= htmlspecialchars($task['priority']); ?></span></li>
                            </ul>

                            <!-- Task Status -->
                            <?php if (isset($task['status'])): ?>
                                <span class="block mt-2 text-sm text-white bg-<?= strtolower($task['status']); ?>-500 px-2 py-1 rounded">
                                    <?= htmlspecialchars($task['status']); ?>
                                </span>
                            <?php else: ?>
                                <span class="block mt-2 text-sm text-gray-400">No Status</span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <!-- Empty State (If no tasks are found) -->
                <div class="flex flex-col items-center justify-center mt-12">
                    <img src="empty-state.svg" alt="No Tasks Found" class="h-24 w-24 mb-4">
                    <p class="text-xl font-bold text-gray-400">No Tasks Here</p>
                    <p class="text-gray-500">It seems that you don't have any tasks in this list.</p>
                </div>
            <?php endif; ?>


        </div>
    </div>
    <!-- Task Modal -->
<div id="taskModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-gray-800 p-6 rounded-lg shadow-lg max-w-lg w-full">
        <h3 class="text-xl font-bold text-white mb-4" id="modalTaskTitle">Task Details</h3>

        <!-- Task Details Section -->
        <div>
            <p class="text-sm text-gray-400"><strong>Assigned To:</strong> <span id="modalAssignedTo"></span></p>
            <p class="text-sm text-gray-400"><strong>Created At:</strong> <span id="modalCreatedAt"></span></p>
            <p class="text-sm text-gray-400"><strong>Due Date:</strong> <span id="modalDueDate"></span></p>
            <p class="text-sm text-gray-400"><strong>Status:</strong> <span id="modalStatus"></span></p>
            <p class="text-sm text-gray-400"><strong>Priority:</strong> <span id="modalPriority"></span></p>
            <p class="text-sm text-gray-400"><strong>Description:</strong> <span id="modalDescription"></span></p>
        </div>

        <!-- Status Update Buttons -->
        <div class="mt-4">
            <button id="inProgressBtn" class="bg-yellow-500 px-4 py-2 rounded-lg text-white">In Progress</button>
            <button id="completeBtn" class="bg-green-500 px-4 py-2 rounded-lg text-white">Complete</button>
        </div>

        <!-- Task Update Form (Initially hidden) -->
        <div id="taskUpdateForm" class="mt-4 hidden">
            <textarea id="taskUpdateText" class="w-full p-3 bg-gray-900 text-white rounded-lg focus:ring-2 focus:ring-green-500" placeholder="Enter your update..."></textarea>
            <button id="submitUpdateBtn" class="w-full bg-green-500 py-3 rounded-lg text-white font-bold mt-4">Submit Update</button>
        </div>

        <!-- Task Updates Section -->
        <div id="taskUpdatesSection" class="mt-6">
            <h4 class="text-lg font-bold text-white mb-2">Task Updates</h4>
            <ul id="taskUpdatesList">
                <!-- Task updates will be displayed here -->
            </ul>
        </div>

        <!-- Close Modal Button -->
        <button class="absolute top-2 right-2 text-white" onclick="closeModal()">X</button>
    </div>
</div>
<script>
let currentTaskId = null;

// Open the task modal and populate it with task data
function openTaskModal(task) {
    currentTaskId = task.id;

    document.getElementById('modalTaskTitle').innerText = task.title;
    document.getElementById('modalAssignedTo').innerText = task.assigned_to;
    document.getElementById('modalCreatedAt').innerText = new Date(task.created_at).toLocaleString();
    document.getElementById('modalDueDate').innerText = new Date(task.due_date).toLocaleString();
    document.getElementById('modalStatus').innerText = task.status;
    document.getElementById('modalPriority').innerText = task.priority;
    document.getElementById('modalDescription').innerText = task.description;

    // Fetch and display task updates
    fetchTaskUpdates(task.id);

    // Show modal
    document.getElementById('taskModal').classList.remove('hidden');
}

// Close the task modal
function closeModal() {
    document.getElementById('taskModal').classList.add('hidden');
}

// Fetch and display task updates
function fetchTaskUpdates(taskId) {
    // Fetch updates via AJAX or any method
    fetch(`get_task_updates.php?task_id=${taskId}`)
        .then(response => response.json())
        .then(updates => {
            const updatesList = document.getElementById('taskUpdatesList');
            updatesList.innerHTML = ''; // Clear current updates

            updates.forEach(update => {
                const li = document.createElement('li');
                li.classList.add('text-white', 'mb-2');
                li.innerHTML = `<strong>${update.name}</strong> (${new Date(update.timestamp).toLocaleString()}) - ${update.message} <span class="bg-yellow-500 px-2 py-1 rounded">${update.status}</span>`;
                updatesList.appendChild(li);
            });
        });
}

// Handle In Progress button click
document.getElementById('inProgressBtn').addEventListener('click', function() {
    document.getElementById('taskUpdateForm').classList.remove('hidden');
    document.getElementById('submitUpdateBtn').setAttribute('data-status', 'In Progress');
});

// Handle Complete button click
document.getElementById('completeBtn').addEventListener('click', function() {
    document.getElementById('taskUpdateForm').classList.remove('hidden');
    document.getElementById('submitUpdateBtn').setAttribute('data-status', 'Completed');
});

// Handle submit update button click
document.getElementById('submitUpdateBtn').addEventListener('click', function() {
    const updateMessage = document.getElementById('taskUpdateText').value;
    const newStatus = this.getAttribute('data-status');

    const data = {
        task_id: currentTaskId,
        update_message: updateMessage,
        status: newStatus
    };

    fetch('update_task.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            // Clear the form and refresh the updates
            document.getElementById('taskUpdateText').value = '';
            document.getElementById('taskUpdateForm').classList.add('hidden');
            fetchTaskUpdates(currentTaskId);
        } else {
            console.error('Error updating task:', result.error);
        }
    });
});
</script>

</body>

</html>