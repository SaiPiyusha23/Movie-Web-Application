<?php
$servername = "localhost";
$username = "root";
$password = "";
$db_name = "movie_db"; // Updated database name
$port = 3310; // Updated port number

$conn = new mysqli($servername, $username, $password, $db_name, $port); // Added port parameter

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
