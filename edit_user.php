<?php
include 'db.php';

if (!isset($_SESSION['username']) || ($_SESSION['role'] != 'Admin' && $_SESSION['role'] != 'Teacher')) {
    header('Location: login.html');
    exit();
}

$id = intval($_GET['id'] ?? 0);
$returnRole = $_GET['role'] ?? '';
$allowedRoles = ['Parent', 'Student'];
$error = '';
$success = '';
$user = null;

if ($id <= 0) {
    $error = 'Invalid user selected.';
} else {
    $stmt = $conn->prepare('SELECT u.id, u.username, u.email, u.role, u.password, s.first_name, s.last_name, s.grade
                             FROM users u
                             LEFT JOIN students s ON s.user_id = u.id
                             WHERE u.id = ? LIMIT 1');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if (!$user) {
        $error = 'User not found.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user) {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = trim($_POST['role'] ?? $user['role']);
    $firstName = trim($_POST['first_name'] ?? $user['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? $user['last_name'] ?? '');
    $grade = trim($_POST['grade'] ?? $user['grade'] ?? '');
    $newPassword = trim($_POST['password'] ?? '');

    if ($username === '' || $email === '') {
        $error = 'Username and email are required.';
    } else {
        $stmt = $conn->prepare('UPDATE users SET username = ?, email = ?, role = ?' . ($newPassword !== '' ? ', password = ?' : '') . ' WHERE id = ?');
        if ($newPassword !== '') {
            $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt->bind_param('ssssi', $username, $email, $role, $hashed, $id);
        } else {
            $stmt->bind_param('sssi', $username, $email, $role, $id);
        }

        if ($stmt->execute()) {
            $stmt->close();

            $studentStmt = $conn->prepare('INSERT INTO students (user_id, first_name, last_name, grade) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE first_name = VALUES(first_name), last_name = VALUES(last_name), grade = VALUES(grade)');
            $studentStmt->bind_param('isss', $id, $firstName, $lastName, $grade);
            $studentStmt->execute();
            $studentStmt->close();

            $success = 'User updated successfully.';
            $user['username'] = $username;
            $user['email'] = $email;
            $user['role'] = $role;
            $user['first_name'] = $firstName;
            $user['last_name'] = $lastName;
            $user['grade'] = $grade;
        } else {
            $error = 'Failed to update user.';
        }
        if (isset($stmt) && $stmt->ping()) { $stmt->close(); }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User | Ozone RMS</title>
    <link rel="stylesheet" href="styles.css" />
</head>
<body class="dashboard-body">
    <div class="dashboard-container">
        <main class="dash-main-content">
            <header class="dash-header">
                <h1>Edit User</h1>
            </header>
            <section class="table-section-card">
                <?php if ($error): ?>
                    <div style="color: #ef4444; margin-bottom: 1rem;"><?php echo htmlspecialchars($error); ?></div>
                <?php elseif ($success): ?>
                    <div style="color: #16a34a; margin-bottom: 1rem;"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>
                <?php if ($user): ?>
                    <form method="POST" action="edit_user.php?id=<?php echo urlencode($id); ?>&role=<?php echo urlencode($returnRole); ?>">
                        <div class="input-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" required value="<?php echo htmlspecialchars($user['username']); ?>">
                        </div>
                        <div class="input-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($user['email']); ?>">
                        </div>
                        <div class="input-group">
                            <label for="first_name">First Name</label>
                            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>">
                        </div>
                        <div class="input-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>">
                        </div>
                        <div class="input-group">
                            <label for="grade">Grade</label>
                            <input type="text" id="grade" name="grade" value="<?php echo htmlspecialchars($user['grade'] ?? ''); ?>">
                        </div>
                        <div class="input-group">
                            <label for="password">New Password</label>
                            <input type="password" id="password" name="password" placeholder="Leave blank to keep current password">
                        </div>
                        <div class="input-group">
                            <label for="role">Role</label>
                            <select id="role" name="role">
                                <option value="Student"<?php echo $user['role'] === 'Student' ? ' selected' : ''; ?>>Student</option>
                                <option value="Parent"<?php echo $user['role'] === 'Parent' ? ' selected' : ''; ?>>Parent</option>
                                <option value="Admin"<?php echo $user['role'] === 'Admin' ? ' selected' : ''; ?>>Admin</option>
                            </select>
                        </div>
                        <button type="submit" class="action-btn-primary">Save Changes</button>
                        <a href="users_management.php<?php echo $returnRole ? '?role=' . urlencode($returnRole) : ''; ?>" class="action-btn-secondary" style="display:inline-block; margin-left:10px;">Back</a>
                    </form>
                <?php endif; ?>
            </section>
        </main>
    </div>
    <script src="theme.js"></script>
</body>
</html>
