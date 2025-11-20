<?php
/*
 * Item 6: PHP Connection
 * This file connects to the MySQL database.
 */

// Database Configuration
define('DB_SERVER', 'localhost'); // Your MySQL server address
define('DB_USERNAME', 'root');      // Your MySQL username (default is often 'root')
define('DB_PASSWORD', '');    // Your MySQL password (default is often empty)
define('DB_NAME', 'review_hub_db'); // The database name we just created

// 1. Create a new MySQLi connection
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// 2. Check the connection
if ($conn->connect_error) {
    // Stop the script and show the error
    die("Connection failed: " . $conn->connect_error);
}

// Optional: Set the character set to utf8mb4 for full emoji/special character support
$conn->set_charset("utf8mb4");

?>