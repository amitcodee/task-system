<?php
// Include the database configuration file
require_once 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Set up date condition for each day filter
$selectedDay = 'Today';
$dateCondition = "DATE(due_date) = CURDATE()"; // Default to Today

if (isset($_GET['day'])) {
    $selectedDay = $_GET['day'];

    switch ($selectedDay) {
        case 'Yesterday':
            $dateCondition = "DATE(due_date) = CURDATE() - INTERVAL 1 DAY";
            break;
        case 'This Week':
            $dateCondition = "YEARWEEK(due_date, 1) = YEARWEEK(CURDATE(), 1)";
            break;
        case 'Last Week':
            $dateCondition = "YEARWEEK(due_date, 1) = YEARWEEK(CURDATE(), 1) - 1";
            break;
        case 'This Month':
            $dateCondition = "MONTH(due_date) = MONTH(CURDATE()) AND YEAR(due_date) = YEAR(CURDATE())";
            break;
        case 'Last Month':
            $dateCondition = "MONTH(due_date) = MONTH(CURDATE()) - 1 AND YEAR(due_date) = YEAR(CURDATE())";
            break;
        case 'All Time':
            $dateCondition = "1"; // No specific condition, show all tasks
            break;
        default:
            // For 'Today' or any unknown filter, use CURDATE() to fetch today's tasks
            $dateCondition = "DATE(due_date) = CURDATE()";
            break;
    }
}

// Initialize tasks array
$tasks = [];

try {
    // Fetch tasks based on the date condition
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE $dateCondition");
    $stmt->execute();
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching tasks: " . $e->getMessage();
}

// Filter tasks by status
function filterTasksByStatus($tasks, $status) {
    return array_filter($tasks, function($task) use ($status) {
        return isset($task['status']) && $task['status'] === $status;
    });
}

// Get tasks for each status category
$overdueTasks = filterTasksByStatus($tasks, 'Overdue');
$pendingTasks = filterTasksByStatus($tasks, 'Pending');
$inProgressTasks = filterTasksByStatus($tasks, 'In Progress');
$completedTasks = filterTasksByStatus($tasks, 'Completed');
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

            <!-- Day Filters -->
            <div class="flex flex-wrap items-center space-x-2 mb-6 overflow-x-auto">
                <a href="?day=Today" class="bg-gray-700 px-4 py-2 rounded-full text-white <?= $selectedDay === 'Today' ? 'bg-green-500' : '' ?>">Today</a>
                <a href="?day=Yesterday" class="bg-gray-700 px-4 py-2 rounded-full text-white <?= $selectedDay === 'Yesterday' ? 'bg-green-500' : '' ?>">Yesterday</a>
                <a href="?day=This Week" class="bg-gray-700 px-4 py-2 rounded-full text-white <?= $selectedDay === 'This Week' ? 'bg-green-500' : '' ?>">This Week</a>
                <a href="?day=Last Week" class="bg-gray-700 px-4 py-2 rounded-full text-white <?= $selectedDay === 'Last Week' ? 'bg-green-500' : '' ?>">Last Week</a>
                <a href="?day=This Month" class="bg-gray-700 px-4 py-2 rounded-full text-white <?= $selectedDay === 'This Month' ? 'bg-green-500' : '' ?>">This Month</a>
                <a href="?day=Last Month" class="bg-gray-700 px-4 py-2 rounded-full text-white <?= $selectedDay === 'Last Month' ? 'bg-green-500' : '' ?>">Last Month</a>
                <a href="?day=All Time" class="bg-gray-700 px-4 py-2 rounded-full text-white <?= $selectedDay === 'All Time' ? 'bg-green-500' : '' ?>">All Time</a>
            </div>

            <!-- Search and Filter -->
            <div class="flex items-center justify-between mb-6">
                <input type="text" placeholder="Search Task" class="w-full max-w-xs p-2 bg-gray-800 text-white rounded-lg focus:ring-2 focus:ring-green-500">
                <button class="bg-green-500 px-6 py-2 rounded-lg text-white ml-4">Filter</button>
            </div>

            <!-- Task Status Overview -->
            <div class="flex flex-wrap items-center justify-around mb-6 space-x-4 overflow-x-auto">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-4 h-4 bg-red-500 rounded-full mr-2"></div>
                    <span class="text-white">Overdue - <?= count($overdueTasks); ?></span>
                </div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-4 h-4 bg-yellow-500 rounded-full mr-2"></div>
                    <span class="text-white">Pending - <?= count($pendingTasks); ?></span>
                </div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-4 h-4 bg-orange-500 rounded-full mr-2"></div>
                    <span class="text-white">In Progress - <?= count($inProgressTasks); ?></span>
                </div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-4 h-4 bg-green-500 rounded-full mr-2"></div>
                    <span class="text-white">Completed - <?= count($completedTasks); ?></span>
                </div>
            </div>

            <!-- Task List -->
            <?php if (count($tasks) > 0): ?>
                <div class="grid grid-cols-1 gap-4">
                    <?php foreach ($tasks as $task): ?>
                        <div class="bg-gray-800 p-4 rounded-lg">
                            <h3 class="text-xl font-bold text-white"><?= htmlspecialchars($task['title']); ?></h3>
                            <p class="text-gray-400"><?= htmlspecialchars($task['description']); ?></p>
                            <?php if (isset($task['status'])): ?>
                                <span class="block mt-2 text-sm text-<?= strtolower($task['status']); ?>"><?= htmlspecialchars($task['status']); ?></span>
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
                    <p class="text-gray-500">It seems that you don't have any tasks in this list</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
