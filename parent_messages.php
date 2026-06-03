<?php
include 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Parent') {
    header("Location: login.html");
    exit();
}

$parent_username = $_SESSION['username'];
$success = '';
$error = '';

$admin_username = 'admin';
$admin_result = $conn->query("SELECT username FROM users WHERE role = 'Admin' LIMIT 1");
if ($admin_result && $admin_result->num_rows > 0) {
    $admin_row = $admin_result->fetch_assoc();
    if (!empty($admin_row['username'])) {
        $admin_username = $admin_row['username'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply_message'])) {
    $reply_message = trim($_POST['reply_message'] ?? '');

    if (empty($reply_message)) {
        $error = 'Please enter a message.';
    } else {
        $stmt = $conn->prepare("INSERT INTO parent_messages (parent_username, sender_role, message) VALUES (?, ?, ?)");
        $sender_role = 'Parent';
        $stmt->bind_param('sss', $admin_username, $sender_role, $reply_message);

        if ($stmt->execute()) {
            $success = 'Message sent to admin successfully.';
        } else {
            $error = 'Failed to send message to admin.';
        }

        $stmt->close();
    }
}

$messages = $conn->prepare("SELECT sender_role, message, sent_at FROM parent_messages WHERE parent_username = ? ORDER BY sent_at DESC");
$messages->bind_param('s', $parent_username);
$messages->execute();
$result = $messages->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Messages | Ozone RMS</title>
    <link rel="stylesheet" href="styles.css" />
</head>
<body class="dashboard-body">
    <div class="dashboard-container">
        <aside class="dash-sidebar">
            <div class="sidebar-brand">
                <h2>OZONE <span>RMS</span></h2>
            </div>
            <nav class="sidebar-menu">
                <a href="parent.php" class="menu-item">Family Overview</a>
                    <a href="parent_academic.php" class="menu-item"><span class="menu-icon">📈</span> Academic Progress</a>
                    <a href="parent_attendance.php" class="menu-item"><span class="menu-icon">📅</span> Attendance</a>
                    <a href="parent_calendar.php" class="menu-item"><span class="menu-icon">📆</span> Calendar</a>
                    <a href="parent_messages.php" class="menu-item active"><span class="menu-icon">💬</span> Messages</a>
            </nav>
            <div class="sidebar-footer">
                <a href="logout.php" class="logout-btn"><span>🚪</span> Sign Out</a>
            </div>
        </aside>
        <main class="dash-main-content">
            <header class="dash-header">
                <h1>Messages</h1>
            </header>
            <section class="messages-section">
                <h2>Messages sent to you</h2>
                <?php if ($success): ?>
                    <div style="color:green; margin-bottom:1rem;"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div style="color:red; margin-bottom:1rem;"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <form method="POST" action="parent_messages.php" style="margin-bottom:2rem;">
                    <div class="input-group">
                        <label for="reply_message">Send a message to admin</label>
                        <textarea id="reply_message" name="reply_message" rows="5" required placeholder="Type your reply to the admin..."></textarea>
                    </div>
                    <button type="submit" class="btn-primary">Send Reply</button>
                </form>

                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="message-card" style="border:1px solid #ddd; padding:1rem; margin-bottom:1rem; border-radius:8px; background:#fff;">
                            <p><strong>From:</strong> <?php echo htmlspecialchars($row['sender_role']); ?></p>
                            <p><strong>Sent:</strong> <?php echo htmlspecialchars($row['sent_at']); ?></p>
                            <p><?php echo nl2br(htmlspecialchars($row['message'])); ?></p>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No messages yet.</p>
                <?php endif; ?>
            </section>
        </main>
    </div>
</body>
</html>
