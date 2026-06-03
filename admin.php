<?php
include 'db.php';

if (!isset($_SESSION['username']) || ($_SESSION['role'] != 'Admin' && $_SESSION['role'] != 'Teacher')) {
    header("Location: login.html");
    exit();
}

// Ensure required tables exist
$conn->query("CREATE TABLE IF NOT EXISTS `students` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `user_id` INT UNIQUE,
    `first_name` VARCHAR(50),
    `last_name` VARCHAR(50),
    `grade` VARCHAR(10),
    `gpa` DECIMAL(3,2),
    `enrollment_date` DATE,
    `attendance_rate` DECIMAL(5,2),
    `absences` INT DEFAULT 0,
    `active_courses` INT DEFAULT 6,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)");

// Get dashboard statistics from database

// 1. Total Students Count
$result_students = $conn->query("SELECT COUNT(*) as count FROM students");
$total_students = ($result_students && $result_students->num_rows > 0) ? $result_students->fetch_assoc()['count'] : 0;

// 2. Average Attendance Rate
$result_attendance = $conn->query("SELECT AVG(attendance_rate) as avg_attendance FROM students WHERE attendance_rate > 0");
$avg_attendance = 0;
if ($result_attendance && $result_attendance->num_rows > 0) {
    $row = $result_attendance->fetch_assoc();
    $avg_attendance = $row['avg_attendance'] ? round($row['avg_attendance'], 1) : 0;
}

// 3. Pending Applications (New users without student records or unverified enrollments)
$result_pending = $conn->query("SELECT COUNT(u.id) as count FROM users u 
                                LEFT JOIN students s ON u.id = s.user_id 
                                WHERE u.role = 'Student' AND s.id IS NULL");
$pending_applications = ($result_pending && $result_pending->num_rows > 0) ? $result_pending->fetch_assoc()['count'] : 0;

// 4. System Health (check if database is operational - 100% if connection is good)
$system_health = 100;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Dashboard | Ozone RMS</title>
    <link rel="stylesheet" href="styles.css" />
</head>
<body class="dashboard-body">

    <div class="dashboard-container">
        <aside class="dash-sidebar">
            <div class="sidebar-brand">
                <h2>OZONE <span>RMS</span></h2>
            </div>
            <nav class="sidebar-menu">
                <a href="admin.php" class="menu-item active"><span class="menu-icon">📊</span> Dashboard</a>
                <a href="student_records.php" class="menu-item"><span class="menu-icon">🎓</span> Student Records</a>
                <a href="admin_contact_messages.php" class="menu-item"><span class="menu-icon">📧</span> Contact Messages</a>
                <a href="admin_messages.php" class="menu-item"><span class="menu-icon">💬</span> Reply Messages</a>
                <a href="users_management.php" class="menu-item"><span class="menu-icon">👥</span> Users Management</a>
                <a href="reports.php" class="menu-item"><span class="menu-icon">📋</span> Reports</a>
                <a href="admin_settings.php" class="menu-item"><span class="menu-icon">⚙️</span> System Settings</a>
            </nav>
            <div class="sidebar-footer">
                <a href="index.html" class="logout-btn"><span>🚪</span> Logout</a>
            </div>
        </aside>

        <main class="dash-main-content">
            <header class="dash-header"> 
                <div class="header-search">
                    <span class="search-icon">🔍</span>
                    <input type="search" placeholder="Search records, students, or staff...">
                </div>
                <div class="admin-profile">
                    <div class="admin-info">
                        <span class="admin-name"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        <span class="admin-role"><?php echo htmlspecialchars($_SESSION['role']); ?></span>
                    </div>
                    <div class="admin-avatar"><?php echo strtoupper(substr($_SESSION['username'], 0, 2)); ?></div>
                </div>
            </header>

            <section class="dash-welcome">
                <h1>Welcome Back, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
                <p>System operational status is normal. Here is your institutional snapshot for today.</p>
            </section>

            <section class="metrics-grid">
                <div class="metric-card">
                    <div class="metric-header">
                        <span class="metric-title">Total Students</span>
                        <span class="metric-badge up">▲ Active</span>
                    </div>
                    <div class="metric-value"><?php echo $total_students; ?></div>
                    <div class="metric-footer">Active enrollment this term</div>
                </div>

                <div class="metric-card">
                    <div class="metric-header">
                        <span class="metric-title">Attendance Rate</span>
                        <span class="metric-badge stable">Average</span>
                    </div>
                    <div class="metric-value"><?php echo $avg_attendance > 0 ? $avg_attendance . '%' : 'N/A'; ?></div>
                    <div class="metric-footer">Daily average aggregate</div>
                </div>

                <!--<div class="metric-card">
                    <div class="metric-header">
                        <span class="metric-title">Pending Applications</span>
                        <span class="metric-badge warning">Awaiting Setup</span>
                    </div>
                    <div class="metric-value"><?php echo $pending_applications; ?></div>
                    <div class="metric-footer">Requires verification review</div>
                </div> -->

                <div class="metric-card">
                    <div class="metric-header">
                        <span class="metric-title">System Health</span>
                        <span class="metric-badge secure">Operational</span>
                    </div>
                    <div class="metric-value"><?php echo $system_health; ?>%</div>
                    <div class="metric-footer">All systems online</div>
                </div>
            </section>

            <div class="dash-data-split">
                <section class="table-section-card">
                    <div class="section-card-header">
                        <h3>Student Records</h3>
                        <a href="student_records.php" class="view-all-link">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="dash-table">
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>Grade</th>
                                    <th>GPA</th>
                                    <th>Attendance</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $result = $conn->query("SELECT s.id, CONCAT(s.first_name, ' ', s.last_name) as fullname, 
                                                                 s.grade, s.gpa, s.attendance_rate 
                                                           FROM students s 
                                                           ORDER BY s.enrollment_date DESC 
                                                           LIMIT 5");
                                    if ($result && $result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $attendance = $row['attendance_rate'] ? $row['attendance_rate'] . '%' : 'N/A';
                                            $gpa = $row['gpa'] ? number_format($row['gpa'], 2) : 'N/A';
                                            $status = $row['attendance_rate'] >= 80 ? 'Active' : 'Warning';
                                            $status_color = $status === 'Active' ? 'verified' : 'warning';
                                            echo "<tr>";
                                            echo "<td><strong>" . htmlspecialchars($row['fullname']) . "</strong></td>";
                                            echo "<td>" . htmlspecialchars($row['grade'] ?? '—') . "</td>";
                                            echo "<td>" . $gpa . "</td>";
                                            echo "<td>" . $attendance . "</td>";
                                            echo "<td><span class='status-pill " . $status_color . "'>" . $status . "</span></td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='5'>No student records found. <a href='setup_database.php'>Initialize database</a></td></tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="actions-section-card">
                    <div class="section-card-header">
                        <h3>Quick Actions</h3>
                    </div>
                    <div class="actions-button-stack">
                        <a href="enroll_student.php" class="action-btn-primary">➕ Enroll New Student</a>
                        <a href="users_management.php?role=Student" class="action-btn-secondary">👨‍🎓 Manage Students</a>
                        <a href="users_management.php?role=Parent" class="action-btn-secondary">👪 Manage Parents</a>
                        <a href="generate_report.php" class="action-btn-secondary">📝 Generate Report</a>
                        <a href="admin_settings.php" class="action-btn-secondary">⚙️ System Settings</a>
                    </div>
                </section>
            </div>
        </main>
    </div>

</body>
    <script src="theme.js"></script>
</html>
