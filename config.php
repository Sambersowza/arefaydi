<?php
$servername = "localhost";   // or 127.0.0.1
$username   = "root";        // default XAMPP user
$password   = "";            // default XAMPP has no password
$dbname     = "rfid_system"; // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
