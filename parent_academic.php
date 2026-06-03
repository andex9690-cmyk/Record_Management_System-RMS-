<?php
include 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Parent') {
    header("Location: login.html");
    exit();
}

$parent_name = $_SESSION['username'];
$user_id = $_SESSION['user_id'];

$child = null;
$childStmt = $conn->prepare("SELECT s.id, s.first_name, s.last_name, s.grade, s.gpa, s.attendance_rate, s.absences FROM parent_student_links psl JOIN users s_u ON s_u.id = psl.student_user_id JOIN students s ON s.user_id = s_u.id WHERE psl.parent_user_id = ? LIMIT 1");
$childStmt->bind_param('i', $user_id);
$childStmt->execute();
$childResult = $childStmt->get_result();
if ($childResult && $childResult->num_rows > 0) {
    $child = $childResult->fetch_assoc();
}
$childStmt->close();

$student_name = $child ? $child['first_name'] . ' ' . $child['last_name'] : 'No child linked';
$grade = $child['grade'] ?? 'N/A';
$gpa = $child['gpa'] ?? 0;
$attendance = $child['attendance_rate'] ?? 0;
$absences = $child['absences'] ?? 0;
$term = 'Term 2 (2026)';

$nextEvent = null;
$eventStmt = $conn->prepare("SELECT e.event_date FROM calendar_events e JOIN students s ON s.id = e.student_id JOIN users u ON u.id = s.user_id JOIN parent_student_links psl ON psl.student_user_id = u.id WHERE psl.parent_user_id = ? ORDER BY e.event_date ASC LIMIT 1");
$eventStmt->bind_param('i', $user_id);
$eventStmt->execute();
$eventResult = $eventStmt->get_result();
if ($eventResult && $eventResult->num_rows > 0) {
    $nextEvent = $eventResult->fetch_assoc();
}
$eventStmt->close();
$next_event = $nextEvent ? date('M d', strtotime($nextEvent['event_date'])) : 'No event';

$subjects = [
    ['course' => 'AP Computer Science', 'teacher' => 'Mr. Davis', 'grade' => 'A (96%)', 'status' => 'Passed', 'trend' => 'Improving'],
    ['course' => 'Advanced Calculus', 'teacher' => 'Mrs. Harrison', 'grade' => 'B+ (88%)', 'status' => 'Passed', 'trend' => 'Stable'],
    ['course' => 'Physics Honors', 'teacher' => 'Dr. Sterling', 'grade' => 'A (94%)', 'status' => 'Passed', 'trend' => 'Strong'],
];

$highlights = [
    ['label' => 'GPA Trend', 'value' => '+0.14 since last term'],
    ['label' => 'Top Subject', 'value' => 'AP Computer Science'],
    ['label' => 'Teacher Feedback', 'value' => 'Consistently engaged and on track'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Academic Progress | Ozone RMS</title>
    <link rel="stylesheet" href="styles.css" />
</head>
<body class="dashboard-body">
    <div class="dashboard-container">
        <aside class="dash-sidebar">
            <div class="sidebar-brand">
                <h2>OZONE <span>RMS</span></h2>
            </div>
            <nav class="sidebar-menu">
                <a href="parent.php" class="menu-item"><span class="menu-icon">🏠</span> Family Overview</a>
                <a href="parent_academic.php" class="menu-item active"><span class="menu-icon">📈</span> Academic Progress</a>
                <a href="parent_attendance.php" class="menu-item"><span class="menu-icon">📅</span> Attendance</a>
                <a href="parent_calendar.php" class="menu-item"><span class="menu-icon">📆</span> Calendar</a>
                <a href="parent_messages.php" class="menu-item"><span class="menu-icon">💬</span> Messages</a>
            </nav>
            <div class="sidebar-footer">
                <a href="logout.php" class="logout-btn"><span>🚪</span> Sign Out</a>
            </div>
        </aside>
        <main class="dash-main-content">
            <header class="dash-header">
                <div class="header-status-info">
                    <span class="family-pill">Parent Account: <?php echo htmlspecialchars(ucfirst($parent_name)); ?></span>
                </div>
                <div class="student-profile">
                    <div class="student-info">
                        <span class="student-name"><?php echo htmlspecialchars($student_name); ?></span>
                        <span class="student-id">Grade <?php echo htmlspecialchars($grade); ?></span>
                    </div>
                    <div class="parent-avatar"><?php echo strtoupper(substr($student_name, 0, 1)); ?></div>
                </div>
            </header>

            <section class="dash-welcome">
                <h1>Academic Progress for <?php echo htmlspecialchars(explode(' ', $student_name)[0]); ?></h1>
                <p>Current performance snapshot for <?php echo htmlspecialchars($term); ?>.</p>
            </section>

            <section class="metrics-grid">
                <div class="metric-card">
                    <div class="metric-header">
                        <span class="metric-title">Cumulative GPA</span>
                        <span class="metric-badge up">Honor Roll</span>
                    </div>
                    <div class="metric-value"><?php echo htmlspecialchars($gpa); ?></div>
                    <div class="metric-footer">Academic standing is strong for the current term.</div>
                </div>

                <div class="metric-card">
                    <div class="metric-header">
                        <span class="metric-title">Attendance Index</span>
                        <span class="metric-badge secure"><?php echo htmlspecialchars($attendance); ?>%</span>
                    </div>
                    <div class="metric-value"><?php echo htmlspecialchars($absences); ?> <span class="metric-unit-text">Absences</span></div>
                    <div class="metric-footer">Attendance remains excellent this term.</div>
                </div>

                <div class="metric-card">
                    <div class="metric-header">
                        <span class="metric-title">Next School Event</span>
                        <span class="metric-badge warning">Calendar</span>
                    </div>
                    <div class="metric-value"><?php echo htmlspecialchars($next_event); ?></div>
                    <div class="metric-footer">Parent-Teacher Conferences are scheduled.</div>
                </div>
            </section>

            <section class="dash-middle-row">
                <div class="table-section-card results-card">
                    <div class="section-card-header">
                        <h3>📋 Current Term Grades</h3>
                        <span class="term-label"><?php echo htmlspecialchars($term); ?></span>
                    </div>
                    <div class="table-responsive">
                        <table class="dash-table">
                            <thead>
                                <tr>
                                    <th>Subject Course</th>
                                    <th>Teacher</th>
                                    <th>Grade Metric</th>
                                    <th>Status</th>
                                    <th>Trend</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($subjects as $subject): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($subject['course']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($subject['teacher']); ?></td>
                                        <td><span class="grade-pill high"><?php echo htmlspecialchars($subject['grade']); ?></span></td>
                                        <td><?php echo htmlspecialchars($subject['status']); ?></td>
                                        <td><?php echo htmlspecialchars($subject['trend']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="profile-summary-sidebar">
                    <div class="mini-profile-card">
                        <div class="card-accent-line"></div>
                        <h4>Academic Highlights</h4>
                        <?php foreach ($highlights as $highlight): ?>
                            <div class="profile-info-item">
                                <span class="info-label"><?php echo htmlspecialchars($highlight['label']); ?></span>
                                <span class="info-value"><?php echo htmlspecialchars($highlight['value']); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
