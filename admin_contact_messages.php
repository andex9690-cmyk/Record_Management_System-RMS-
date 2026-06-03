<?php
// 1. Initialize session storage immediately
session_start(); 

include 'db.php';

// 1. Protect the page: Enforce role-based authorization
if (!isset($_SESSION['username']) || ($_SESSION['role'] != 'Admin' && $_SESSION['role'] != 'Teacher')) {
    header("Location: login.html");
    exit();
}

// Ensure required tables exist for incoming contact messages, contact replies, and site settings
$conn->query("CREATE TABLE IF NOT EXISTS `contact_messages` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `fullname` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `subject` VARCHAR(200) NOT NULL,
    `message` TEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `status` ENUM('New','Read','Responded') DEFAULT 'New'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$conn->query("CREATE TABLE IF NOT EXISTS `site_settings` (
    `name` VARCHAR(100) PRIMARY KEY,
    `value` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$conn->query("CREATE TABLE IF NOT EXISTS `contact_responses` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `contact_id` INT NOT NULL,
    `response_text` TEXT NOT NULL,
    `responded_by` VARCHAR(255) NOT NULL,
    `email_sent` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `responded_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$success = '';
$error = '';

// Handle response submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'respond') {
        $message_id = intval($_POST['message_id'] ?? 0);
        $response_text = trim($_POST['response_text'] ?? '');
        $customer_email = trim($_POST['customer_email'] ?? '');

        if ($message_id <= 0 || empty($response_text) || empty($customer_email)) {
            $error = 'Please fill in all required fields.';
        } else {
            // Update message status
            $stmt = $conn->prepare("UPDATE contact_messages SET status = 'Responded' WHERE id = ?");
            $stmt->bind_param('i', $message_id);
            $stmt->execute();
            $stmt->close();

            // Store response in database
            $responded_by = $_SESSION['username'];
            $stmt = $conn->prepare("INSERT INTO contact_responses (contact_id, response_text, responded_by, email_sent) VALUES (?, ?, ?, FALSE)");
            $stmt->bind_param('iss', $message_id, $response_text, $responded_by);
            $stmt->execute();
            $response_id = $conn->insert_id;
            $stmt->close();

            // Use configured sender email from settings if available
            $from_row = $conn->query("SELECT value FROM site_settings WHERE name = 'contact_from_email' LIMIT 1");
            $from_email = 'ozonehighschool2@gmail.com';
            if ($from_row && ($fr = $from_row->fetch_assoc()) && !empty($fr['value'])) {
                $from_email = $fr['value'];
            }
            $admin_name = $_SESSION['username'];

            // Build dynamic HTML Email Body
            $body = "<html><body style='font-family: Arial, sans-serif;'>
                <h2 style='color: #002147;'>Thank you for contacting Ozone High School</h2>
                <p>Dear Valued Visitor,</p>
                <p>We received your message and our team has reviewed it. Here is our response:</p>
                <hr style='border: none; border-top: 1px solid #ddd;'>
                <h3>Our Response:</h3>
                <p style='background: #f5f7fb; padding: 15px; border-left: 4px solid #D4AF37;'>" . nl2br(htmlspecialchars($response_text)) . "</p>
                <hr style='border: none; border-top: 1px solid #ddd;'>
                <p>Best Regards,<br><strong>" . htmlspecialchars($admin_name) . "</strong><br>" . htmlspecialchars($from_email) . "<br>+251 953462733</p>
            </body></html>";

            $headers = [];
            $headers[] = 'MIME-Version: 1.0';
            $headers[] = 'Content-type: text/html; charset=UTF-8';
            $headers[] = 'From: ' . htmlspecialchars($admin_name) . ' <' . $from_email . '>';
            $headers[] = 'Reply-To: ' . htmlspecialchars($from_email);

            $email_sent = false;
            if (mail($customer_email, "Re: School Contact - We've Received Your Message", $body, implode("\r\n", $headers))) {
                $email_sent = true;
            }

            if ($email_sent) {
                $stmt = $conn->prepare("UPDATE contact_responses SET email_sent = TRUE WHERE id = ?");
                $stmt->bind_param('i', $response_id);
                $stmt->execute();
                $stmt->close();
                $success = '✓ Response saved and email sent successfully to ' . htmlspecialchars($customer_email);
            } else {
                $success = '✓ Response saved locally. Outbound email could not be sent from this server.';
            }
        }
    }
}

// Fetch all contact messages
$messages = $conn->query("SELECT id, fullname, email, subject, message, created_at, status FROM contact_messages ORDER BY created_at DESC");
$new_count = $conn->query("SELECT COUNT(*) as count FROM contact_messages WHERE status = 'New'")->fetch_assoc()['count'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Contact Messages | Ozone RMS</title>
    <link rel="stylesheet" href="styles.css" />
    <style>
        .contact-msg-table { width: 100%; border-collapse: collapse; margin-bottom: 2rem; }
        .contact-msg-table th, .contact-msg-table td { padding: 12px; text-align: left; border-bottom: 1px solid #e0e0e0; }
        .contact-msg-table th { background: #002147; color: white; font-weight: 600; }
        .contact-msg-table tr:hover { background: #f5f7fb; }
        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; }
        .status-new { background: #ffebee; color: #c62828; }
        .status-read { background: #e3f2fd; color: #1565c0; }
        .status-responded { background: #e8f5e9; color: #2e7d32; }
        .msg-content { background: #f5f7fb; padding: 15px; border-radius: 8px; margin: 10px 0; }
        .response-form { background: #fff; padding: 20px; border: 1px solid #e0e0e0; border-radius: 8px; margin-top: 15px; }
        .btn-respond { background: #0f172a; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; }
        .btn-respond:hover { background: #1e293b; }
        .contact-card { background: white; border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px; margin-bottom: 15px; }
        .contact-header { display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px; }
        .contact-info { color: #475569; font-size: 0.9rem; }
    </style>
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
                <a href="admin_contact_messages.php" class="menu-item active"><span class="menu-icon">📧</span> Contact Messages</a>
                <a href="admin_messages.php" class="menu-item"><span class="menu-icon">💬</span> Parent Communication</a>
                <a href="users_management.php" class="menu-item"><span class="menu-icon">👥</span> Users Management</a>
                <a href="admin_settings.php" class="menu-item"><span class="menu-icon">⚙️</span> System Settings</a>
            </nav>
            <div class="sidebar-footer">
                <a href="logout.php" class="logout-btn"><span>🚪</span> Logout</a>
            </div>
        </aside>

        <main class="dash-main-content">
            <header class="dash-header">
                <h1>Contact Form Messages</h1>
            </header>

            <section class="dash-welcome">
                <h2>Manage Contact Submissions</h2>
                <p>View and respond to messages sent through the contact form. <strong><?php echo $new_count; ?></strong> new message(s) awaiting response.</p>
            </section>

            <?php if ($success): ?>
                <div style="background: #e8f5e9; color: #2e7d32; padding: 12px; border-radius: 6px; margin-bottom: 1rem; border: 1px solid #4caf50;">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div style="background: #ffebee; color: #c62828; padding: 12px; border-radius: 6px; margin-bottom: 1rem; border: 1px solid #f44336;">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <section class="messages-section">
                <?php if ($messages && $messages->num_rows > 0): ?>
                    <?php while ($row = $messages->fetch_assoc()): ?>
                        <div class="contact-card">
                            <div class="contact-header">
                                <div>
                                    <h3><?php echo htmlspecialchars($row['subject']); ?></h3>
                                    <p class="contact-info">
                                        <strong>From:</strong> <?php echo htmlspecialchars($row['fullname']); ?> (<?php echo htmlspecialchars($row['email']); ?>)<br>
                                        <strong>Date:</strong> <?php echo htmlspecialchars($row['created_at']); ?>
                                    </p>
                                </div>
                                <span class="status-badge status-<?php echo strtolower($row['status']); ?>">
                                    <?php echo htmlspecialchars($row['status']); ?>
                                </span>
                            </div>

                            <div class="msg-content">
                                <?php echo nl2br(htmlspecialchars($row['message'])); ?>
                            </div>

                            <?php if ($row['status'] !== 'Responded'): ?>
                                <form method="POST" action="admin_contact_messages.php" class="response-form">
                                    <input type="hidden" name="action" value="respond">
                                    <input type="hidden" name="message_id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="customer_email" value="<?php echo htmlspecialchars($row['email']); ?>">

                                    <label for="response_<?php echo $row['id']; ?>" style="display: block; margin-bottom: 10px; font-weight: 600;">
                                        Send Response to <?php echo htmlspecialchars($row['fullname']); ?>
                                    </label>
                                    <textarea 
                                        id="response_<?php echo $row['id']; ?>" 
                                        name="response_text" 
                                        rows="4" 
                                        placeholder="Type your response here. This will be sent to their email..." 
                                        required
                                        style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-family: inherit; resize: vertical;"></textarea>
                                    <button type="submit" class="btn-respond" style="margin-top: 10px;">📧 Send Email Response</button>
                                </form>
                            <?php else: ?>
                                <p style="color: #2e7d32; font-weight: 600;">✓ Response already sent to this contact.</p>
                                <?php
                                    $resp_query = $conn->query("SELECT response_text, responded_by, responded_at, email_sent FROM contact_responses WHERE contact_id = " . $row['id'] . " ORDER BY responded_at DESC LIMIT 1");
                                    if ($resp_query && $resp_query->num_rows > 0) {
                                        $resp = $resp_query->fetch_assoc();
                                        echo "<div class='msg-content' style='margin-top: 10px;'>";
                                        echo "<p><strong>Responded by:</strong> " . htmlspecialchars($resp['responded_by']) . " on " . htmlspecialchars($resp['responded_at']) . "</p>";
                                        echo "<p><strong>Email Status:</strong> " . ($resp['email_sent'] ? "✓ Sent" : "⚠️ Not Sent (check SMTP config)") . "</p>";
                                        echo "<p><strong>Response:</strong></p>";
                                        echo "<p>" . nl2br(htmlspecialchars($resp['response_text'])) . "</p>";
                                        echo "</div>";
                                    }
                                ?>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="contact-card">
                        <p style="text-align: center; color: #475569;">No contact messages yet.</p>
                    </div>
                <?php endif; ?>
            </section>
        </main>
    </div>
    <script src="theme.js"></script>
</body>
</html>
