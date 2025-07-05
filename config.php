<?php
$host = "localhost";
$user = "root";
$pass = ""; // Leave blank for XAMPP
$db = "bincomtest";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
