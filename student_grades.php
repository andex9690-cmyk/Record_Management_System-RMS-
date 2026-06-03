<?php
include 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Student') {
    header("Location: login.html");
    exit();
}

$student_stmt = $conn->prepare("SELECT s.id, s.first_name, s.last_name, s.grade FROM students s WHERE s.user_id = ? LIMIT 1");
$student_stmt->bind_param('i', $_SESSION['user_id']);
$student_stmt->execute();
$student_result = $student_stmt->get_result();
$student = $student_result && $student_result->num_rows > 0 ? $student_result->fetch_assoc() : null;
$student_stmt->close();

$student_name = trim((string)($student['first_name'] ?? '') . ' ' . (string)($student['last_name'] ?? ''));
if ($student_name === '') {
    $student_name = $_SESSION['username'];
}

$grade_level = isset($student['grade']) ? trim((string)$student['grade']) : '';
$stream_available = in_array($grade_level, ['11', '12'], true);

$grades_stmt = $conn->prepare("SELECT course_name, instructor, grade FROM student_grades WHERE student_id = ? ORDER BY id");
$grades_stmt->bind_param('i', $student['id']);
$grades_stmt->execute();
$grades_result = $grades_stmt->get_result();
$grades = [];
while ($row = $grades_result->fetch_assoc()) {
    $grades[] = $row;
}
$grades_stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Grades | Ozone RMS</title>
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
                <a href="student_grades.php" class="menu-item active"><span class="menu-icon">📝</span> Grades</a>
                <a href="student_attendance.php" class="menu-item"><span class="menu-icon">📅</span> Attendance</a>
            </nav>
            <div class="sidebar-footer">
                <a href="logout.php" class="logout-btn"><span>🚪</span> Sign Out</a>
            </div>
        </aside>
        <main class="dash-main-content">
            <header class="dash-header">
                <h1>Grades</h1>
            </header>
            <section class="grades-section">
                <h2>Current Term Grades</h2>
                <p>Grades for <?php echo htmlspecialchars($student_name); ?>.</p>
                <?php if ($stream_available): ?>
                    <div class="table-section-card" style="margin-bottom:1rem;">
                        <div class="section-card-header"><h3>Grade 11 and 12 Stream Path</h3></div>
                        <p>Choose between Natural Science and Social Science streams for your senior-secondary studies.</p>
                    </div>
                <?php else: ?>
                    <div class="table-section-card" style="margin-bottom:1rem; background:#f8fafc;">
                        <div class="section-card-header"><h3>Grade 9 and 10</h3></div>
                        <p>Grades 9 and 10 follow the general curriculum, so stream choices are not shown at this level.</p>
                    </div>
                <?php endif; ?>
                <table class="dash-table">
                    <thead>
                        <tr>
                            <th>Course</th>
                            <th>Instructor</th>
                            <th>Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($grades)): ?>
                            <?php foreach ($grades as $grade): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($grade['course_name']); ?></td>
                                <td><?php echo htmlspecialchars($grade['instructor']); ?></td>
                                <td><?php echo htmlspecialchars($grade['grade']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3">No grades available for this student.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>
</html>
