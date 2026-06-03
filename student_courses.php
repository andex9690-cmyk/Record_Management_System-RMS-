<?php
include 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Student') {
    header("Location: login.html");
    exit();
}

$student_stmt = $conn->prepare("SELECT s.id, s.first_name, s.last_name, s.grade, s.stream FROM students s WHERE s.user_id = ? LIMIT 1");
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
$student_stream = isset($student['stream']) ? trim((string)$student['stream']) : '';
$stream_available = in_array($grade_level, ['11', '12'], true);

$courseColumns = [];
$columns_stmt = $conn->prepare("SHOW COLUMNS FROM student_courses");
if ($columns_stmt) {
    $columns_stmt->execute();
    $columns_result = $columns_stmt->get_result();
    while ($col = $columns_result->fetch_assoc()) {
        $courseColumns[] = $col['Field'];
    }
    $columns_stmt->close();
}

$has_course_grade = in_array('grade', $courseColumns, true);
$has_course_stream = in_array('stream', $courseColumns, true);
$selectFields = ['course_name', 'instructor', 'status'];
if ($has_course_grade) {
    $selectFields[] = 'grade';
}
if ($has_course_stream) {
    $selectFields[] = 'stream';
}
$fieldList = implode(', ', $selectFields);

$courses_stmt = $conn->prepare("SELECT $fieldList FROM student_courses WHERE student_id = ? ORDER BY id");
$courses_stmt->bind_param('i', $student['id']);
$courses_stmt->execute();
$courses_result = $courses_stmt->get_result();
$courses = [];
while ($row = $courses_result->fetch_assoc()) {
    if ($stream_available) {
        if ($has_course_stream && $student_stream !== '' && trim((string)$row['stream']) !== $student_stream) {
            continue;
        }
        if (!$has_course_stream && $has_course_grade && !in_array(trim((string)$row['grade']), ['11', '12'], true)) {
            continue;
        }
    }
    $courses[] = $row;
}
$courses_stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>My Courses | Ozone RMS</title>
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
                <a href="student_courses.php" class="menu-item active"><span class="menu-icon">📚</span> My Courses</a>
                <a href="student_records.php" class="menu-item"><span class="menu-icon">📄</span> My Records</a>
                <a href="student_grades.php" class="menu-item"><span class="menu-icon">📝</span> Levels</a>
                <a href="student_attendance.php" class="menu-item"><span class="menu-icon">📅</span> Attendance</a>
            </nav>
            <div class="sidebar-footer">
                <a href="logout.php" class="logout-btn"><span>🚪</span> Sign Out</a>
            </div>
        </aside>
        <main class="dash-main-content">
            <header class="dash-header">
                <h1>My Courses</h1>
            </header>
            <section class="courses-section">
                <h2>Active Courses</h2>
                <p>Showing current classes for <?php echo htmlspecialchars($student_name); ?>.</p>
                <?php if ($stream_available): ?>
                    <div class="table-section-card" style="margin-bottom:1rem;">
                        <div class="section-card-header"><h3>Grade 11 and 12 Stream Selection</h3></div>
                        <p>Choose your academic pathway for senior secondary studies.</p>
                        <div class="actions-button-stack" style="display:flex; flex-direction:column; gap:12px; margin-top:0.75rem; max-width:420px;">
                            <label for="stream_select" style="font-weight:600;">Stream</label>
                            <select id="stream_select" name="stream" class="input-field" style="padding:0.65rem; border-radius:8px; border:1px solid #cbd5e1;">
                                <option value="" disabled <?php echo $student_stream === '' ? 'selected' : ''; ?>>Select your stream</option>
                                <option value="Natural Science" <?php echo $student_stream === 'Natural Science' ? 'selected' : ''; ?>>🔬 Natural Science</option>
                                <option value="Social Science" <?php echo $student_stream === 'Social Science' ? 'selected' : ''; ?>>🌍 Social Science</option>
                            </select>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="table-section-card" style="margin-bottom:1rem; background:#f8fafc;">
                        <div class="section-card-header"><h3>Grade 9 and 10</h3></div>
                        <p>Grades 9 and 10 follow the general curriculum. Stream choices are not used at this level.</p>
                    </div>
                <?php endif; ?>
                <table class="dash-table">
                    <thead>
                        <tr>
                            <th>Course</th>
                            <th>Instructor</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($courses)): ?>
                            <?php foreach ($courses as $course): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($course['course_name']); ?></td>
                                <td><?php echo htmlspecialchars($course['instructor']); ?></td>
                                <td><?php echo htmlspecialchars($course['status']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3">No courses available for this student.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>
</html>
