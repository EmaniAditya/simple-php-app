<?php
// Database connection parameters
$host = 'localhost'; // Hostname of the database server
$dbname = 'loginDB'; // Name of the database to connect to
$username = 'root';   // Username for the database connection
$password = '';       // Password for the database connection

// Establishing a connection to the MySQL database
$mysqli = mysqli_connect($host, $username, $password, $dbname);

// Checking for connection errors
if (mysqli_connect_error()) {
    // If there is a connection error, terminate the script and display the error
    die("Connection failed: " . mysqli_connect_error());
}
