<?php
include 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Student') {
    header("Location: login.html");
    exit();
}

$student_stmt = $conn->prepare("SELECT id, first_name, last_name, attendance_rate, absences FROM students WHERE user_id = ? LIMIT 1");
$student_stmt->bind_param('i', $_SESSION['user_id']);
$student_stmt->execute();
$student_result = $student_stmt->get_result();
$student = $student_result && $student_result->num_rows > 0 ? $student_result->fetch_assoc() : null;
$student_stmt->close();

$student_name = trim((string)($student['first_name'] ?? '') . ' ' . (string)($student['last_name'] ?? ''));
if ($student_name === '') {
    $student_name = $_SESSION['username'];
}

$attendance_rate = $student['attendance_rate'] ?? 0;
$absences = $student['absences'] ?? 0;

$attendance_stmt = $conn->prepare("SELECT attendance_date, status, note FROM student_attendance_records WHERE student_id = ? ORDER BY attendance_date DESC");
$attendance_stmt->bind_param('i', $student['id']);
$attendance_stmt->execute();
$attendance_result = $attendance_stmt->get_result();
$attendance_records = [];
while ($row = $attendance_result->fetch_assoc()) {
    $attendance_records[] = [
        'date' => date('M d, Y', strtotime($row['attendance_date'])),
        'status' => $row['status'],
        'note' => $row['note'],
    ];
}
$attendance_stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Attendance | Ozone RMS</title>
    <link rel="stylesheet" href="styles.css" />
</head>
<body class="dashboard-body">
    <div class="dashboard-container">
        <aside class="dash-sidebar">
            <div class="sidebar-brand">
                <h2>OZONE <span>RMS</span></h2>
            </div>
            <nav class="sidebar-menu">
                <a href="student.php" class="menu-item"><span class="menu-icon">🏠</span> Overview</a>
                <a href="student_courses.php" class="menu-item"><span class="menu-icon">📚</span> My Courses</a>
                <a href="student_records.php" class="menu-item"><span class="menu-icon">📄</span> My Records</a>
                <a href="student_grades.php" class="menu-item"><span class="menu-icon">📝</span> Grades</a>
                <a href="student_attendance.php" class="menu-item active"><span class="menu-icon">📅</span> Attendance</a>
            </nav>
            <div class="sidebar-footer">
                <a href="logout.php" class="logout-btn"><span>🚪</span> Sign Out</a>
            </div>
        </aside>
        <main class="dash-main-content">
            <header class="dash-header">
                <h1>Attendance</h1>
            </header>
            <section class="attendance-section">
                <h2>Attendance Records</h2>
                <p>Latest attendance data for <?php echo htmlspecialchars($student_name); ?>.</p>
                <div class="metrics-grid">
                    <div class="metric-card">
                        <div class="metric-header">
                            <span class="metric-title">Attendance Rate</span>
                            <span class="metric-badge secure">Current</span>
                        </div>
                        <div class="metric-value"><?php echo htmlspecialchars(number_format((float)$attendance_rate, 1)); ?>%</div>
                        <div class="metric-footer">Excellent attendance</div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-header">
                            <span class="metric-title">Absences</span>
                            <span class="metric-badge warning">This Term</span>
                        </div>
                        <div class="metric-value"><?php echo htmlspecialchars($absences); ?></div>
                        <div class="metric-footer">Keep up the good work!</div>
                    </div>
                </div>
                <table class="dash-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($attendance_records)): ?>
                            <?php foreach ($attendance_records as $record): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($record['date']); ?></td>
                                <td><?php echo htmlspecialchars($record['status']); ?></td>
                                <td><?php echo htmlspecialchars($record['note']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3">No attendance records available.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>
</html>
