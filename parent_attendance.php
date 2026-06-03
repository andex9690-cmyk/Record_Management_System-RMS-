<?php
include 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Parent') {
    header("Location: login.html");
    exit();
}

$parent_name = $_SESSION['username'];
$user_id = $_SESSION['user_id'];

$child = null;
$childStmt = $conn->prepare("SELECT s.id, s.first_name, s.last_name, s.grade, s.attendance_rate, s.absences FROM parent_student_links psl JOIN users s_u ON s_u.id = psl.student_user_id JOIN students s ON s.user_id = s_u.id WHERE psl.parent_user_id = ? LIMIT 1");
$childStmt->bind_param('i', $user_id);
$childStmt->execute();
$childResult = $childStmt->get_result();
if ($childResult && $childResult->num_rows > 0) {
    $child = $childResult->fetch_assoc();
}
$childStmt->close();

$student_name = $child ? $child['first_name'] . ' ' . $child['last_name'] : 'No child linked';
$attendance_rate = $child['attendance_rate'] ?? 0;
$absences = $child['absences'] ?? 0;
$late_arrivals = 1;

$attendance_records = [];
if ($child) {
    $attendanceStmt = $conn->prepare("SELECT attendance_date, status, note FROM student_attendance_records WHERE student_id = ? ORDER BY attendance_date DESC LIMIT 5");
    $attendanceStmt->bind_param('i', $child['id']);
    $attendanceStmt->execute();
    $attendanceResult = $attendanceStmt->get_result();
    while ($row = $attendanceResult->fetch_assoc()) {
        $attendance_records[] = [
            'date' => date('M d, Y', strtotime($row['attendance_date'])),
            'status' => $row['status'],
            'note' => $row['note'],
        ];
    }
    $attendanceStmt->close();
}

$summary_items = [
    ['label' => 'Attendance Rate', 'value' => $attendance_rate . '%'],
    ['label' => 'Total Absences', 'value' => $absences],
    ['label' => 'Late Arrivals', 'value' => $late_arrivals],
];
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
                <a href="parent.php" class="menu-item"><span class="menu-icon">🏠</span> Family Overview</a>
                <a href="parent_academic.php" class="menu-item"><span class="menu-icon">📈</span> Academic Progress</a>
                <a href="parent_attendance.php" class="menu-item active"><span class="menu-icon">📅</span> Attendance</a>
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
                        <span class="student-id">Attendance Overview</span>
                    </div>
                    <div class="parent-avatar"><?php echo strtoupper(substr($student_name, 0, 1)); ?></div>
                </div>
            </header>

            <section class="dash-welcome">
                <h1>Attendance for <?php echo htmlspecialchars(explode(' ', $student_name)[0]); ?></h1>
                <p>Recent attendance records and current standing for the school term.</p>
            </section>

            <section class="metrics-grid">
                <?php foreach ($summary_items as $item): ?>
                    <div class="metric-card">
                        <div class="metric-header">
                            <span class="metric-title"><?php echo htmlspecialchars($item['label']); ?></span>
                            <span class="metric-badge secure">Current</span>
                        </div>
                        <div class="metric-value"><?php echo htmlspecialchars($item['value']); ?></div>
                        <div class="metric-footer">Updated from school records.</div>
                    </div>
                <?php endforeach; ?>
            </section>

            <section class="dash-middle-row">
                <div class="table-section-card results-card">
                    <div class="section-card-header">
                        <h3>📅 Attendance History</h3>
                        <span class="term-label">Last 5 school days</span>
                    </div>
                    <div class="table-responsive">
                        <table class="dash-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($attendance_records as $record): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($record['date']); ?></td>
                                        <td><?php echo htmlspecialchars($record['status']); ?></td>
                                        <td><?php echo htmlspecialchars($record['note']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="profile-summary-sidebar">
                    <div class="mini-profile-card">
                        <div class="card-accent-line"></div>
                        <h4>Attendance Notes</h4>
                        <div class="profile-info-item">
                            <span class="info-label">Current Status:</span>
                            <span class="info-value">Excellent attendance</span>
                        </div>
                        <div class="profile-info-item">
                            <span class="info-label">Recent concern:</span>
                            <span class="info-value">1 late arrival</span>
                        </div>
                        <div class="profile-info-item">
                            <span class="info-label">Recommendation:</span>
                            <span class="info-value">Maintain punctuality for next week</span>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
