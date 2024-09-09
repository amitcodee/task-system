<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar - Task Management</title>
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.7/main.min.css" rel="stylesheet">
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #1c1c28;
            color: #ffffff;
        }
        .fc-toolbar-title {
            color: #ffffff;
            font-size: 24px;
        }
        .fc-daygrid-day-number {
            color: #ffffff;
        }
        .task-highlight {
            background-color: #00d98c !important;
            color: #000 !important;
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

            <!-- Calendar Page Content -->
            <div class="p-4">
                <h3 class="text-white">Task Calendar</h3>
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</div>

<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.7/main.min.js"></script>
<!-- Bootstrap 5.3 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            themeSystem: 'bootstrap',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: [
                {
                    title: 'Project Deadline',
                    start: '2024-09-10',
                    className: 'task-highlight'  // Custom class for task highlighting
                },
                {
                    title: 'Meeting with Team',
                    start: '2024-09-12',
                    className: 'task-highlight'
                },
                {
                    title: 'Code Review',
                    start: '2024-09-15',
                    className: 'task-highlight'
                },
                {
                    title: 'Client Presentation',
                    start: '2024-09-18',
                    className: 'task-highlight'
                }
            ]
        });

        calendar.render();
    });
</script>

</body>
</html>
