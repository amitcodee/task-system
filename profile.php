<?php
// Include the database configuration and session start
require_once 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Initialize variables for user data
$user_id = $_SESSION['user_id'];

// Fetch current user details from database
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Handle form submission for updating details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = htmlspecialchars($_POST['first_name']);
    $last_name = htmlspecialchars($_POST['last_name']);
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Update user details in the database
    if ($new_password === $confirm_password) {
        if (!empty($new_password)) {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET first_name = :first_name, last_name = :last_name, password = :password WHERE id = :id");
            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':password', $hashed_password);
        } else {
            // Update only the names if password is not changed
            $stmt = $pdo->prepare("UPDATE users SET first_name = :first_name, last_name = :last_name WHERE id = :id");
            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':last_name', $last_name);
        }

        $stmt->bindParam(':id', $user_id);
        $stmt->execute();

        // Update session with the new first name
        $_SESSION['first_name'] = $first_name;

        echo "<script>alert('Profile updated successfully!'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('Passwords do not match'); window.location.href='profile.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md bg-gray-800 p-8 rounded-lg shadow-lg">
        <h1 class="text-3xl font-bold mb-4">Profile</h1>

        <!-- Profile form -->
        <form action="profile.php" method="POST">
            <!-- First Name field -->
            <div class="mb-4">
                <label for="first_name" class="block text-sm text-gray-400">First Name</label>
                <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" class="w-full p-3 bg-gray-900 text-white rounded-lg focus:ring focus:ring-yellow-500 focus:outline-none" required>
            </div>

            <!-- Last Name field -->
            <div class="mb-4">
                <label for="last_name" class="block text-sm text-gray-400">Last Name</label>
                <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" class="w-full p-3 bg-gray-900 text-white rounded-lg focus:ring focus:ring-yellow-500 focus:outline-none" required>
            </div>

            <!-- Password field (optional) -->
            <div class="mb-4 relative">
                <label for="password" class="block text-sm text-gray-400">New Password (Leave blank to keep unchanged)</label>
                <input type="password" id="password" name="password" class="w-full p-3 bg-gray-900 text-white rounded-lg focus:ring focus:ring-yellow-500 focus:outline-none">
            </div>

            <!-- Confirm Password field -->
            <div class="mb-4 relative">
                <label for="confirm_password" class="block text-sm text-gray-400">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="w-full p-3 bg-gray-900 text-white rounded-lg focus:ring focus:ring-yellow-500 focus:outline-none">
            </div>

            <!-- Submit button -->
            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-lg transition duration-200 focus:ring focus:ring-yellow-500 focus:outline-none">
                Update Profile
            </button>
        </form>
    </div>

</body>
</html>
