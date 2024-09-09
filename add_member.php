<?php
// Include the database configuration file
require_once 'config.php';

// Fetch existing members for the "Reporting Member" dropdown
$stmt = $pdo->prepare("SELECT id, name FROM members");
$stmt->execute();
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $name = htmlspecialchars($_POST['name']);
    $role = htmlspecialchars($_POST['role']);
    $email = htmlspecialchars($_POST['email']);
    $username = htmlspecialchars($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Hash the password
    $whatsappNumber = htmlspecialchars($_POST['whatsappNumber']);
    $reportingMember = $_POST['reportingMember'] != '' ? $_POST['reportingMember'] : NULL;  // Reporting member ID

    try {
        // Prepare an SQL statement to insert the data into the members table
        $stmt = $pdo->prepare("INSERT INTO members (name, role, email, username, password, whatsapp_number, reporting_member) 
                               VALUES (:name, :role, :email, :username, :password, :whatsapp_number, :reporting_member)");

        // Bind the parameters to the SQL query
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':whatsapp_number', $whatsappNumber);
        $stmt->bindParam(':reporting_member', $reportingMember);

        // Execute the SQL statement
        $stmt->execute();

        // Redirect back with a success message
        echo "<script>alert('Member added successfully!'); window.location.href='dashboard.php';</script>";
    } catch (PDOException $e) {
        // If an error occurs, display the error message
        echo "<script>alert('Member creation failed: " . $e->getMessage() . "'); window.location.href='add_member.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Member</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white min-h-screen flex  justify-center">
    <?php include 'sidenav.php'; ?>

    <!-- Main content -->
    <div class="flex-1 p-6 bg-gray-900">
        <!-- Include header -->
        <?php include 'header.php'; ?>
        <div class="p-6 md:p-12 lg:px-16 lg:py-8">
            <div class="w-full max-w-lg bg-gray-800 p-8 rounded-lg shadow-lg">
                <h2 class="text-2xl font-bold mb-6">Add New Member</h2>
                <form action="add_member.php" method="POST">

                    <!-- Member Name -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm text-gray-400">Member Name</label>
                        <input type="text" id="name" name="name" class="w-full p-3 bg-gray-900 text-white rounded-lg focus:ring-2 focus:ring-green-500" required>
                    </div>

                    <!-- Role Dropdown -->
                    <div class="mb-4">
                        <label for="role" class="block text-sm text-gray-400">Role</label>
                        <select id="role" name="role" class="w-full p-3 bg-gray-900 text-white rounded-lg focus:ring-2 focus:ring-green-500" required>
                            <option value="Manager">Manager</option>
                            <option value="Team Member">Team Member</option>
                        </select>
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm text-gray-400">Email</label>
                        <input type="email" id="email" name="email" class="w-full p-3 bg-gray-900 text-white rounded-lg focus:ring-2 focus:ring-green-500" required>
                    </div>

                    <!-- Username -->
                    <div class="mb-4">
                        <label for="username" class="block text-sm text-gray-400">Username</label>
                        <input type="text" id="username" name="username" class="w-full p-3 bg-gray-900 text-white rounded-lg focus:ring-2 focus:ring-green-500" required>
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="block text-sm text-gray-400">Password</label>
                        <input type="password" id="password" name="password" class="w-full p-3 bg-gray-900 text-white rounded-lg focus:ring-2 focus:ring-green-500" required>
                    </div>

                    <!-- WhatsApp Number -->
                    <div class="mb-4">
                        <label for="whatsappNumber" class="block text-sm text-gray-400">WhatsApp Number</label>
                        <input type="text" id="whatsappNumber" name="whatsappNumber" class="w-full p-3 bg-gray-900 text-white rounded-lg focus:ring-2 focus:ring-green-500" required>
                    </div>

                    <!-- Reporting Member Dropdown -->
                    <div class="mb-4">
                        <label for="reportingMember" class="block text-sm text-gray-400">Reporting Member</label>
                        <select id="reportingMember" name="reportingMember" class="w-full p-3 bg-gray-900 text-white rounded-lg focus:ring-2 focus:ring-green-500">
                            <option value="">Select Reporting Member</option>
                            <?php foreach ($members as $member): ?>
                                <option value="<?= $member['id']; ?>"><?= htmlspecialchars($member['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-green-500 py-3 rounded-lg text-white font-bold">Add Member</button>
                </form>
            </div>
        </div>
    </div>

</body>

</html>