<?php
$servername = "86.58.10.17";
$username = "projektSPV";
$password = "bankaZnamk123";
$database = "projektSPV";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
echo "Connected successfully";
?>