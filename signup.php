<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-green-400 via-yellow-500 to-green-600 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-gray-800 p-8 rounded-lg shadow-lg">
        <div class="text-center mb-8">
            <img src="logo.png" alt="Automate Business" class="mx-auto h-12 w-auto">
            <h1 class="text-2xl font-bold text-white mt-4">Automate Team</h1>
            <p class="text-gray-400">Let's get started by filling out the form below.</p>
        </div>
        <form action="process_signup.php" method="POST" onsubmit="return validateForm()">

            <div class="mb-4">
                <label for="firstName" class="block text-sm text-gray-400">First Name</label>
                <input type="text" id="firstName" name="firstName" class="w-full p-3 mt-1 bg-gray-900 text-white rounded-lg focus:ring focus:ring-yellow-500 focus:outline-none" required>
            </div>

            <div class="mb-4">
                <label for="lastName" class="block text-sm text-gray-400">Last Name</label>
                <input type="text" id="lastName" name="lastName" class="w-full p-3 mt-1 bg-gray-900 text-white rounded-lg focus:ring focus:ring-yellow-500 focus:outline-none" required>
            </div>

            <div class="mb-4">
                <label for="country" class="block text-sm text-gray-400">Country</label>
                <select id="country" name="country" class="w-full p-3 mt-1 bg-gray-900 text-white rounded-lg focus:ring focus:ring-yellow-500 focus:outline-none" onchange="updateWhatsAppPrefix()" required>
                    <option value="India">India</option>
                    <option value="Canada">Canada</option>
                    <option value="United States">United States</option>
                </select>
            </div>

            <div class="mb-4 flex items-center">
                <span id="countryCode" class="text-white bg-gray-700 p-3 rounded-l-lg">+91</span>
                <input type="text" id="whatsAppNumber" name="whatsAppNumber" class="w-full p-3 bg-gray-900 text-white rounded-r-lg focus:ring focus:ring-yellow-500 focus:outline-none" required>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm text-gray-400">Email</label>
                <input type="email" id="email" name="email" class="w-full p-3 mt-1 bg-gray-900 text-white rounded-lg focus:ring focus:ring-yellow-500 focus:outline-none" required>
            </div>

            <div class="mb-4 relative">
                <label for="password" class="block text-sm text-gray-400">Password</label>
                <input type="password" id="password" name="password" class="w-full p-3 mt-1 bg-gray-900 text-white rounded-lg focus:ring focus:ring-yellow-500 focus:outline-none" required>
                <span class="absolute right-4 top-10 cursor-pointer" onclick="togglePasswordVisibility('password', 'passwordIcon')">
                    <i id="passwordIcon" class="fas fa-eye text-gray-400"></i> <!-- Font Awesome eye icon -->
                </span>
            </div>

            <div class="mb-4 relative">
                <label for="confirmPassword" class="block text-sm text-gray-400">Confirm Password</label>
                <input type="password" id="confirmPassword" name="confirmPassword" class="w-full p-3 mt-1 bg-gray-900 text-white rounded-lg focus:ring focus:ring-yellow-500 focus:outline-none" required>
                <span class="absolute right-4 top-10 cursor-pointer" onclick="togglePasswordVisibility('confirmPassword', 'confirmPasswordIcon')">
                    <i id="confirmPasswordIcon" class="fas fa-eye text-gray-400"></i> <!-- Font Awesome eye icon -->
                </span>
            </div>

            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-lg transition duration-200 focus:ring focus:ring-yellow-500 focus:outline-none">
                Create Account
            </button>
        </form>

        <div class="text-center mt-4 text-gray-400">
            Already have an account? <a href="login.php" class="text-green-500 hover:text-green-600">Login here</a>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/0fffda5efb.js" crossorigin="anonymous"></script>

    <script>
        
        function updateWhatsAppPrefix() {
            var countrySelect = document.getElementById('country').value;
            var countryCode = {
                'India': '+91',
                'Canada': '+1',
                'United States': '+1'
            };
            document.getElementById('countryCode').innerText = countryCode[countrySelect];
        }

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

        function validateForm() {
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('confirmPassword').value;
            if (password !== confirmPassword) {
                alert('Passwords do not match');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
