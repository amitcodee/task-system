<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if session variables are set and fallback if not
$first_name = isset($_SESSION['first_name']) ? $_SESSION['first_name'] : 'Unknown';

// Extract initials from the first and last name
$initials = strtoupper($first_name[0]) ;
?>

<!-- header.php -->
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Dashboard</h1>

    <!-- Profile Section -->
    <div class="relative">
        <!-- Name and Profile Avatar -->
        <div class="flex items-center space-x-4 cursor-pointer" onclick="toggleDropdown()">
            <div class="text-white">
                <span class="font-bold"><?= htmlspecialchars($first_name); ?></span>
            </div>
            <!-- Dynamic avatar with initials -->
            <div class="rounded-full bg-gray-800 h-10 w-10 flex items-center justify-center text-white">
                <?= $initials ?>
            </div>
        </div>

        <!-- Dropdown Menu (Hidden by default) -->
        <div id="profileDropdown" class="absolute right-0 mt-2 w-48 bg-gray-800 text-white rounded-lg shadow-lg hidden">
            <a href="profile.php" class="block px-4 py-2 text-sm hover:bg-gray-700">Profile</a>
            <hr class="border-gray-700">
            <a href="logout.php" class="block px-4 py-2 text-sm hover:bg-gray-700">Logout</a>
        </div>
    </div>
</div>

<script>
    // Function to toggle the visibility of the dropdown
    function toggleDropdown() {
        var dropdown = document.getElementById('profileDropdown');
        dropdown.classList.toggle('hidden');
    }

    // Optional: Close the dropdown if the user clicks outside of it
    window.onclick = function(event) {
        if (!event.target.closest('.relative')) {
            var dropdown = document.getElementById('profileDropdown');
            if (!dropdown.classList.contains('hidden')) {
                dropdown.classList.add('hidden');
            }
        }
    }
</script>
