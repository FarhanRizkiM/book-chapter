<?php
// Aktifkan error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db.php';

ob_start(); // Memulai output buffering

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $full_name = $_POST['full_name'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $password2 = $_POST['password2'];

  if ($password !== $password2) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                      icon: 'error',
                      title: 'Oops...',
                      text: 'Password dan Re-type Password tidak cocok!'
                    }).then((result) => {
                      window.location.href = 'register.html';
                    });
                });
              </script>";
    exit();
  }

  $hashed_password = password_hash($password, PASSWORD_DEFAULT);
  $sql = "INSERT INTO users (username, full_name, password, email) VALUES (?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ssss", $username, $full_name, $hashed_password, $email);

  if ($stmt->execute()) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                      icon: 'success',
                      title: 'Registrasi Berhasil!',
                      text: 'Anda akan diarahkan ke halaman login.'
                    }).then((result) => {
                      window.location.href = 'login.html';
                    });
                });
              </script>";
  } else {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                      icon: 'error',
                      title: 'Oops...',
                      text: 'Error: " . $stmt->error . "'
                    }).then((result) => {
                      window.location.href = 'register.html';
                    });
                });
              </script>";
  }

  $stmt->close();
}

$conn->close();
ob_end_flush(); // Akhiri output buffering
