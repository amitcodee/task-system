<?php
// Include the database configuration file
include 'config.php';

session_start(); // Start the session to handle login sessions

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the email and password from the form
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Prepare a select statement to fetch the user based on the email
        $stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if user exists and password is correct
        if ($user && password_verify($password, $user['password'])) {
            // If password is correct, create a session for the user
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $email;

            // Redirect to the dashboard or homepage
            header("Location: dashboard.php");
            exit();
        } else {
            // If login fails, redirect back to login page with error
            header("Location: login.php?error=Invalid credentials");
            exit();
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
