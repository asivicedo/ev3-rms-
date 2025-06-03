<?php
// -----------------------------------------------------------------
// config.php
// -----------------------------------------------------------------
// Central configuration: establishes a mysqli connection to
// the “db_legorms” database and starts the session.
// 
// Usage:
//   require 'config.php';
// -----------------------------------------------------------------

// MySQL connection settings
$host   = 'localhost';
$user   = 'root';       // Change if your MySQL username is different
$pass   = '';           // Change if your MySQL password is not empty
$dbname = 'db_legorms'; // Your database name

// Create a new mysqli connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start PHP session (needed for authentication, logs, etc.)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// (Optional) You can enable error reporting during development:
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
?>