<?php
include 'db.php';

if (!isset($_SESSION['username']) || ($_SESSION['role'] != 'Admin' && $_SESSION['role'] != 'Teacher')) {
    header("Location: login.html");
    exit();
}

$roleFilter = $_GET['role'] ?? '';
$allowedRoles = ['Parent', 'Student'];
$filterTitle = 'All Users';

$sql = "SELECT u.id, u.username, u.email, u.role, u.password,
               s.first_name, s.last_name, s.grade
        FROM users u
        LEFT JOIN students s ON s.user_id = u.id";

if ($roleFilter && in_array($roleFilter, $allowedRoles)) {
    $filterTitle = $roleFilter . ' Accounts';
    $sql .= " WHERE u.role = ?";
}
$sql .= " ORDER BY u.role, u.username";

$stmt = $conn->prepare($sql);
if ($roleFilter && in_array($roleFilter, $allowedRoles)) {
    $stmt->bind_param('s', $roleFilter);
}
$stmt->execute();
$users = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Users Management | Ozone RMS</title>
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
                <a href="users_management.php" class="menu-item active">👥 Users Management</a>
                <a href="reports.php" class="menu-item">📋 Reports</a>
                <a href="admin_settings.php" class="menu-item">⚙️ System Settings</a>
            </nav>
            <div class="sidebar-footer">
                <a href="logout.php" class="logout-btn"><span>🚪</span> Logout</a>
            </div>
        </aside>
        <main class="dash-main-content">
            <header class="dash-header">
                <h1>Users Management</h1>
            </header>
            <section class="actions-section-card">
                <div class="section-card-header">
                    <h3>Quick Account Filters</h3>
                </div>
                <div class="actions-button-stack">
                    <a href="users_management.php?role=Student" class="action-btn-secondary">👨‍🎓 Student Accounts</a>
                    <a href="users_management.php?role=Parent" class="action-btn-secondary">👪 Parent Accounts</a>
                    <a href="users_management.php" class="action-btn-secondary">👥 All Users</a>
                </div>
            </section>
            <section class="users-section">
                <h2><?php echo htmlspecialchars($filterTitle); ?></h2>
                <table class="dash-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Grade</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Password</th>

                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($users && $users->num_rows > 0): ?>
                            <?php while ($row = $users->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['grade']); ?></td>
                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['role']); ?></td>
                                    <td><?php echo htmlspecialchars($row['password'] ? '********' : ''); ?></td>
                                    <td>
                                        <div class="actions-cell">
                                            <a href="edit_user.php?id=<?php echo urlencode($row['id']); ?>&role=<?php echo urlencode($roleFilter); ?>" class="action-btn-secondary">Edit</a>
                                            <form method="POST" action="delete_user.php" class="inline-form">
                                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>" />
                                                <input type="hidden" name="return_role" value="<?php echo htmlspecialchars($roleFilter); ?>" />
                                                <button type="submit" class="action-btn-secondary" onclick="return confirm('Delete this user?');">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="9">No users found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
    <script src="theme.js"></script>
</body>
</html>