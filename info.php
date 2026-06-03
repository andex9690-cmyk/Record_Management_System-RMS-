<?php
// Simple info page - shows system and database status
echo "<h1>Ozone RMS - System Information</h1>";
echo "<hr>";

// PHP Info
echo "<h2>PHP Configuration</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Server: " . $_SERVER['SERVER_SOFTWARE'] . "<br>";
echo "<hr>";

// Database Connection Test
echo "<h2>Database Connection Test</h2>";

$host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'ozone_rms';

$conn = new mysqli($host, $db_user, $db_password, $db_name);

if ($conn->connect_error) {
    echo "<span style='color: red;'>❌ Connection Failed: " . $conn->connect_error . "</span><br>";
    echo "Please run: <a href='setup_database.php'>setup_database.php</a>";
} else {
    echo "<span style='color: green;'>✓ Connected to database: $db_name</span><br>";
    
    // Check tables
    echo "<h3>Database Tables:</h3>";
    $result = $conn->query("SHOW TABLES");
    if ($result) {
        while ($row = $result->fetch_row()) {
            echo "✓ " . $row[0] . "<br>";
        }
    }
    
    // Check users
    echo "<h3>Users in System:</h3>";
    $result = $conn->query("SELECT username, role FROM users");
    if ($result) {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Username</th><th>Role</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . htmlspecialchars($row['username']) . "</td><td>" . htmlspecialchars($row['role']) . "</td></tr>";
        }
        echo "</table>";
    }
    
    $conn->close();
}

echo "<hr>";
echo "<h2>Quick Links</h2>";
echo "<a href='index.html'>Home</a> | ";
echo "<a href='login.php'>Login</a> | ";
echo "<a href='setup_database.php'>Initialize Database</a>";
?>
