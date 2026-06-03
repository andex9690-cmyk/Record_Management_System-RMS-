<?php
include 'db.php';

// 3. Process Form Data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Sanitize inputs to prevent basic injections
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $role = $conn->real_escape_string($_POST['role']);
    $password = $_POST['password']; // We will hash this next

    // Basic Validation: Check if role is valid
    $allowed_roles = ['Admin', 'Teacher', 'Parent', 'Student'];
    if (!in_array($role, $allowed_roles)) {
        die("Invalid role selected.");
    }

    // 4. Secure Password Hashing
    // password_hash uses the Bcrypt algorithm by default
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 5. Prepare SQL Statement
    // Using Prepared Statements to prevent SQL Injection
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);

    // 6. Execute and Feedback
    if ($stmt->execute()) {
        echo "<script>
                alert('Registration successful! Please login.');
                window.location.href = 'login.html'; // Redirect back to your login page
              </script>";
    } else {
        if ($conn->errno === 1062) { // Error code for Duplicate Entry
            echo "Error: Username or Email already exists.";
        } else {
            echo "Something went wrong. Please try again.";
        }
    }

    $stmt->close();
}

$conn->close();
?>