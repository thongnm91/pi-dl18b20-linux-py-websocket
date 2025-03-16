<?php
$servername = "mariadb.vamk.fi"; // Database server
$username = ""; // Database username
$password = ""; // Database password
$dbname = ""; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>