<?php
include 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Parent') {
    header("Location: login.html");
    exit();
}

$parent_name = $_SESSION['username'];
$user_id = $_SESSION['user_id'];

$child = null;
$childStmt = $conn->prepare("SELECT s.id, s.first_name, s.last_name, s.grade FROM parent_student_links psl JOIN users s_u ON s_u.id = psl.student_user_id JOIN students s ON s.user_id = s_u.id WHERE psl.parent_user_id = ? LIMIT 1");
$childStmt->bind_param('i', $user_id);
$childStmt->execute();
$childResult = $childStmt->get_result();
if ($childResult && $childResult->num_rows > 0) {
    $child = $childResult->fetch_assoc();
}
$childStmt->close();

$student_name = $child ? $child['first_name'] . ' ' . $child['last_name'] : 'No child linked';

$calendar_events = [];
if ($child) {
    $eventStmt = $conn->prepare("SELECT e.event_date, e.title, e.category, e.details FROM calendar_events e WHERE e.student_id = ? ORDER BY e.event_date ASC");
    $eventStmt->bind_param('i', $child['id']);
    $eventStmt->execute();
    $eventResult = $eventStmt->get_result();
    while ($row = $eventResult->fetch_assoc()) {
        $calendar_events[] = [
            'date' => date('M d, Y', strtotime($row['event_date'])),
            'title' => $row['title'],
            'type' => $row['category'],
            'details' => $row['details'],
        ];
    }
    $eventStmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Calendar | Ozone RMS</title>
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
                <a href="parent_attendance.php" class="menu-item"><span class="menu-icon">📅</span> Attendance</a>
                <a href="parent_calendar.php" class="menu-item active"><span class="menu-icon">📆</span> Calendar</a>
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
                        <span class="student-id">School Calendar</span>
                    </div>
                    <div class="parent-avatar"><?php echo strtoupper(substr($student_name, 0, 1)); ?></div>
                </div>
            </header>

            <section class="dash-welcome">
                <h1>School Calendar</h1>
                <p>Upcoming academic and school events for <?php echo htmlspecialchars(explode(' ', $student_name)[0]); ?>.</p>
            </section>

            <section class="dash-middle-row">
                <div class="table-section-card results-card">
                    <div class="section-card-header">
                        <h3>📆 Upcoming Events</h3>
                        <span class="term-label">Next 4 scheduled items</span>
                    </div>
                    <div class="table-responsive">
                        <table class="dash-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Event</th>
                                    <th>Category</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($calendar_events as $event): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($event['date']); ?></td>
                                        <td><strong><?php echo htmlspecialchars($event['title']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($event['type']); ?></td>
                                        <td><?php echo htmlspecialchars($event['details']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="profile-summary-sidebar">
                    <div class="mini-profile-card">
                        <div class="card-accent-line"></div>
                        <h4>Calendar Notes</h4>
                        <div class="profile-info-item">
                            <span class="info-label">Next Event:</span>
                            <span class="info-value">Parent-Teacher Conferences</span>
                        </div>
                        <div class="profile-info-item">
                            <span class="info-label">Status:</span>
                            <span class="info-value">Current term schedule is active</span>
                        </div>
                        <div class="profile-info-item">
                            <span class="info-label">Reminder:</span>
                            <span class="info-value">Check messages for attendance updates</span>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
