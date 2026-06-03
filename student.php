<?php
include 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Student') {
    header("Location: login.html");
    exit();
}

$student_stmt = $conn->prepare("SELECT s.* FROM students s WHERE s.user_id = ? LIMIT 1");
$student_stmt->bind_param('i', $_SESSION['user_id']);
$student_stmt->execute();
$student_result = $student_stmt->get_result();
$student = $student_result && $student_result->num_rows > 0 ? $student_result->fetch_assoc() : null;
$student_stmt->close();

$student_username = $_SESSION['username'];
$student_name = trim((string)($student['first_name'] ?? '') . ' ' . (string)($student['last_name'] ?? ''));
if ($student_name === '') {
    $student_name = $student_username;
}

$term = $student['term'] ?? 'Current term';
$gpa = $student['gpa'] ?? 0.0;
$grade = $student['grade'] ?? '';
$stream = $student['stream'] ?? '';
$attendance = $student['attendance_rate'] ?? 0;
$absences = $student['absences'] ?? 0;
$courses_count = $student['active_courses'] ?? 0;

$courses_stmt = $conn->prepare("SELECT course_name, instructor, status FROM student_courses WHERE student_id = ? ORDER BY id");
$courses_stmt->bind_param('s', $student['id']);
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

$needs_grade_setup = ($grade === '');
$grade_choice = $grade !== '' ? (string)$grade : '';

$subject_map = [
    '9' => ['English', 'Mathematics', 'Science', 'Social Studies', 'ICT', 'Physical Education'],
    '10' => ['English', 'Mathematics', 'Biology', 'Geography', 'Computer Science', 'Physical Education'],
    '11' => [
        'Natural Science' => ['Physics', 'Chemistry', 'Biology', 'Mathematics', 'Computer Science', 'Environmental Science'],
        'Social Science' => ['History', 'Geography', 'Economics', 'Business Studies', 'Sociology', 'Psychology'],
    ],
    '12' => [
        'Natural Science' => ['Physics', 'Chemistry', 'Biology', 'Mathematics', 'Computer Science', 'Environmental Science'],
        'Social Science' => ['History', 'Geography', 'Economics', 'Business Studies', 'Sociology', 'Psychology'],
    ],
];

$selected_grade = $grade_choice;
$selected_stream = $stream;
$display_subjects = [];
if ($selected_grade !== '') {
    if (in_array($selected_grade, ['9','10'], true)) {
        $display_subjects = $subject_map[$selected_grade];
    } elseif (in_array($selected_grade, ['11','12'], true) && $selected_stream !== '' && isset($subject_map[$selected_grade][$selected_stream])) {
        $display_subjects = $subject_map[$selected_grade][$selected_stream];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_grade_profile'])) {
    $selectedGrade = trim($_POST['grade'] ?? '');
    $selectedStream = trim($_POST['stream'] ?? '');
    if ($selectedGrade === '') {
        $message = 'Please select a grade.';
    } else {
        if (!in_array($selectedGrade, ['9','10','11','12'], true)) {
            $message = 'Invalid grade selected.';
        } else {
            if (in_array($selectedGrade, ['11','12'], true) && $selectedStream === '') {
                $message = 'Please choose a stream for Grade 11 or 12.';
            } else {
                if (!in_array($selectedGrade, ['11','12'], true)) {
                    $selectedStream = '';
                }
                $update = $conn->prepare('UPDATE students SET grade = ?, stream = ? WHERE user_id = ?');
                $update->bind_param('ssi', $selectedGrade, $selectedStream, $_SESSION['user_id']);
                $update->execute();
                $update->close();
                $grade = $selectedGrade;
                $stream = $selectedStream;
                $grade_choice = $selectedGrade;
                $selected_grade = $selectedGrade;
                $selected_stream = $selectedStream;
                $needs_grade_setup = false;
                if (in_array($selectedGrade, ['9','10'], true)) {
                    $display_subjects = $subject_map[$selectedGrade];
                } else {
                    $display_subjects = $subject_map[$selectedGrade][$selectedStream] ?? [];
                }
                $message = 'Grade and stream saved successfully.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Student Portal | Ozone RMS</title>
    <link rel="stylesheet" href="styles.css" />
</head>
<body class="dashboard-body">

    <div class="dashboard-container">
        <aside class="dash-sidebar">
            <div class="sidebar-brand">
                <h2>OZONE <span>RMS</span></h2>
            </div>
            <nav class="sidebar-menu">
                <a href="student.php" class="menu-item active"><span class="menu-icon">🏠</span> Overview</a>
                <a href="student_courses.php" class="menu-item"><span class="menu-icon">📚</span> My Courses</a>
                <a href="student_records.php" class="menu-item"><span class="menu-icon">📄</span> My Records</a>
                <a href="student_grades.php" class="menu-item"><span class="menu-icon">📝</span> Grades</a>
                <a href="student_attendance.php" class="menu-item"><span class="menu-icon">📅</span> Attendance</a>
            </nav>
            <div class="sidebar-footer">
                <a href="logout.php" class="logout-btn"><span>🚪</span> Sign Out</a>
            </div>
        </aside>

        <main class="dash-main-content">
            <header class="dash-header">
                <div class="header-status-info">
                    <span class="term-pill"><?php echo htmlspecialchars($term); ?></span>
                </div>
                <div class="student-profile">
                    <div class="student-info">
                        <span class="student-name"><?php echo htmlspecialchars($student_name); ?></span>
                        <span class="student-id">Grade <?php echo htmlspecialchars($grade !== '' ? $grade : 'Not set'); ?><?php echo $stream !== '' ? ' · ' . htmlspecialchars($stream) : ''; ?></span>
                    </div>
                    <div class="student-avatar"><?php echo strtoupper(substr($student_name, 0, 1)); ?></div>
                </div>
            </header>

            <section class="dash-welcome">
                <h1>Welcome, <?php echo htmlspecialchars(explode(' ', $student_name)[0]); ?></h1>
                <p>Track your real-time academic standing, courses, and progress metrics below.</p>
            </section>

            <section class="table-section-card" style="margin-bottom:1.25rem;">
                <div class="section-card-header"><h3>Choose Your Grade & Stream</h3></div>
                <p style="margin-top:0.25rem;">Click a grade below. Grade 11 and 12 will reveal the stream options.</p>
                <?php if (!empty($message)) echo '<p style="color:#b91c1c; margin-bottom:0.75rem;">'.htmlspecialchars($message).'</p>'; ?>
                <form method="POST" action="student.php" class="input-group" style="display:grid; gap:12px; max-width:520px;">
                    <input type="hidden" id="selected_grade" name="grade" value="<?php echo htmlspecialchars($grade_choice); ?>">
                    <div style="display:grid; gap:10px;">
                        <button type="button" class="action-btn-secondary" style="text-align:left;" onclick="setGrade('9');">Grade 9</button>
                        <button type="button" class="action-btn-secondary" style="text-align:left;" onclick="setGrade('10');">Grade 10</button>
                        <button type="button" class="action-btn-secondary" style="text-align:left;" onclick="setGrade('11');">Grade 11 ▶</button>
                        <button type="button" class="action-btn-secondary" style="text-align:left;" onclick="setGrade('12');">Grade 12 ▶</button>
                    </div>
                    <div id="stream_group" style="display:<?php echo in_array($grade, ['11','12'], true) ? 'block' : 'none'; ?>;">
                        <label>Choose Stream
                            <select name="stream" id="selected_stream">
                                <option value="">Select stream</option>
                                <option value="Natural Science" <?php echo $stream === 'Natural Science' ? 'selected' : ''; ?>>🔬 Natural Science</option>
                                <option value="Social Science" <?php echo $stream === 'Social Science' ? 'selected' : ''; ?>>🌍 Social Science</option>
                            </select>
                        </label>
                    </div>
                    <button type="submit" name="save_grade_profile" class="action-btn-primary" style="width:auto;">Save Grade & Stream</button>
                </form>
                <div id="subject_preview" class="table-section-card" style="margin-top:1rem; display:<?php echo $selected_grade !== '' ? 'block' : 'none'; ?>;">
                    <div class="section-card-header"><h3>Your Subjects</h3></div>
                    <div id="subject_preview_body">
                        <?php if ($selected_grade === '' ): ?>
                            <p>Select a grade to preview your subjects.</p>
                        <?php elseif (in_array($selected_grade, ['11','12'], true) && $selected_stream === ''): ?>
                            <p>Please choose a stream to view the Grade <?php echo htmlspecialchars($selected_grade); ?> subjects.</p>
                        <?php elseif (!empty($display_subjects)): ?>
                            <ul id="subject_list" style="margin:0; padding-left:20px;">
                                <?php foreach ($display_subjects as $subject): ?>
                                    <li><?php echo htmlspecialchars($subject); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p>No subjects are available for the selected grade/stream.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

            <section class="metrics-grid">
                <div class="metric-card">
                    <div class="metric-header">
                        <span class="metric-title">Cumulative GPA</span>
                        <span class="metric-badge up">Current Term</span>
                    </div>
                    <div class="metric-value"><?php echo htmlspecialchars(number_format((float)$gpa, 2)); ?></div>
                    <div class="metric-footer">Current academic standing</div>
                </div>

                <div class="metric-card">
                    <div class="metric-header">
                        <span class="metric-title">Attendance</span>
                        <span class="metric-badge secure"><?php echo htmlspecialchars(number_format((float)$attendance, 1)); ?>%</span>
                    </div>
                    <div class="metric-value"><?php echo htmlspecialchars($absences); ?> <span class="metric-unit-text">Absences</span></div>
                    <div class="metric-footer">Excellent attendance status</div>
                </div>

                <div class="metric-card">
                    <div class="metric-header">
                        <span class="metric-title">Active Courses</span>
                        <span class="metric-badge stable">Enrolled</span>
                    </div>
                    <div class="metric-value"><?php echo htmlspecialchars(count($courses) > 0 ? count($courses) : (int)$courses_count); ?></div>
                    <div class="metric-footer">Current enrollment</div>
                </div>

                <div class="metric-card">
                    <div class="metric-header">
                        <span class="metric-title">Pending Tasks</span>
                        <span class="metric-badge warning">Due Soon</span>
                    </div>
                    <div class="metric-value">3</div>
                    <div class="metric-footer">Assignments due this week</div>
                </div>
            </section>

            <div class="dash-data-split">
                <section class="table-section-card">
                    <div class="section-card-header">
                        <h3>Current Term Grades</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="dash-table">
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th>Instructor</th>
                                    <th>Status</th>
                                    <th>Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($grades)): ?>
                                    <?php foreach ($grades as $grade_row): ?>
                                        <tr>
                                            <td><strong><?php echo htmlspecialchars($grade_row['course_name']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($grade_row['instructor']); ?></td>
                                            <td>
                                                <?php
                                                $status = 'Active';
                                                foreach ($courses as $course) {
                                                    if ($course['course_name'] === $grade_row['course_name']) {
                                                        $status = $course['status'];
                                                        break;
                                                    }
                                                }
                                                echo htmlspecialchars($status);
                                                ?>
                                            </td>
                                            <td><span class="grade-badge high"><?php echo htmlspecialchars($grade_row['grade']); ?></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4">No grades available for this term.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="actions-section-card">
                    <div class="section-card-header">
                        <h3>Upcoming Deadlines</h3>
                    </div>
                    <div class="deadline-stack">
                        <div class="deadline-item danger">
                            <div class="deadline-meta">
                                <span class="deadline-tag">Physics</span>
                                <span class="deadline-time">Tomorrow</span>
                            </div>
                            <h4>Lab Report Submission</h4>
                        </div>

                        <div class="deadline-item imminent">
                            <div class="deadline-meta">
                                <span class="deadline-tag">Maths</span>
                                <span class="deadline-time">May 26</span>
                            </div>
                            <h4>Problem Set 4</h4>
                        </div>

                        <div class="deadline-item standard">
                            <div class="deadline-meta">
                                <span class="deadline-tag">Web Development</span>
                                <span class="deadline-time">May 30</span>
                            </div>
                            <h4>Essay Draft Submission</h4>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <script>
        const subjectMap = <?php echo json_encode($subject_map, JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
        const gradeInput = document.getElementById('selected_grade');
        const streamSelect = document.getElementById('selected_stream');
        const previewCard = document.getElementById('subject_preview');
        const previewBody = document.getElementById('subject_preview_body');

        function setGrade(value) {
            gradeInput.value = value;
            const streamGroup = document.getElementById('stream_group');
            if (value === '11' || value === '12') {
                streamGroup.style.display = 'block';
            } else {
                streamGroup.style.display = 'none';
                if (streamSelect) {
                    streamSelect.value = '';
                }
            }
            updateSubjectPreview();
        }

        function updateSubjectPreview() {
            const selectedGrade = gradeInput.value;
            const selectedStream = streamSelect ? streamSelect.value : '';
            if (!previewCard || !previewBody) {
                return;
            }
            if (!selectedGrade) {
                previewCard.style.display = 'none';
                return;
            }
            previewCard.style.display = 'block';

            let subjects = [];
            if (['9', '10'].includes(selectedGrade)) {
                subjects = subjectMap[selectedGrade] || [];
            } else if (['11', '12'].includes(selectedGrade) && selectedStream) {
                subjects = subjectMap[selectedGrade] ? subjectMap[selectedGrade][selectedStream] || [] : [];
            }

            let html = '';
            if (subjects.length > 0) {
                html += '<ul style="margin:0; padding-left:20px;">';
                subjects.forEach(subject => {
                    html += '<li>' + subject + '</li>';
                });
                html += '</ul>';
            } else if (['11', '12'].includes(selectedGrade) && !selectedStream) {
                html = '<p>Please choose a stream to view the Grade ' + selectedGrade + ' subjects.</p>';
            } else {
                html = '<p>Select a grade to preview your subjects.</p>';
            }
            previewBody.innerHTML = html;
        }

        document.addEventListener('DOMContentLoaded', () => {
            if (streamSelect) {
                streamSelect.addEventListener('change', updateSubjectPreview);
            }
            updateSubjectPreview();
        });
    </script>
    <script src="theme.js"></script>
</body>
</html>
