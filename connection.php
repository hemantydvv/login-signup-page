<?php
// Pull DB details from environment variables (set in Render)
$servername = $_ENV['DB_HOST'] ?? 'sql302.infinityfree.com';  // Fallback for local testing
$username = $_ENV['DB_USER'] ?? 'if0_40414466';
$password = $_ENV['DB_PASSWORD'] ?? 'pmlwbu2T6fK4G6H';
$dbname = $_ENV['DB_NAME'] ?? 'if0_40414466_login_db';

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to prevent encoding issues
$conn->set_charset("utf8mb4");
?>