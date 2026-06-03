<?php
include 'db.php';

// Destroy session
session_destroy();

// Redirect to login page
header("Location: login.html");
exit();
?>
