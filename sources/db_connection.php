<?php
$servername = "mariadb.vamk.fi"; // Database server
$username = "e2301482"; // Database username
$password = "YhFdRbzjNj8"; // Database password
$dbname = "e2301482_embeddedLinux"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>