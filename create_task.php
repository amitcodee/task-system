<?php
// Include the database configuration file
require_once 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch users from the members table for multiple selection
$stmt = $pdo->prepare("SELECT id, name, email FROM members");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Define categories (or fetch them from the database if needed)
$categories = ['Development', 'Marketing', 'Design', 'HR', 'Finance'];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $taskTitle = htmlspecialchars($_POST['taskTitle']);
    $taskDescription = htmlspecialchars($_POST['taskDescription']);
    $selectUsers = $_POST['selectUsers'];  // Multiple user selection (array)
    $selectCategory = htmlspecialchars($_POST['selectCategory']);
    $priority = htmlspecialchars($_POST['priority']);
    $dueDate = htmlspecialchars($_POST['dueDate']);
    $repeatTask = isset($_POST['repeatTask']) ? 1 : 0; // Checkbox for repeat task
    $assignMoreTasks = isset($_POST['assignMoreTasks']) ? 1 : 0; // Checkbox for assigning more tasks
    $createdBy = $_SESSION['user_id']; // Store the logged-in user's ID

    try {
        // Begin a transaction
        $pdo->beginTransaction();

        // Prepare an SQL statement to insert the task into the tasks table
        $stmt = $pdo->prepare("INSERT INTO tasks (title, description, category, priority, due_date, repeat_task, assign_more_tasks, created_by) 
                               VALUES (:title, :description, :category, :priority, :due_date, :repeat_task, :assign_more_tasks, :created_by)");

        // Bind the parameters to the SQL query
        $stmt->bindParam(':title', $taskTitle);
        $stmt->bindParam(':description', $taskDescription);
        $stmt->bindParam(':category', $selectCategory);
        $stmt->bindParam(':priority', $priority);
        $stmt->bindParam(':due_date', $dueDate);
        $stmt->bindParam(':repeat_task', $repeatTask);
        $stmt->bindParam(':assign_more_tasks', $assignMoreTasks);
        $stmt->bindParam(':created_by', $createdBy);  // Bind the logged-in user's ID

        // Execute the SQL statement
        $stmt->execute();

        // Get the ID of the newly created task
        $taskId = $pdo->lastInsertId();

        // Insert each selected user into the task_user table
        $stmt = $pdo->prepare("INSERT INTO task_user (task_id, user_id) VALUES (:task_id, :user_id)");

        foreach ($selectUsers as $user_id) {
            $stmt->bindParam(':task_id', $taskId);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
        }

        // Commit the transaction
        $pdo->commit();

        // Redirect back to the dashboard with a success message
        echo "<script>alert('Task assigned successfully!'); window.location.href='dashboard.php';</script>";
    } catch (PDOException $e) {
        // Rollback the transaction if something failed
        $pdo->rollBack();

        // If an error occurs, display the error message
        echo "<script>alert('Task creation failed: " . $e->getMessage() . "'); window.location.href='task_create.php';</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Task</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> <!-- Flatpickr for custom calendar -->
    <style>
        /* Avatar initials background color */
        .avatar-initials {
            background-color: #3182CE;
            /* Example color */
        }
    </style>
</head>

<body class="bg-gray-900 text-white min-h-screen flex  justify-center">
    <?php include 'sidenav.php'; ?>

    <!-- Main content -->
    <div class="flex-1 p-6 bg-gray-900">
        <!-- Include header -->
        <?php include 'header.php'; ?>
        <div class="p-6 md:p-12 lg:px-16 lg:py-8">

            <div class="w-full max-w-lg bg-gray-800 p-8 rounded-lg shadow-lg">
                <h2 class="text-2xl font-bold mb-6">Create New Task</h2>
                <form action="create_task.php" method="POST">

                    <!-- Task Title -->
                    <div class="mb-4">
                        <label for="taskTitle" class="block text-sm text-gray-400">Task Title</label>
                        <input type="text" id="taskTitle" name="taskTitle" class="w-full p-3 bg-gray-900 text-white rounded-lg focus:ring-2 focus:ring-green-500" required>
                    </div>

                    <!-- Task Description -->
                    <div class="mb-4">
                        <label for="taskDescription" class="block text-sm text-gray-400">Task Description</label>
                        <textarea id="taskDescription" name="taskDescription" class="w-full p-3 bg-gray-900 text-white rounded-lg focus:ring-2 focus:ring-green-500" required></textarea>
                    </div>

                    <!-- Select Users -->
                    <div class="mb-4">
                        <label for="selectUsers" class="block text-sm text-gray-400">Select Users</label>
                        <div class="relative">
                            <div class="w-full p-3 bg-gray-900 text-white rounded-lg flex items-center space-x-2" id="selectedUsersContainer">
                                <!-- Selected users will appear here -->
                                <span id="defaultText" class="text-gray-400">Select Users</span>
                            </div>
                            <div id="userDropdown" class="absolute w-full bg-gray-800 text-white rounded-lg shadow-lg max-h-48 overflow-y-auto mt-2 hidden">
                                <?php foreach ($users as $user): ?>
                                    <div class="flex items-center p-2 hover:bg-gray-700">
                                        <input type="checkbox" id="user-<?= $user['id']; ?>" name="selectUsers[]" value="<?= $user['id']; ?>" class="mr-2" onchange="handleUserSelection(this)">
                                        <label for="user-<?= $user['id']; ?>" class="flex-grow">
                                            <?= htmlspecialchars($user['name']); ?> <span class="text-gray-400 text-sm"><?= htmlspecialchars($user['email']); ?></span>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Select Category -->
                    <div class="mb-4">
                        <label for="selectCategory" class="block text-sm text-gray-400">Select Category</label>
                        <select id="selectCategory" name="selectCategory" class="w-full p-3 bg-gray-900 text-white rounded-lg focus:ring-2 focus:ring-green-500" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= htmlspecialchars($category); ?>"><?= htmlspecialchars($category); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Priority -->
                    <div class="mb-4">
                        <label class="block text-sm text-gray-400">Priority</label>
                        <div class="flex space-x-4">
                            <label class="flex items-center">
                                <input type="radio" name="priority" value="High" class="mr-2"> <span class="text-white">High</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="priority" value="Medium" class="mr-2"> <span class="text-white">Medium</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="priority" value="Low" class="mr-2"> <span class="text-white">Low</span>
                            </label>
                        </div>
                    </div>

                    <!-- Due Date -->
                    <div class="mb-4">
                        <label for="dueDate" class="block text-sm text-gray-400">Due Date</label>
                        <input type="date" id="dueDate" name="dueDate" class="w-full p-3 bg-gray-900 text-white rounded-lg focus:ring-2 focus:ring-green-500 flatpickr" required>
                    </div>

                    <!-- Repeat Task Checkbox -->
                    <div class="mb-4">
                        <label class="flex items-center text-gray-400">
                            <input type="checkbox" name="repeatTask" class="mr-2"> Repeat Task
                        </label>
                    </div>

                    <!-- Assign More Tasks Checkbox -->
                    <div class="mb-4">
                        <label class="flex items-center text-gray-400">
                            <input type="checkbox" name="assignMoreTasks" class="mr-2"> Assign More Tasks
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-green-500 py-3 rounded-lg text-white font-bold">Create Task</button>
                </form>
            </div>
        </div>
    </div>
    <script>
      
        // Toggle the user dropdown visibility
        document.querySelector("#selectedUsersContainer").addEventListener("click", function() {
            document.getElementById('userDropdown').classList.toggle('hidden');
        });

        // Function to handle user selection
        function handleUserSelection(checkbox) {
            const selectedUsersContainer = document.getElementById('selectedUsersContainer');
            const defaultText = document.getElementById('defaultText');

            // Remove default text
            if (checkbox.checked && defaultText) {
                defaultText.remove();
            }

            if (checkbox.checked) {
                // Get user initials and name
                const userName = checkbox.nextElementSibling.textContent.trim();
                const initials = userName.split(" ").map(n => n[0]).join(""); // Get initials

                // Create user badge
                const userBadge = document.createElement('div');
                userBadge.classList.add('flex', 'items-center', 'space-x-2');
                userBadge.innerHTML = `
                    <div class="avatar-initials w-8 h-8 rounded-full text-white flex items-center justify-center">${initials}</div>
                    <span>${userName.split(" ")[0]}</span>
                `;

                userBadge.setAttribute("id", `user-badge-${checkbox.id}`);
                selectedUsersContainer.appendChild(userBadge);

            } else {
                // Remove user badge if unchecked
                const userBadge = document.getElementById(`user-badge-${checkbox.id}`);
                if (userBadge) {
                    userBadge.remove();
                }

                // Show default text if no user selected
                if (!selectedUsersContainer.querySelector('.flex')) {
                    selectedUsersContainer.innerHTML = `<span id="defaultText" class="text-gray-400">Select Users</span>`;
                }
            }
        }
    </script>

</body>

</html>
