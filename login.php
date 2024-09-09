<?php
// Include the database configuration file
require_once 'config.php';

// Initialize error message
$error_message = "";

// Process login form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    try {
        // Prepare an SQL statement to retrieve user by email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Fetch the user data
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify the password
        if ($user && password_verify($password, $user['password'])) {
            // Start a new session and set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['first_name'] = $user['first_name'];

            // Redirect to a dashboard or home page
            header("Location: dashboard.php");
            exit;
        } else {
            // If login fails, set an error message
            $error_message = "Invalid email or password";
        }
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
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> <!-- Font Awesome -->
</head>
<body class="bg-gradient-to-r from-green-400 via-yellow-500 to-green-600 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-gray-800 p-8 rounded-lg shadow-lg">
        <div class="text-center mb-8">
            <img src="logo.png" alt="Automate Business" class="mx-auto h-12 w-auto">
            <h1 class="text-2xl font-bold text-white mt-4">Login to Automate Team</h1>
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
                <label for="email" class="block text-sm text-gray-400">Email</label>
                <input type="email" id="email" name="email" class="w-full p-3 mt-1 bg-gray-900 text-white rounded-lg focus:ring focus:ring-yellow-500 focus:outline-none" required>
            </div>

            <div class="mb-4 relative">
                <label for="password" class="block text-sm text-gray-400">Password</label>
                <input type="password" id="password" name="password" class="w-full p-3 mt-1 bg-gray-900 text-white rounded-lg focus:ring focus:ring-yellow-500 focus:outline-none" required>
                <span class="absolute right-4 top-10 cursor-pointer" onclick="togglePasswordVisibility('password', 'passwordIcon')">
                    <i id="passwordIcon" class="fas fa-eye text-gray-400"></i>
                </span>
            </div>

            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-lg transition duration-200 focus:ring focus:ring-yellow-500 focus:outline-none">
                Login
            </button>
        </form>

        <div class="text-center mt-4 text-gray-400">
            Don't have an account? <a href="signup.php" class="text-green-500 hover:text-green-600">Signup here</a>
        </div>
    </div>

    <script>
        function togglePasswordVisibility(fieldId, iconId) {
            var field = document.getElementById(fieldId);
            var icon = document.getElementById(iconId);
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
