<?php
$servername = "localhost";
$username   = "root";        // or your DB username
$password   = "Ansari_221";            // or your DB password
$dbname     = "food_order";  // your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
