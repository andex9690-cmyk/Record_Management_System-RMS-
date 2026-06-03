<?php
include 'db.php';

if (!isset($_SESSION['username']) || ($_SESSION['role'] != 'Admin' && $_SESSION['role'] != 'Teacher')) {
    header("Location: login.html");
    exit();
}

$students = $conn->query("SELECT username, role FROM student");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Reports | Ozone RMS</title>
    <link rel="stylesheet" href="styles.css" />
</head>
<body class="dashboard-body">
    <div class="dashboard-container">
        <aside class="dash-sidebar">
            <div class="sidebar-brand">
                <h2>OZONE <span>RMS</span></h2>
            </div>
            <nav class="sidebar-menu">
                <a href="admin.php" class="menu-item">Dashboard</a>
                <a href="student_records.php" class="menu-item">🎓 Student Records</a>
                <a href="admin_messages.php" class="menu-item">💬 Reply Messages</a>
                <a href="users_management.php" class="menu-item">👥 Users Management</a>
                <a href="reports.php" class="menu-item active">📋 Reports</a>
                <a href="admin_settings.php" class="menu-item">⚙️ System Settings</a>
            </nav>
            <div class="sidebar-footer">
                <a href="logout.php" class="logout-btn"><span>🚪</span> Logout</a>
            </div>
        </aside>
        <main class="dash-main-content">
            <header class="dash-header">
                <h1>Reports</h1>
            </header>
            <section class="reports-section">
                <h2>Student Reports</h2>
                <table class="dash-table">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($students && $students->num_rows > 0): ?>
                            <?php while ($row = $students->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td><?php echo htmlspecialchars($row['role']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="2">No reports available.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
    <script src="theme.js"></script>
</body>
</html>