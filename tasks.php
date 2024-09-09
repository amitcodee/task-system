<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management - Full Page</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.9.1/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #1b1f24;
            color: #ffffff;
        }
        .sidenav {
            background-color: #2a2e35;
            height: 100vh;
            position: fixed;
            padding-top: 20px;
            width: 220px;
        }
        .sidenav a {
            padding: 10px 20px;
            display: block;
            color: white;
            text-decoration: none;
        }
        .sidenav a:hover {
            background-color: #007bff;
            border-radius: 10px;
        }
        .content {
            margin-left: 240px;
            padding: 20px;
        }
        .task-filter {
            margin-right: 5px;
            border: none;
            background-color: #333a40;
            color: #ffffff;
            padding: 8px 16px;
            border-radius: 20px;
            cursor: pointer;
        }
        .task-filter.active, .task-filter:hover {
            background-color: #007bff;
            color: #ffffff;
        }
        .add-task-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            font-size: 24px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: #007bff;
            color: #ffffff;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .modal-content {
            background-color: #2a2e35;
            border-radius: 15px;
        }
        .form-control, .form-select {
            background-color: #252a30;
            color: #ffffff;
            border: 1px solid #444b52;
            border-radius: 8px;
        }
        .btn-custom {
            background-color: #00cc66;
            color: white;
            border-radius: 8px;
            padding: 12px 20px;
            width: 100%;
        }
        .btn-custom:hover {
            background-color: #00b359;
        }
        .priority-btn {
            margin-right: 5px;
            padding: 6px 16px;
            background-color: #333a40;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            color: #fff;
        }
        .priority-btn.active {
            background-color: #00cc66;
        }
        .icon-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #333a40;
            margin-right: 15px;
            cursor: pointer;
        }
        .icon-btn:hover {
            background-color: #007bff;
        }
        .icon-btn i {
            font-size: 20px;
            color: white;
        }
        .reminder-popup {
            background-color: #252a30;
            color: #ffffff;
            padding: 15px;
            border-radius: 10px;
            display: none;
        }
        .voice-message {
            width: 100%;
            padding: 10px;
            background-color: #333a40;
            border-radius: 10px;
        }
        .modal-title {
            color: #ffffff;
            font-size: 24px;
            font-weight: 600;
        }
        @media (max-width: 768px) {
            .sidenav {
                width: 100%;
                height: auto;
                position: relative;
            }
            .content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

<div class="sidenav">
    <a href="#dashboard"><i class="bi bi-house-door"></i> Dashboard</a>
    <a href="#tasks"><i class="bi bi-clipboard"></i> My Tasks</a>
    <a href="#calendar"><i class="bi bi-calendar"></i> Calendar</a>
    <a href="#members"><i class="bi bi-people"></i> Members</a>
    <a href="#settings"><i class="bi bi-gear"></i> Settings</a>
</div>

<div class="content">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>My Tasks</h2>
        <div>
            <button class="task-filter active">Today</button>
            <button class="task-filter">This Week</button>
            <button class="task-filter">This Month</button>
        </div>
    </div>

    <!-- Tasks Section -->
    <div class="task-list">
        <!-- Dynamic Task Cards Would Go Here -->
        <div class="no-task-container text-center mt-5">
            <i class="bi bi-clipboard no-task-icon"></i>
            <div class="no-task-message">No Tasks Here</div>
            <div class="no-task-subtext">It seems that you don’t have any tasks in this list</div>
        </div>
    </div>

    <!-- Floating Action Button -->
    <button class="add-task-btn" data-bs-toggle="modal" data-bs-target="#addTaskModal">
        <i class="bi bi-plus-lg"></i>
    </button>
</div>

<!-- Task Modal -->
<div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTaskModalLabel">Assign New Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="taskForm" enctype="multipart/form-data" action="process_task.php" method="POST">
                    <!-- Task Title -->
                    <input type="text" class="form-control" name="title" placeholder="Task Title" required>

                    <!-- Task Description -->
                    <textarea class="form-control" name="description" rows="3" placeholder="Short description of the task..." required></textarea>

                    <!-- Select Users and Category -->
                    <div class="d-flex">
                        <div class="dropdown w-100 me-2">
                            <button class="btn btn-dark dropdown-toggle w-100" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span>Assign Users</span>
                            </button>
                            <ul class="dropdown-menu p-3">
                                <!-- Example Users (Use a loop to fetch from DB) -->
                                <li><input type="checkbox" name="users[]" value="Amin" id="user1"> <label for="user1" class="ms-2">Amin Khan</label></li>
                                <li><input type="checkbox" name="users[]" value="Gourav" id="user2"> <label for="user2" class="ms-2">Gourav Gupta</label></li>
                            </ul>
                        </div>

                        <select class="form-select w-100" name="category" required>
                            <option value="" disabled>Select Category</option>
                            <option value="development">Development</option>
                            <option value="design">Design</option>
                            <option value="marketing">Marketing</option>
                        </select>
                    </div>

                    <!-- Priority Selection -->
                    <label class="form-label mt-3">Priority</label>
                    <div class="d-flex">
                        <button type="button" class="priority-btn active" onclick="setPriority('high')">High</button>
                        <button type="button" class="priority-btn" onclick="setPriority('medium')">Medium</button>
                        <button type="button" class="priority-btn" onclick="setPriority('low')">Low</button>
                    </div>

                    <!-- Repeat Toggle and Due Date -->
                    <div class="d-flex mt-3">
                        <div class="form-check form-switch me-3">
                            <input class="form-check-input" type="checkbox" name="repeat_monthly" id="repeatMonthly">
                            <label class="form-check-label" for="repeatMonthly">Repeat</label>
                        </div>

                        <input type="date" class="form-control" name="due_date" required>
                    </div>

                    <!-- Links, Files, Reminders, Voice Message -->
                    <div class="d-flex mt-3">
                        <div class="icon-btn" onclick="addInputField('link')"><i class="bi bi-link"></i></div>
                        <div class="icon-btn" onclick="document.getElementById('taskFile').click()"><i class="bi bi-file-earmark"></i></div>
                        <div class="icon-btn" onclick="toggleReminderPopup()"><i class="bi bi-bell"></i></div>
                        <div class="icon-btn" onclick="recordVoiceMessage()"><i class="bi bi-mic"></i></div>
                    </div>

                    <!-- Extra Input Fields -->
                    <div id="extraFields" class="mt-3"></div>

                    <input type="file" id="taskFile" name="task_files[]" multiple style="display: none;">

                    <!-- Voice Message -->
                    <div class="voice-message mt-3" id="voiceMessage" style="display: none;">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Recording...</span>
                            <button class="btn btn-danger btn-sm" onclick="stopRecording()">Stop</button>
                        </div>
                        <div class="mt-2" style="background: #007bff; height: 2px; width: 100%;"></div>
                    </div>

                    <!-- Reminder Popup -->
                    <div class="reminder-popup" id="reminderPopup">
                        <div class="d-flex">
                            <select class="form-select me-2">
                                <option value="email">Email</option>
                                <option value="notification">Notification</option>
                            </select>
                            <input type="number" class="form-control me-2" min="1" max="60" placeholder="10">
                            <select class="form-select">
                                <option value="minutes">Minutes</option>
                                <option value="hours">Hours</option>
                                <option value="days">Days</option>
                            </select>
                        </div>
                        <button class="btn btn-custom mt-3" onclick="saveReminder()">Save Reminder</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-custom">Assign Task</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5.3 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Add additional input fields (links, reminders, etc.)
    function addInputField(type) {
        let html = '';
        if (type === 'link') {
            html = `<div class="input-group mt-2">
                        <input type="url" class="form-control" name="links[]" placeholder="Link">
                    </div>`;
        }
        document.getElementById('extraFields').insertAdjacentHTML('beforeend', html);
    }

    function toggleReminderPopup() {
        const popup = document.getElementById('reminderPopup');
        popup.style.display = popup.style.display === 'none' ? 'block' : 'none';
    }

    function saveReminder() {
        // Save reminder logic here
        alert('Reminder saved');
        toggleReminderPopup();
    }

    function recordVoiceMessage() {
        const voiceMessage = document.getElementById('voiceMessage');
        voiceMessage.style.display = 'block';
        // Add actual recording logic here
    }

    function stopRecording() {
        const voiceMessage = document.getElementById('voiceMessage');
        voiceMessage.style.display = 'none';
        alert('Recording stopped');
    }
</script>

</body>
</html>
