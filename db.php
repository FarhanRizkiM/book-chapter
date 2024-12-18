<?php
// Cari file .env dari root project
$env_path = realpath(__DIR__ . '/.env'); // Cek file .env di root yang sama dengan db.php

if (!$env_path) {
    die(".env file not found!");
}

// Fungsi untuk membaca file .env
function load_env($path)
{
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue; // Abaikan komentar
        list($key, $value) = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}

load_env($env_path);

// Ambil koneksi dari variabel .env
$host = $_ENV['DB_HOST'];
$user = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];
$dbname = $_ENV['DB_NAME'];

// Koneksi Database
$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
