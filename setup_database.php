<?php
$host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'ozone_rms';

// Create connection without database first
$conn = new mysqli($host, $db_user, $db_password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS `$db_name`";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully or already exists.<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Select the database
$conn->select_db($db_name);

// Create users table
$sql = "CREATE TABLE IF NOT EXISTS `users` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `username` VARCHAR(50) UNIQUE NOT NULL,
    `email` VARCHAR(100) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('Admin', 'Teacher', 'Parent', 'Student') NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Users table created successfully.<br>";
} else {
    echo "Error creating users table: " . $conn->error . "<br>";
}

// Create students table
$sql = "CREATE TABLE IF NOT EXISTS `students` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `user_id` INT UNIQUE,
    `first_name` VARCHAR(50),
    `last_name` VARCHAR(50),
    `grade` VARCHAR(10),
    `stream` VARCHAR(50) DEFAULT NULL,
    `gpa` DECIMAL(3,2),
    `enrollment_date` DATE,
    `attendance_rate` DECIMAL(5,2),
    `absences` INT DEFAULT 0,
    `active_courses` INT DEFAULT 6,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Students table created successfully.<br>";
} else {
    echo "Error creating students table: " . $conn->error . "<br>";
}

// Create contact_messages table
$sql = "CREATE TABLE IF NOT EXISTS `contact_messages` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `fullname` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `subject` VARCHAR(200) NOT NULL,
    `message` TEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `status` ENUM('New', 'Read', 'Responded') DEFAULT 'New'
)";

if ($conn->query($sql) === TRUE) {
    echo "Contact messages table created successfully.<br>";
} else {
    echo "Error creating contact_messages table: " . $conn->error . "<br>";
}

// Create parent-to-student linkage and calendar tables
$conn->query("CREATE TABLE IF NOT EXISTS `parent_student_links` (
    `parent_user_id` INT NOT NULL,
    `student_user_id` INT NOT NULL,
    `relation` VARCHAR(50) DEFAULT 'Child',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`parent_user_id`, `student_user_id`),
    FOREIGN KEY (`parent_user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`student_user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$conn->query("CREATE TABLE IF NOT EXISTS `calendar_events` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `student_id` INT NOT NULL,
    `event_date` DATE NOT NULL,
    `title` VARCHAR(150) NOT NULL,
    `category` VARCHAR(50) NOT NULL DEFAULT 'School Event',
    `details` TEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$conn->query("CREATE TABLE IF NOT EXISTS `student_attendance_records` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `student_id` INT NOT NULL,
    `attendance_date` DATE NOT NULL,
    `status` VARCHAR(30) NOT NULL,
    `note` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Insert sample users
$admin_password = password_hash('admin123', PASSWORD_DEFAULT);
$student_password = password_hash('student123', PASSWORD_DEFAULT);
$parent_password = password_hash('parent123', PASSWORD_DEFAULT);

$sql = "INSERT IGNORE INTO users (username, email, password, role) VALUES 
        ('admin', 'admin@ozone.com', '$admin_password', 'Admin'),
        ('student1', 'student1@ozone.com', '$student_password', 'Student'),
        ('parent1', 'parent1@ozone.com', '$parent_password', 'Parent')";

if ($conn->query($sql) === TRUE) {
    echo "Sample users created successfully.<br>";
} else {
    echo "Error creating sample users: " . $conn->error . "<br>";
}

// Insert sample student data
$sql = "INSERT IGNORE INTO students (user_id, first_name, last_name, grade, gpa, enrollment_date, attendance_rate, absences, active_courses) VALUES 
        (2, 'Alex', 'Mercer', '11', 3.84, '2025-09-01', 98.2, 2, 6)";

if ($conn->query($sql) === TRUE) {
    echo "Sample student data created successfully.<br>";
} else {
    echo "Error creating student data: " . $conn->error . "<br>";
}

$sql = "INSERT IGNORE INTO parent_student_links (parent_user_id, student_user_id, relation)
        SELECT u.id, s.user_id, 'Child'
        FROM users u
        JOIN users s ON s.username = 'student1'
        WHERE u.username = 'parent1'";

if ($conn->query($sql) === TRUE) {
    echo "Parent-child link created successfully.<br>";
} else {
    echo "Error creating parent-child link: " . $conn->error . "<br>";
}

$sql = "INSERT IGNORE INTO calendar_events (student_id, event_date, title, category, details)
        SELECT s.id, '2026-06-03', 'Parent-Teacher Conferences', 'Important', 'Check-in session for student progress and goals.'
        FROM students s JOIN users u ON s.user_id = u.id WHERE u.username = 'student1'";
$sql .= "; INSERT IGNORE INTO calendar_events (student_id, event_date, title, category, details)
        SELECT s.id, '2026-06-10', 'Science Lab Showcase', 'School Event', 'Student presentations and project displays.'
        FROM students s JOIN users u ON s.user_id = u.id WHERE u.username = 'student1'";
$sql .= "; INSERT IGNORE INTO calendar_events (student_id, event_date, title, category, details)
        SELECT s.id, '2026-06-14', 'Final Exam Review Week', 'Academic', 'Review sessions and exam preparation.'
        FROM students s JOIN users u ON s.user_id = u.id WHERE u.username = 'student1'";

if ($conn->multi_query($sql) === TRUE) {
    do {
        if ($conn->errno) {
            echo "Error creating calendar events: " . $conn->error . "<br>";
            break;
        }
    } while ($conn->more_results() && $conn->next_result());
    echo "Sample calendar events created successfully.<br>";
} else {
    echo "Error creating calendar events: " . $conn->error . "<br>";
}
while ($conn->more_results()) { $conn->next_result(); }

$sql = "INSERT IGNORE INTO student_attendance_records (student_id, attendance_date, status, note)
        SELECT s.id, '2026-05-20', 'Present', 'On time'
        FROM students s JOIN users u ON s.user_id = u.id WHERE u.username = 'student1'";
$sql .= "; INSERT IGNORE INTO student_attendance_records (student_id, attendance_date, status, note)
        SELECT s.id, '2026-05-21', 'Present', 'On time'
        FROM students s JOIN users u ON s.user_id = u.id WHERE u.username = 'student1'";
$sql .= "; INSERT IGNORE INTO student_attendance_records (student_id, attendance_date, status, note)
        SELECT s.id, '2026-05-22', 'Present', 'Arrived 5 minutes late'
        FROM students s JOIN users u ON s.user_id = u.id WHERE u.username = 'student1'";
$sql .= "; INSERT IGNORE INTO student_attendance_records (student_id, attendance_date, status, note)
        SELECT s.id, '2026-05-23', 'Absent', 'Excused absence'
        FROM students s JOIN users u ON s.user_id = u.id WHERE u.username = 'student1'";

if ($conn->multi_query($sql) === TRUE) {
    do {
        if ($conn->errno) {
            echo "Error creating attendance records: " . $conn->error . "<br>";
            break;
        }
    } while ($conn->more_results() && $conn->next_result());
    echo "Sample attendance records created successfully.<br>";
} else {
    echo "Error creating attendance records: " . $conn->error . "<br>";
}
while ($conn->more_results()) { $conn->next_result(); }


echo "<br><h3>Database setup completed!</h3>";
echo "<p><strong>Sample Credentials:</strong></p>";
echo "<p>Admin: admin / admin123</p>";
echo "<p>Student: student1 / student123</p>";
echo "<p>Parent: parent1 / parent123</p>";
echo "<p><a href='index.html'>Back to Home</a> | <a href='login.html'>Go to Login</a></p>";

$conn->close();
?>
