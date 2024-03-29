<?php
// Database configuration
$db_host = "sql11.freemysqlhosting.net"; // Server: Hostname of the database server
$db_username = "sql11695074"; // Username: Username for database access
$db_password = "8aKsudJmLp"; // Password: Password for database access
$db_name = "sql11695074"; // Name: Name of the database (without file extension)
$db_port = "3306"; // Port number: Port number for the database connection

// Attempt to establish a connection to the database
$connection = mysqli_connect($db_host, $db_username, $db_password, $db_name, $db_port);

// Check if the connection was successful
if (!$connection) {
    // If connection failed, display error message and terminate script
    die("Connection failed: " . mysqli_connect_error());
}
?>

