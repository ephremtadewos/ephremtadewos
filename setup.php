<?php
ob_start();
session_start();
ob_end_flush();

echo "<!DOCTYPE html>";
echo "<html><head><title>Setup - Portfolio</title>";
echo "<style>body{font-family:Arial;padding:40px;text-align:center;background:#f4f4f4;}";
echo ".success{color:green;padding:10px;background:#d4edda;border:1px solid #c3e6cb;margin:10px 0;}";
echo ".error{color:red;padding:10px;background:#f8d7da;border:1px solid #f5c6cb;margin:10px 0;}";
echo "a{display:inline-block;margin-top:20px;padding:10px 20px;background:#4f46e5;color:white;text-decoration:none;border-radius:5px;}</style>";
echo "</head><body>";
echo "<h1>Database Setup</h1>";

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "portfolio";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("<div class='error'>Connection failed: " . $conn->connect_error . "</div></body></html>");
}

$conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
$conn->select_db($dbname);

echo "<div class='success'>Database '$dbname' ready!</div>";

$sql1 = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql1)) {
    echo "<div class='success'>✓ Users table created</div>";
} else {
    echo "<div class='error'>✗ Users table: " . $conn->error . "</div>";
}

$sql2 = "CREATE TABLE IF NOT EXISTS contact_messages (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    subject VARCHAR(200),
    message TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    is_read TINYINT(1) DEFAULT 0
)";

if ($conn->query($sql2)) {
    echo "<div class='success'>✓ Contact messages table created</div>";
} else {
    echo "<div class='error'>✗ Contact messages: " . $conn->error . "</div>";
}

$conn->close();

echo "<h2>Setup Complete!</h2>";
echo "<p>Now you can:</p>";
echo "<a href='index.php'>Go to Login</a> | ";
echo "<a href='register.php'>Register</a>";
echo "</body></html>";
?>