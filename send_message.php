<?php
// 1. MUST start the session immediately to use $_SESSION alerts and old form values
session_start(); 

include 'db.php';

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact.php');
    exit;
}

// Extract and sanitize input
$fullname = trim($_POST['fullname'] ?? '');
$email = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

// Cache old values to pass back to the form if validation fails
$old_values = [
    'fullname' => $fullname,
    'email' => $email,
    'subject' => $subject,
    'message' => $message
];

// Validate empty fields
if (empty($fullname) || empty($email) || empty($subject) || empty($message)) {
    $_SESSION['error_message'] = 'Please fill in all fields before sending your message.';
    $_SESSION['old_values'] = $old_values;
    header('Location: contact.php');
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error_message'] = 'Please enter a valid email address.';
    $_SESSION['old_values'] = $old_values;
    header('Location: contact.php');
    exit;
}

// Ensure table structure exists dynamically
$create_table_sql = "CREATE TABLE IF NOT EXISTS `contact_messages` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `fullname` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `subject` VARCHAR(200) NOT NULL,
    `message` TEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `status` ENUM('New','Read','Responded') DEFAULT 'New'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
$conn->query($create_table_sql);

// Save submission via Prepared Statement
$stmt = $conn->prepare("INSERT INTO contact_messages (fullname, email, subject, message) VALUES (?, ?, ?, ?)");
if ($stmt === false) {
    $_SESSION['error_message'] = 'Unable to store your message right now. Please try again later.';
    $_SESSION['old_values'] = $old_values;
    header('Location: contact.php');
    exit;
}

$stmt->bind_param('ssss', $fullname, $email, $subject, $message);

if ($stmt->execute()) {
    $_SESSION['success_message'] = 'Message sent successfully! Thank you for contacting Ozone High School. We will respond to you soon.';
    unset($_SESSION['old_values']); // Clear saved form field text on complete success
} else {
    $_SESSION['error_message'] = 'Error sending message. Please try again later.';
    $_SESSION['old_values'] = $old_values;
}

$stmt->close();

// Redirect back to display success/error alerts
header('Location: contact.php');
exit;
/*include 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact.php');
    exit;
}

$fullname = trim($_POST['fullname'] ?? '');
$email = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

$old_values = [
    'fullname' => $fullname,
    'email' => $email,
    'subject' => $subject,
    'message' => $message
];

if (empty($fullname) || empty($email) || empty($subject) || empty($message)) {
    $_SESSION['error_message'] = 'Please fill in all fields before sending your message.';
    $_SESSION['old_values'] = $old_values;
    header('Location: contact.php');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error_message'] = 'Please enter a valid email address.';
    $_SESSION['old_values'] = $old_values;
    header('Location: contact.php');
    exit;
}

$create_table_sql = "CREATE TABLE IF NOT EXISTS `contact_messages` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `fullname` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `subject` VARCHAR(200) NOT NULL,
    `message` TEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `status` ENUM('New','Read','Responded') DEFAULT 'New'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
$conn->query($create_table_sql);

$stmt = $conn->prepare("INSERT INTO contact_messages (fullname, email, subject, message) VALUES (?, ?, ?, ?)");
if ($stmt === false) {
    $_SESSION['error_message'] = 'Unable to store your message right now. Please try again later.';
    $_SESSION['old_values'] = $old_values;
    header('Location: contact.php');
    exit;
}

$stmt->bind_param('ssss', $fullname, $email, $subject, $message);

if ($stmt->execute()) {
    $_SESSION['success_message'] = 'Message sent successfully! Thank you for contacting Ozone High School. We will respond to you soon.';
    unset($_SESSION['old_values']);
} else {
    $_SESSION['error_message'] = 'Error sending message. Please try again later.';
    $_SESSION['old_values'] = $old_values;
}

$stmt->close();
header('Location: contact.php');
exit;*/
