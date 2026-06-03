<?php
include 'db.php';

if (!isset($_SESSION['username']) || ($_SESSION['role'] != 'Admin' && $_SESSION['role'] != 'Teacher')) {
    header("Location: login.html");
    exit();
}

$success = '';
$error = '';

$conn->query("CREATE TABLE IF NOT EXISTS parent_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    parent_username VARCHAR(100) NOT NULL,
    sender_role VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $parent = trim($_POST['parent'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($parent) || empty($message)) {
        $error = 'Please select a parent and enter a message.';
    } else {
        $stmt = $conn->prepare("INSERT INTO parent_messages (parent_username, sender_role, message) VALUES (?, ?, ?)");
        $sender = $_SESSION['role'];
        $stmt->bind_param('sss', $parent, $sender, $message);
        if ($stmt->execute()) {
            $success = 'Message sent successfully.';
        } else {
            $error = 'Failed to send message.';
        }
        $stmt->close();
    }
}

$parents = $conn->query("SELECT username, name FROM parent ORDER BY name");
$sent_messages = $conn->query("SELECT parent_username, sender_role, message, sent_at FROM parent_messages ORDER BY sent_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Communication | Ozone RMS</title>
    <link rel="stylesheet" href="styles.css" />
</head>
<body class="dashboard-body">
    <div class="dashboard-container">
        <aside class="dash-sidebar">
            <div class="sidebar-brand">
                <h2>OZONE <span>RMS</span></h2>
            </div>
            <nav class="sidebar-menu">
                <a href="admin.php" class="menu-item"><span class="menu-icon">📊</span> Dashboard</a>
                <a href="student_records.php" class="menu-item"><span class="menu-icon">🎓</span> Student Records</a>
                <a href="admin_contact_messages.php" class="menu-item"><span class="menu-icon">📧</span> Contact Messages</a>
                <a href="admin_messages.php" class="menu-item active"><span class="menu-icon">💬</span> Parent Communication</a>
                <a href="users_management.php" class="menu-item"><span class="menu-icon">👥</span> Users Management</a>
                <a href="reports.php" class="menu-item"><span class="menu-icon">📋</span> Reports</a>
                <a href="admin_settings.php" class="menu-item"><span class="menu-icon">⚙️</span> System Settings</a>
            </nav>
            <div class="sidebar-footer">
                <a href="logout.php" class="logout-btn"><span>🚪</span> Logout</a>
            </div>
        </aside>

        <main class="dash-main-content">
            <header class="dash-header">
                <h1>Admin Communication</h1>
            </header>

            <section class="dash-welcome">
                <h2>Send a message to a parent</h2>
                <p>Use this page to communicate updates on student performance, attendance, or important notices.</p>
            </section>

            <section class="messages-section">
                <?php if ($success): ?>
                    <div style="color:green; margin-bottom:1rem;"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div style="color:red; margin-bottom:1rem;"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <form method="POST" action="admin_messages.php">
                    <div class="input-group">
                        <label for="parent">Select Parent</label>
                        <select id="parent" name="parent" required>
                            <option value="">Choose Parent</option>
                            <?php while ($row = $parents->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($row['username']); ?>">
                                    <?php echo htmlspecialchars($row['name']); ?> (<?php echo htmlspecialchars($row['username']); ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="input-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" rows="6" required placeholder="Type message to parent..."></textarea>
                    </div>

                    <button type="submit" class="btn-primary">Send Message</button>
                </form>

                <h2 style="margin-top:2rem;">Sent Messages</h2>
                <?php if ($sent_messages && $sent_messages->num_rows > 0): ?>
                    <?php while ($row = $sent_messages->fetch_assoc()): ?>
                        <div class="message-card" style="border:1px solid #ddd; padding:1rem; margin-bottom:1rem; border-radius:8px; background:#fff;">
                            <p><strong>Sender:</strong> <?php echo htmlspecialchars($row['sender_role']); ?></p>
                            <p><strong>Recipient:</strong> <?php echo htmlspecialchars($row['parent_username']); ?></p>
                            <p><strong>Sent:</strong> <?php echo htmlspecialchars($row['sent_at']); ?></p>
                            <p><?php echo nl2br(htmlspecialchars($row['message'])); ?></p>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No messages sent yet.</p>
                <?php endif; ?>
            </section>
        </main>
    </div>
    <script src="theme.js"></script>
</body>
</html>
