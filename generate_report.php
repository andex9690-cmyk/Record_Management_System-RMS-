<?php
include 'db.php';

if (!isset($_SESSION['username']) || ($_SESSION['role'] != 'Admin' && $_SESSION['role'] != 'Teacher')) {
    header("Location: login.html");
    exit();
}

$students = $conn->query("SELECT username FROM student");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Generate Report | Ozone RMS</title>
    <link rel="stylesheet" href="styles.css" />
</head>
<body class="dashboard-body">
    <div class="dashboard-container">
        <main class="dash-main-content">
            <header class="dash-header">
                <h1>Student Report</h1>
            </header>
            <section class="report-section">
                <h2>Student List</h2>
                <table class="dash-table">
                    <thead>
                        <tr><th>Username</th></tr>
                    </thead>
                    <tbody>
                        <?php if ($students && $students->num_rows > 0): ?>
                            <?php while ($row = $students->fetch_assoc()): ?>
                                <tr><td><?php echo htmlspecialchars($row['username']); ?></td></tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td>No students found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>
</html>