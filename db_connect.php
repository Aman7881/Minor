<?php
// Database connection details
$host = 'localhost';        // Hostname (typically 'localhost' for XAMPP)
$username = 'root';         // Database username (default for XAMPP is 'root')
$password = '';             // Database password (default is empty in XAMPP)
$dbname = 'project_minor';  // Name of the database you created

// Create a new MySQLi connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: Set character set to UTF-8 for compatibility
$conn->set_charset("utf8");

// Now $conn can be used as the database connection in any file that includes 'db_connect.php'
?>
