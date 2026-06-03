<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $role = isset($_POST['role']) ? trim($_POST['role']) : '';

    if (empty($username) || empty($password) || empty($role)) {
        $error = "All fields are required.";
    } else {
        $roleKey = strtolower($role);
        $roleName = ucfirst(strtolower($role));
        $stmt = null;

        if (in_array($roleName, ['Admin', 'Teacher', 'Student', 'Parent'], true)) {
            $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ? AND role = ? LIMIT 1");
            $stmt->bind_param('ss', $username, $roleName);
        } else {
            $error = "Invalid role selected.";
        }

        if ($stmt) {
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows == 1) {
                $user = $result->fetch_assoc();
                $storedPassword = (string) $user['password'];

                if (password_verify($password, $storedPassword) || hash_equals($storedPassword, $password)) {
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = ucfirst(strtolower($user['role']));
                    $_SESSION['user_id'] = $user['id'];

                    if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Teacher') {
                        header("Location: admin.php");
                    } elseif ($_SESSION['role'] === 'Student') {
                        header("Location: student.php");
                    } elseif ($_SESSION['role'] === 'Parent') {
                        header("Location: parent.php");
                    }
                    exit();
                } else {
                    $error = "Invalid password.";
                }
            } else {
                $error = "Invalid username or role.";
            }

            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Sign In | Ozone RMS</title>
    <link rel="stylesheet" href="styles.css" />
</head>
<body>
    <header class="site-header">
        <div class="container header-inner">
            <h1 class="logo">Record <span>Management System</span></h1>
            <nav class="main-nav">
                <a href="index.html">Home</a>
                <a href="About.html">About Us</a>
                <a href="Staff.html">Staff</a>
                <a href="contact.html">Contact Us</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <section id="auth-section">
            <div class="auth-box">
                <div class="info-card">
                    <form id="login-form" class="auth-form" action="login.php" method="POST">
                        <h2>Login to RMS</h2>
                        
                        <?php if (isset($error)): ?>
                            <div style="color: red; margin-bottom: 1rem; padding: 0.75rem; background: #ffe0e0; border-radius: 5px;">
                                <?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="input-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" placeholder="Enter username" required />
                        </div>
                        
                        <div class="input-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" placeholder="Enter password" required />
                        </div>
                        
                        <div class="input-group">
                            <label for="role">Select Role</label>
                            <select id="role" name="role" required>
                                <option value="" disabled selected>Select Role</option>
                                <option value="Admin">Admin</option>
                                <option value="Parent">Parent</option>
                                <option value="Student">Student</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn-primary">Login</button>
                        <p style="text-align: center; margin-top: 1rem;">
                            Get know your result!
                        </p>
                    </form>
                </div>
            </div>
        </section>
    </main>
</body>
</html>