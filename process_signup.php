<?php
// Include the database configuration file
require_once 'config.php';

// Check if the request is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $firstName = htmlspecialchars($_POST['firstName']);
    $lastName = htmlspecialchars($_POST['lastName']);
    $country = $_POST['country'];
    $whatsAppNumber = htmlspecialchars($_POST['whatsAppNumber']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Password match validation
    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match'); window.location.href='signup.php';</script>";
        exit;
    }

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Prepare an SQL statement to insert the data into the users table
        $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, country, whatsapp_number, email, password) VALUES (:firstName, :lastName, :country, :whatsAppNumber, :email, :password)");

        // Bind the parameters to the SQL query
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':whatsAppNumber', $whatsAppNumber);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        // Execute the SQL statement
        $stmt->execute();

        // Redirect to login page after successful signup
        echo "<script>alert('Signup Successful!'); window.location.href='login.php';</script>";
    } catch (PDOException $e) {
        // If an error occurs, display the error message
        echo "<script>alert('Signup failed: " . $e->getMessage() . "'); window.location.href='signup.php';</script>";
    }
} else {
    // Redirect to signup page if not a POST request
    header("Location: signup.php");
    exit;
}
?>
