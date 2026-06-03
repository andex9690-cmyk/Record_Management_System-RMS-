<?php
include 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Parent') {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$parent_name = $_SESSION['username'];

$child = null;
$childStmt = $conn->prepare("SELECT s_u.email, s.id, s.first_name, s.last_name, s.grade, s.gpa, s.attendance_rate, s.absences
FROM parent_student_links psl
JOIN users s_u ON s_u.id = psl.student_user_id
JOIN students s ON s.user_id = s_u.id
WHERE psl.parent_user_id = ?
LIMIT 1");
$childStmt->bind_param('i', $user_id);
$childStmt->execute();
$childResult = $childStmt->get_result();
if ($childResult && $childResult->num_rows > 0) {
    $child = $childResult->fetch_assoc();
}
$childStmt->close();

$nextEvent = null;
$eventStmt = $conn->prepare("SELECT e.event_date, e.title, e.category FROM calendar_events e JOIN students s ON s.id = e.student_id JOIN users u ON u.id = s.user_id JOIN parent_student_links psl ON psl.student_user_id = u.id WHERE psl.parent_user_id = ? ORDER BY e.event_date ASC LIMIT 1");
$eventStmt->bind_param('i', $user_id);
$eventStmt->execute();
$eventResult = $eventStmt->get_result();
if ($eventResult && $eventResult->num_rows > 0) {
    $nextEvent = $eventResult->fetch_assoc();
}
$eventStmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Parent Portal | Ozone RMS</title>
    <link rel="stylesheet" href="styles.css" />
</head>
<body class="dashboard-body">

    <div class="dashboard-container">
        <aside class="dash-sidebar">
            <div class="sidebar-brand">
                <h2>OZONE <span>RMS</span></h2>
            </div>
            <nav class="sidebar-menu">
                <a href="parent.php" class="menu-item active"><span class="menu-icon">🏠</span> Family Overview</a>
                    <a href="parent_academic.php" class="menu-item"><span class="menu-icon">📈</span> Academic Progress</a>
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
                        <span class="student-name"><?php echo htmlspecialchars($child ? $child['first_name'] . ' ' . $child['last_name'] : 'No child linked'); ?></span>
                        <span class="student-id">Student: <?php echo htmlspecialchars($child ? 'Grade ' . $child['grade'] : 'Not assigned'); ?></span>
                    </div>
                    <div class="parent-avatar"><?php echo strtoupper(substr($parent_name, 0, 1)); ?></div>
                </div>
            </header>

            <section class="dash-welcome">
                <h1>Welcome, <?php echo htmlspecialchars(ucfirst($parent_name)); ?></h1>
                <p>Monitor your child's real-time grades, attendance, and communicate with the school.</p>
            </section>

            <section class="metrics-grid">
                <div class="metric-card">
                    <div class="metric-header">
                        <span class="metric-title">Child's Term GPA</span>
                        <span class="metric-badge up">Honor Roll</span>
                    </div>
                    <div class="metric-value"><?php echo htmlspecialchars($child['gpa'] ?? '0.00'); ?></div>
                    <div class="metric-footer">Standing: Top 10% of Class</div>
                </div>

                <div class="metric-card">
                    <div class="metric-header">
                        <span class="metric-title">Attendance Index</span>
                        <span class="metric-badge secure">Excellent</span>
                    </div>
                    <div class="metric-value"><?php echo htmlspecialchars(($child['attendance_rate'] ?? 0) . '%'); ?></div>
                    <div class="metric-footer">Total days missed: <?php echo htmlspecialchars(($child['absences'] ?? 0) . ' day' . (($child['absences'] ?? 0) == 1 ? '' : 's')); ?></div>
                </div>

                <div class="metric-card">
                    <div class="metric-header">
                        <span class="metric-title">Outstanding Fees</span>
                        <span class="metric-badge stable">Paid</span>
                    </div>
                    <div class="metric-value">$0.00</div>
                    <div class="metric-footer">Spring statement cleared</div>
                </div>

                <div class="metric-card">
                    <div class="metric-header">
                        <span class="metric-title">Next School Event</span>
                        <span class="metric-badge warning">Calendar</span>
                    </div>
                    <div class="metric-value"><?php echo htmlspecialchars($nextEvent ? date('M d', strtotime($nextEvent['event_date'])) : 'No event'); ?></div>
                    <div class="metric-footer">Parent-Teacher Conferences</div>
                </div>
            </section>

            <section class="dash-middle-row">
                <div class="table-section-card results-card">
                    <div class="section-card-header">
                        <h3>📋 Student Grade Performance Report</h3>
                        <span class="term-label">Term 2 (2026)</span>
                    </div>
                    <div class="table-responsive">
                        <table class="dash-table">
                            <thead>
                                <tr>
                                    <th>Subject Course</th>
                                    <th>Teacher</th>
                                    <th>Grade Metric</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Web Development</strong></td>
                                    <td>Mr. Henok</td>
                                    <td><span class="grade-pill high">11</span></td>
                                    <td>Passed</td>
                                </tr>
                                <tr>
                                    <td><strong>Maths</strong></td>
                                    <td>Mr. Sisay</td>
                                    <td><span class="grade-pill mid">10</span></td>
                                    <td>Passed</td>
                                </tr>
                                <tr>
                                    <td><strong>Physics</strong></td>
                                    <td>Mr. Sofonyas</td>
                                    <td><span class="grade-pill high">12</span></td>
                                    <td>Passed</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="profile-summary-sidebar">
                    <div class="mini-profile-card">
                        <div class="card-accent-line"></div>
                        <h4>Family Profile Data</h4>
                        <div class="profile-info-item">
                            <span class="info-label">Parent/Guardian:</span>
                            <span class="info-value"><?php echo htmlspecialchars(ucfirst($parent_name)); ?></span>
                        </div>
                        <div class="profile-info-item">
                            <span class="info-label">Email Address:</span>
                            <span class="info-value"><?php echo htmlspecialchars($child['email'] ?? ''); ?></span>
                        </div>
                        <hr class="profile-divider" />
                        <div class="profile-info-item">
                            <span class="info-label">Linked Student:</span>
                            <span class="info-value"><?php echo htmlspecialchars($child ? $child['first_name'] . ' ' . $child['last_name'] : 'No child linked'); ?></span>
                        </div>
                        <div class="profile-info-item">
                            <span class="info-label">RMS:</span>
                            <span class="info-value"><?php echo htmlspecialchars($child ? 'Grade ' . $child['grade'] : 'Not assigned'); ?></span>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

</body>
    <script src="theme.js"></script>
</html>
