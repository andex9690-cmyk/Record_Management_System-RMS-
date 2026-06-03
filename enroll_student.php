<?php
include 'db.php';

if (!isset($_SESSION['username']) || ($_SESSION['role'] != 'Admin' && $_SESSION['role'] != 'Teacher')) {
    header("Location: login.html");
    exit();
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role = 'student';

    if (empty($username) || empty($password)) {
        $error = 'All fields are required.';
    } else {
        $stmt = $conn->prepare("INSERT INTO student (username, password, role) VALUES (?, ?, ?)");
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param('sss', $username, $hashed, $role);
        if ($stmt->execute()) {
            $success = 'Student enrolled successfully!';
        } else {
            $error = 'Failed to enroll student.';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Enroll New Student | Ozone RMS</title>
    <link rel="stylesheet" href="styles.css" />
</head>
<body class="dashboard-body">
    <div class="dashboard-container">
        <main class="dash-main-content">
            <header class="dash-header">
                <h1>Enroll New Student</h1>
            </header>
            <section class="form-section">
                <?php if ($success): ?>
                    <div style="color:green; margin-bottom:1rem;"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div style="color:red; margin-bottom:1rem;"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <form method="POST" action="enroll_student.php">
                    <div class="input-group">
                        <label for="username">Student Username</label>
                        <input type="text" id="username" name="username" required />
                    </div>
                    <div class="input-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required />
                    </div>
                    <button type="submit" class="btn-primary">Enroll Student</button>
                </form>
            </section>
        </main>
    </div>
    <script src="theme.js"></script>
</body>
</html>