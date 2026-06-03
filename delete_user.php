<?php
include 'db.php';

if (!isset($_SESSION['username']) || ($_SESSION['role'] != 'Admin' && $_SESSION['role'] != 'Teacher')) {
    header('Location: login.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: users_management.php');
    exit();
}

$id = intval($_POST['id'] ?? 0);
$returnRole = $_POST['return_role'] ?? '';

if ($id > 0) {
    $stmt = $conn->prepare('DELETE FROM users WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
}

$redirect = 'users_management.php';
if ($returnRole) {
    $redirect .= '?role=' . urlencode($returnRole);
}
header('Location: ' . $redirect);
exit();
