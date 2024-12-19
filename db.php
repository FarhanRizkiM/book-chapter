<?php
$host = 'localhost';
$db = 'book_chapter';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mengatur karakter encoding (opsional, tapi direkomendasikan)
$conn->set_charset("utf8");
