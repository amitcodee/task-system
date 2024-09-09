<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members - Task Management</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #1c1c28;
            color: #ffffff;
        }
        .member-container {
            padding: 30px;
        }
        .filter-btn {
            background-color: #333;
            color: white;
            font-size: 14px;
            border: none;
            padding: 8px 15px;
            margin: 5px;
            border-radius: 25px;
            cursor: pointer;
        }
        .filter-btn.active {
            background-color: #00d98c;
        }
        .search-bar {
            background-color: #333;
            border-radius: 10px;
            padding: 10px;
            border: none;
            color: white;
        }
        .member-list {
            background-color: #2c2c38;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .member-details {
            display: flex;
            align-items: center;
        }
        .member-profile {
            background-color: #00d98c;
            width: 40px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            font-size: 18px;
            color: white;
            margin-right: 15px;
        }
        .member-info {
            margin-right: 20px;
        }
        .member-info small {
            display: block;
            color: #aaa;
        }
        .role-badge {
            background-color: #444;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 14px;
        }
        .role-badge.admin {
            background-color: #007bff;
        }
        .role-badge.manager {
            background-color: #ffc107;
        }
        .role-badge.team-member {
            background-color: #00d98c;
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

            <!-- Member Page Content -->
            <div class="member-container">
                <!-- Filter Buttons -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex">
                        <button class="filter-btn active">All</button>
                        <button class="filter-btn">Admin</button>
                        <button class="filter-btn">Manager</button>
                        <button class="filter-btn">Team Member</button>
                        <select class="form-select filter-btn bg-dark text-white">
                            <option>Reporting Manager</option>
                            <option>Department Head</option>
                        </select>
                    </div>
                    <div class="input-group" style="width: 300px;">
                        <input type="text" class="form-control search-bar" placeholder="Search Team Member">
                    </div>
                </div>

                <h5>4 Members</h5>

                <!-- Member List -->
                <div class="member-list">
                    <div class="member-details">
                        <div class="member-profile">AK</div>
                        <div class="member-info">
                            <h6>Amit Kumar</h6>
                            <small><i class="bi bi-envelope"></i> amithsp@techcadd.com</small>
                            <small><i class="bi bi-telephone"></i> 7710575371</small>
                        </div>
                        <small class="text-muted"><i class="bi bi-people"></i> Shilpa Gupta</small>
                    </div>
                    <span class="role-badge team-member">Team Member</span>
                </div>

                <div class="member-list">
                    <div class="member-details">
                        <div class="member-profile">GG</div>
                        <div class="member-info">
                            <h6>Gourav Gupta</h6>
                            <small><i class="bi bi-envelope"></i> techcaddcomputereducation@gmail.com</small>
                            <small><i class="bi bi-telephone"></i> 9780000982</small>
                        </div>
                        <small class="text-muted"><i class="bi bi-people"></i> -</small>
                    </div>
                    <span class="role-badge admin">Admin</span>
                </div>

                <div class="member-list">
                    <div class="member-details">
                        <div class="member-profile">SG</div>
                        <div class="member-info">
                            <h6>Shilpa Gupta</h6>
                            <small><i class="bi bi-envelope"></i> shilpaaatri@gmail.com</small>
                            <small><i class="bi bi-telephone"></i> 7657801982</small>
                        </div>
                        <small class="text-muted"><i class="bi bi-people"></i> Gourav Gupta</small>
                    </div>
                    <span class="role-badge manager">Manager</span>
                </div>

                <div class="member-list">
                    <div class="member-details">
                        <div class="member-profile">SS</div>
                        <div class="member-info">
                            <h6>Surbi Sharma</h6>
                            <small><i class="bi bi-envelope"></i> surbi.techcadd@gmail.com</small>
                            <small><i class="bi bi-telephone"></i> 9877801679</small>
                        </div>
                        <small class="text-muted"><i class="bi bi-people"></i> Shilpa Gupta</small>
                    </div>
                    <span class="role-badge team-member">Team Member</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5.3 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.9.1/font/bootstrap-icons.min.css">

</body>
</html>
