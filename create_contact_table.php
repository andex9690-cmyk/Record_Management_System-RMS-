<?php
session_start();

include 'db.php';

if (!isset($_SESSION['username']) || ($_SESSION['role'] != 'Admin' && $_SESSION['role'] != 'Teacher')) {
    header("Location: login.html");
    exit();
}

$success = [];
$error = [];

$queries = [
    "CREATE TABLE IF NOT EXISTS `contact_messages` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `fullname` VARCHAR(100) NOT NULL,
        `email` VARCHAR(100) NOT NULL,
        `subject` VARCHAR(200) NOT NULL,
        `message` TEXT NOT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `status` ENUM('New','Read','Responded') DEFAULT 'New'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    "CREATE TABLE IF NOT EXISTS `site_settings` (
        `name` VARCHAR(100) PRIMARY KEY,
        `value` TEXT
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    "CREATE TABLE IF NOT EXISTS `contact_responses` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `contact_id` INT NOT NULL,
        `response_text` TEXT NOT NULL,
        `responded_by` VARCHAR(255) NOT NULL,
        `email_sent` TINYINT(1) NOT NULL DEFAULT 0,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `responded_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
];

foreach ($queries as $sql) {
    if ($conn->query($sql) !== true) {
        $error[] = $conn->error;
    }
}

if (empty($error)) {
    $success[] = 'Required tables are available. Contact message workflow is ready.';
} else {
    $error[] = 'If the tables already exist, verify that the database connection is correct and that your MySQL user has sufficient privileges.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Database Setup | Ozone RMS</title>
    <link rel="stylesheet" href="styles.css" />
</head>
<body>
    <div class="container" style="max-width:680px; margin: 40px auto;">
        <h1>Contact Message Database Setup</h1>
        <?php if (!empty($success)): ?>
            <div style="background:#e8f5e9;color:#2e7d32;padding:16px;border-radius:8px;border:1px solid #c8e6c9;margin-bottom:16px;">
                <?php foreach ($success as $message): ?>
                    <p><?php echo htmlspecialchars($message); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div style="background:#ffebee;color:#c62828;padding:16px;border-radius:8px;border:1px solid #ffcdd2;margin-bottom:16px;">
                <?php foreach ($error as $message): ?>
                    <p><?php echo htmlspecialchars($message); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <p>Once the tables are created, use <a href="admin_contact_messages.php">Admin Contact Messages</a> to manage incoming contact form submissions.</p>
        <p><a href="admin.php">Return to Admin Dashboard</a></p>
    </div>
</body>
</html>
