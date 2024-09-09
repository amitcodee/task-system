<?php
// Include the database configuration file
require_once 'config.php';

// Initialize error message
$error_message = "";

// Process login form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $loginInput = htmlspecialchars($_POST['loginInput']); // Can be username or email for members, email for users
    $password = $_POST['password'];

    try {
        // First, try logging in from the 'members' table using either username or email
        $stmt = $pdo->prepare("SELECT * FROM members WHERE username = :loginInput OR email = :loginInput");
        $stmt->bindParam(':loginInput', $loginInput);
        $stmt->execute();

        // Fetch the member data
        $member = $stmt->fetch(PDO::FETCH_ASSOC);

        // If member exists and password is correct, log them in
        if ($member && password_verify($password, $member['password'])) {
            session_start();
            $_SESSION['user_id'] = $member['id'];
            $_SESSION['first_name'] = $member['name']; // Assuming 'name' column in 'members' table

            // Redirect to the dashboard
            header("Location: dashboard.php");
            exit;
        }

        // If not found in 'members', try logging in from the 'users' table using email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :loginInput");
        $stmt->bindParam(':loginInput', $loginInput);
        $stmt->execute();

        // Fetch the user data
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // If user exists and password is correct, log them in
        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['first_name'] = $user['first_name']; // Assuming 'first_name' column in 'users' table

            // Redirect to the dashboard
            header("Location: dashboard.php");
            exit;
        }

        // If login fails, set an error message
        $error_message = "Invalid username, email, or password.";
    } catch (PDOException $e) {
        $error_message = "An error occurred: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-green-400 via-yellow-500 to-green-600 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-gray-800 p-8 rounded-lg shadow-lg">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-white mt-4">Login</h1>
            <p class="text-gray-400">Please login to access your account.</p>
        </div>
        
        <!-- Display error message if login fails -->
        <?php if ($error_message): ?>
            <div class="mb-4 bg-red-500 text-white p-3 rounded">
                <?= $error_message ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="mb-4">
                <label for="loginInput" class="block text-sm text-gray-400">Username or Email</label>
                <input type="text" id="loginInput" name="loginInput" class="w-full p-3 mt-1 bg-gray-900 text-white rounded-lg focus:ring focus:ring-yellow-500 focus:outline-none" required>
            </div>

            <div class="mb-4 relative">
                <label for="password" class="block text-sm text-gray-400">Password</label>
                <input type="password" id="password" name="password" class="w-full p-3 mt-1 bg-gray-900 text-white rounded-lg focus:ring focus:ring-yellow-500 focus:outline-none" required>
            </div>

            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-lg transition duration-200 focus:ring focus:ring-yellow-500 focus:outline-none">
                Login
            </button>
        </form>

        <div class="text-center mt-4 text-gray-400">
            Don't have an account? <a href="signup.php" class="text-green-500 hover:text-green-600">Signup here</a>
        </div>
    </div>
</body>
</html>
