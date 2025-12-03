<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "my_new_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Database холбогдсонгүй: " . $conn->connect_error);
}

// UTF-8 тохируулах
$conn->set_charset("utf8");
?>