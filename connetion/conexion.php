<?php
$host = "bwdywwoziyiuddmklza4-mysql.services.clever-cloud.com";
$user = "ux4pbioyrnpczygi";
$password = "eHYuYj8tYxOLhmRPut5n";
$database = "bwdywwoziyiuddmklza4";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
