<?php
// 1. Initialize session storage immediately at the top
session_start(); 

include 'db.php';

// 2. Protect the page: Enforce role-based authorization
if (!isset($_SESSION['username']) || ($_SESSION['role'] !== 'Admin' && $_SESSION['role'] !== 'Teacher')) {
    header("Location: login.html");
    exit();
}

// Ensure site settings table exists
$conn->query("CREATE TABLE IF NOT EXISTS `site_settings` (
    `name` VARCHAR(100) PRIMARY KEY,
    `value` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$success = '';
$error = '';

// 3. Handle saving configuration (Sender Email) to the database
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_contact_email'])) {
    $email = trim($_POST['contact_from_email'] ?? '');
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $stmt = $conn->prepare("INSERT INTO site_settings (`name`, `value`) VALUES ('contact_from_email', ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)");
        $stmt->bind_param('s', $email);
        if ($stmt->execute()) {
            $success = 'Sender email saved.';
        } else {
            $error = 'Unable to save sender email.';
        }
        $stmt->close();
    }
}

// Read current sender configuration out of your `site_settings` table
$row = $conn->query("SELECT value FROM site_settings WHERE name = 'contact_from_email' LIMIT 1");
$current_sender = '';
if ($row && $r = $row->fetch_assoc()) {
    $current_sender = $r['value'];
}

// 4. Handle sending an actual message via Gmail SMTP
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    $recipient = trim($_POST['recipient_email'] ?? '');
    $subject = trim($_POST['email_subject'] ?? '');
    $message = trim($_POST['email_message'] ?? '');

    if (empty($current_sender)) {
        $error = 'You must configure and save a Sender Email before sending messages.';
    } elseif (!filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid recipient email address.';
    } elseif (empty($subject) || empty($message)) {
        $error = 'Subject and message fields cannot be empty.';
    } else {
        $headers = [];
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=UTF-8';
        $headers[] = 'From: Ozone RMS <' . $current_sender . '>';
        $headers[] = 'Reply-To: Ozone RMS <' . $current_sender . '>';

        $email_body = '<html><body>' . nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8')) . '</body></html>';

        if (mail($recipient, $subject, $email_body, implode("\r\n", $headers))) {
            $success = 'Message successfully sent to ' . htmlspecialchars($recipient, ENT_QUOTES, 'UTF-8');
        } else {
            $error = 'Unable to send message from the current server configuration. Please verify SMTP settings.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>System Settings | Ozone RMS</title>
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
                <a href="admin_messages.php" class="menu-item"><span class="menu-icon">💬</span> Parent Communication</a>
                <a href="users_management.php" class="menu-item"><span class="menu-icon">👥</span> Users Management</a>
                <a href="reports.php" class="menu-item"><span class="menu-icon">📋</span> Reports</a>
                <a href="admin_settings.php" class="menu-item active"><span class="menu-icon">⚙️</span> System Settings</a>
            </nav>
            <div class="sidebar-footer">
                <a href="logout.php" class="logout-btn"><span>🚪</span> Logout</a>
            </div>
        </aside>
        
        <main class="dash-main-content">
            <header class="dash-header">
                <h1>System Settings</h1>
            </header>
            <section class="settings-section">
                <h2>Mail & System Settings</h2>

                <?php if ($success): ?>
                    <div style="margin-bottom:12px; color:#2e7d32; font-weight:600;">✓ <?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div style="margin-bottom:12px; color:#c62828; font-weight:600;">✗ <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
                <?php endif; ?>

                <div style="max-width:760px; background:#fff; padding:18px; border-radius:8px; border:1px solid #e6e6e6; margin-bottom: 20px;">
                    <h3>Sender Email (used when admin replies)</h3>
                    <p style="color:#475569; margin-top:4px;">Current configured sender: <strong><?php echo $current_sender ? htmlspecialchars($current_sender, ENT_QUOTES, 'UTF-8') : 'Not set'; ?></strong></p>

                    <form method="POST" action="" style="margin-top:12px; display:flex; gap:8px; align-items:center;">
                        <input type="email" name="contact_from_email" id="contact_from_email" value="<?php echo htmlspecialchars($current_sender, ENT_QUOTES, 'UTF-8'); ?>" placeholder="admin@yourdomain.com" style="flex:1; padding:10px; border:1px solid #cbd5e1; border-radius:6px;" required />
                        <button type="submit" name="save_contact_email" class="btn-primary">Save Config</button>
                    </form>
                </div>

                <div style="max-width:760px; background:#fff; padding:18px; border-radius:8px; border:1px solid #e6e6e6;">
                    <h3>Send Outbound Email Message</h3>
                    <form method="POST" action="" style="margin-top:12px; display:flex; flex-direction:column; gap:12px;">
                        <div>
                            <label style="display:block; margin-bottom:4px; font-weight:600;">Recipient Email</label>
                            <input type="email" name="recipient_email" placeholder="parent@example.com" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;" required />
                        </div>
                        <div>
                            <label style="display:block; margin-bottom:4px; font-weight:600;">Subject</label>
                            <input type="text" name="email_subject" placeholder="Ozone RMS System Notification" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;" required />
                        </div>
                        <div>
                            <label style="display:block; margin-bottom:4px; font-weight:600;">Message Body</label>
                            <textarea name="email_message" rows="5" placeholder="Write your message details here..." style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px; font-family:inherit;" required></textarea>
                        </div>
                        <button type="submit" name="send_message" class="btn-primary" style="align-self: flex-start; padding:10px 24px;">Send Message</button>
                    </form>
                </div>

                <div style="margin-top:18px;">
                    <h3>Admin Controls</h3>
                    <p>Manage system-wide settings, user roles, and perform administrative actions.</p>
                </div>
            </section>
        </main>
    </div>
    <script src="theme.js"></script>
</body>
</html>