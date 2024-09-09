<nav class="h-screen bg-gray-800 w-64 flex-shrink-0 hidden lg:block">
    <div class="px-6 py-4">
        <!-- Logo or Header -->
        <h1 class="text-2xl font-bold text-white">Automate Business</h1>
    </div>
    <div class="px-6 py-4">
        <!-- Navigation Links -->
        <ul class="space-y-4">
            <li>
                <a href="dashboard.php" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg">
                    <i class="fas fa-tachometer-alt mr-3"></i> 
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="create_task.php" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg">
                    <i class="fas fa-tasks mr-3"></i> 
                    <span>Create Task</span>
                </a>
            </li>
            <li>
                <a href="add_member.php" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg">
                    <i class="fas fa-user-plus mr-3"></i> 
                    <span>Add Member</span>
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg">
                    <i class="fas fa-question-circle mr-3"></i> 
                    <span>Help</span>
                </a>
            </li>
        </ul>
    </div>
</nav>

<!-- Mobile Menu Button -->
<div class="lg:hidden flex justify-end p-4">
    <button id="menu-toggle" class="text-white focus:outline-none">
        <i class="fas fa-bars fa-lg"></i>
    </button>
</div>

<!-- Mobile Navigation (Hidden by default) -->
<nav id="mobile-menu" class="fixed inset-0 bg-gray-900 bg-opacity-75 z-50 hidden">
    <div class="flex flex-col h-full">
        <div class="flex justify-between items-center p-6">
            <h1 class="text-2xl font-bold text-white">Automate Business</h1>
            <button id="menu-close" class="text-white focus:outline-none">
                <i class="fas fa-times fa-lg"></i>
            </button>
        </div>
        <div class="px-6 py-4">
            <ul class="space-y-4">
                <li>
                    <a href="dashboard.php" class="block text-white px-3 py-2 hover:bg-gray-700 rounded-lg">Dashboard</a>
                </li>
                <li>
                    <a href="create_task.php" class="block text-white px-3 py-2 hover:bg-gray-700 rounded-lg">Create Task</a>
                </li>
                <li>
                    <a href="add_member.php" class="block text-white px-3 py-2 hover:bg-gray-700 rounded-lg">Add Member</a>
                </li>
                <li>
                    <a href="#" class="block text-white px-3 py-2 hover:bg-gray-700 rounded-lg">Help</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script>
    // Toggle the mobile menu
    document.getElementById('menu-toggle').addEventListener('click', function() {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });

    // Close the mobile menu
    document.getElementById('menu-close').addEventListener('click', function() {
        document.getElementById('mobile-menu').classList.add('hidden');
    });
</script>
