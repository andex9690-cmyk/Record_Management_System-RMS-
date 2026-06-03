<?php
include 'db.php';

if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: login.html");
    exit();
}

$role = $_SESSION['role'];

if ($role === 'Student') {
    $student_stmt = $conn->prepare("SELECT id, first_name, last_name, grade, gpa, attendance_rate, absences, active_courses FROM students WHERE user_id = ? LIMIT 1");
    $student_stmt->bind_param('i', $_SESSION['user_id']);
    $student_stmt->execute();
    $student_result = $student_stmt->get_result();
    $student = $student_result && $student_result->num_rows > 0 ? $student_result->fetch_assoc() : null;
    $student_stmt->close();

    if (!$student) {
        echo "Student record not found.";
        exit();
    }

    $student_name = trim((string)($student['first_name'] ?? '') . ' ' . (string)($student['last_name'] ?? ''));
    if ($student_name === '') {
        $student_name = $_SESSION['username'];
    }

    $courses_stmt = $conn->prepare("SELECT course_name, instructor, status FROM student_courses WHERE student_id = ? ORDER BY id");
    $courses_stmt->bind_param('i', $student['id']);
    $courses_stmt->execute();
    $courses_result = $courses_stmt->get_result();
    $courses = [];
    while ($row = $courses_result->fetch_assoc()) {
        $courses[] = $row;
    }
    $courses_stmt->close();

    $grades_stmt = $conn->prepare("SELECT course_name, instructor, grade FROM student_grades WHERE student_id = ? ORDER BY id");
    $grades_stmt->bind_param('i', $student['id']);
    $grades_stmt->execute();
    $grades_result = $grades_stmt->get_result();
    $grades = [];
    while ($row = $grades_result->fetch_assoc()) {
        $grades[] = $row;
    }
    $grades_stmt->close();

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
}

$students = $conn->query("SELECT id, username, role FROM users WHERE role = 'Student'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo $role === 'Student' ? 'My Records' : 'Student Records'; ?> | Ozone RMS</title>
    <link rel="stylesheet" href="styles.css" />
</head>
<body class="dashboard-body">
    <div class="dashboard-container">
        <aside class="dash-sidebar">
            <div class="sidebar-brand">
                <h2>OZONE <span>RMS</span></h2>
            </div>
            <nav class="sidebar-menu">
                <?php if ($role === 'Student'): ?>
                    <a href="student.php" class="menu-item">🏠 Overview</a>
                    <a href="student_courses.php" class="menu-item">📚 My Courses</a>
                    <a href="student_records.php" class="menu-item active">📄 My Records</a>
                    <a href="student_grades.php" class="menu-item">📝 Grades</a>
                    <a href="student_attendance.php" class="menu-item">📅 Attendance</a>
                <?php else: ?>
                    <a href="admin.php" class="menu-item">Dashboard</a>
                    <a href="student_records.php" class="menu-item active">🎓 Student Records</a>
                    <a href="admin_messages.php" class="menu-item">💬 Reply Messages</a>
                    <a href="users_management.php" class="menu-item">👥 Users Management</a>
                    <a href="reports.php" class="menu-item">📋 Reports</a>
                    <a href="admin_settings.php" class="menu-item">⚙️ System Settings</a>
                <?php endif; ?>
            </nav>
            <div class="sidebar-footer">
                <a href="logout.php" class="logout-btn"><span>🚪</span> Logout</a>
            </div>
        </aside>
        <main class="dash-main-content">
            <header class="dash-header">
                <h1><?php echo $role === 'Student' ? 'My Records' : 'Student Records'; ?></h1>
            </header>

            <?php if ($role === 'Student'): ?>
                <section class="records-section">
                    <h2>Welcome, <?php echo htmlspecialchars($student_name); ?></h2>
                    <p>Here are your personal academic records for this term.</p>

                    <div class="metrics-grid">
                        <div class="metric-card">
                            <div class="metric-header"><span class="metric-title">Current GPA</span><span class="metric-badge up">Term</span></div>
                            <div class="metric-value"><?php echo htmlspecialchars(number_format((float)($student['gpa'] ?? 0), 2)); ?></div>
                            <div class="metric-footer">Academic standing</div>
                        </div>
                        <div class="metric-card">
                            <div class="metric-header"><span class="metric-title">Attendance</span><span class="metric-badge secure">Current</span></div>
                            <div class="metric-value"><?php echo htmlspecialchars(number_format((float)($student['attendance_rate'] ?? 0), 1)); ?>%</div>
                            <div class="metric-footer">Attendance rate</div>
                        </div>
                        <div class="metric-card">
                            <div class="metric-header"><span class="metric-title">Active Courses</span><span class="metric-badge stable">Enrolled</span></div>
                            <div class="metric-value"><?php echo htmlspecialchars(count($courses) > 0 ? count($courses) : (int)($student['active_courses'] ?? 0)); ?></div>
                            <div class="metric-footer">Current enrollment</div>
                        </div>
                    </div>

                    <div class="dash-data-split">
                        <section class="table-section-card">
                            <div class="section-card-header"><h3>My Courses</h3></div>
                            <table class="dash-table">
                                <thead><tr><th>Course</th><th>Instructor</th><th>Status</th></tr></thead>
                                <tbody>
                                    <?php if (!empty($courses)): foreach ($courses as $course): ?>
                                        <tr><td><?php echo htmlspecialchars($course['course_name']); ?></td><td><?php echo htmlspecialchars($course['instructor']); ?></td><td><?php echo htmlspecialchars($course['status']); ?></td></tr>
                                    <?php endforeach; else: ?><tr><td colspan="3">No courses available for this student.</td></tr><?php endif; ?>
                                </tbody>
                            </table>
                        </section>

                        <section class="table-section-card">
                            <div class="section-card-header"><h3>My Grades</h3></div>
                            <table class="dash-table">
                                <thead><tr><th>Course</th><th>Instructor</th><th>Grade</th></tr></thead>
                                <tbody>
                                    <?php if (!empty($grades)): foreach ($grades as $grade): ?>
                                        <tr><td><?php echo htmlspecialchars($grade['course_name']); ?></td><td><?php echo htmlspecialchars($grade['instructor']); ?></td><td><?php echo htmlspecialchars($grade['grade']); ?></td></tr>
                                    <?php endforeach; else: ?><tr><td colspan="3">No grades available for this student.</td></tr><?php endif; ?>
                                </tbody>
                            </table>
                        </section>
                    </div>

                    <section class="table-section-card">
                        <div class="section-card-header"><h3>My Attendance</h3></div>
                        <table class="dash-table">
                            <thead><tr><th>Date</th><th>Status</th><th>Note</th></tr></thead>
                            <tbody>
                                <?php if (!empty($attendance_records)): foreach ($attendance_records as $record): ?>
                                    <tr><td><?php echo htmlspecialchars($record['date']); ?></td><td><?php echo htmlspecialchars($record['status']); ?></td><td><?php echo htmlspecialchars($record['note']); ?></td></tr>
                                <?php endforeach; else: ?><tr><td colspan="3">No attendance records available.</td></tr><?php endif; ?>
                            </tbody>
                        </table>
                    </section>
                </section>
            <?php else: ?>
                <section class="records-section">
                    <h2>All Students</h2>
                    <table class="dash-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($students && $students->num_rows > 0): ?>
                                <?php while ($row = $students->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                                        <td><?php echo htmlspecialchars($row['role']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="3">No student records found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </section>
            <?php endif; ?>
        </main>
    </div>
    <script src="theme.js"></script>
</body>
</html>