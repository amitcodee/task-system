<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings - Task Management</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #1c1c28;
            color: #ffffff;
        }
        .account-container {
            padding: 30px;
        }
        .account-form label {
            color: #aaa;
            font-weight: bold;
        }
        .account-form input {
            background-color: #333;
            border: none;
            color: white;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
        }
        .account-form input:focus {
            border: 1px solid #00d98c;
            box-shadow: none;
        }
        .btn-save {
            background-color: #00d98c;
            color: #ffffff;
            font-size: 16px;
            font-weight: bold;
            padding: 10px;
            border-radius: 8px;
            border: none;
        }
        .btn-save:hover {
            background-color: #00b974;
        }
        .account-header {
            margin-bottom: 20px;
        }
        .account-header h3 {
            color: #ffffff;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Include Sidebar -->
        <?php include 'sidenav.php'; ?>

        <div class="col-md-10">
            <!-- Include Dashboard Header -->
            <?php include 'dashboard_header.php'; ?>

            <!-- Account Settings Page Content -->
            <div class="account-container">
                <div class="account-header">
                    <h3>Account Settings</h3>
                    <p>Manage your personal information and update your account settings below.</p>
                </div>

                <form action="process_account.php" method="POST" class="account-form">
                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="Amin Khan" required>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" value="amin@example.com" required>
                    </div>

                    <!-- Phone Number -->
                    <div class="mb-3">
                        <label for="phone">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="9876543210" required>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
                    </div>

                    <!-- Save Button -->
                    <button type="submit" class="btn btn-save">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5.3 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
