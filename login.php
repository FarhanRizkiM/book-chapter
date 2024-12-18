<?php
session_start(); // Mulai sesi

require 'db.php';

// Proses login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Query untuk mendapatkan pengguna berdasarkan email
  $sql = "SELECT * FROM users WHERE email = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Verifikasi password
    if (password_verify($password, $user['password'])) {
      // Jika berhasil login, simpan informasi pengguna ke session
      $_SESSION['loggedin'] = true;
      $_SESSION['username'] = $user['username'];
      header("Location: dashboard.php");

      // Menampilkan pesan sukses dan redirect ke halaman utama
      echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
      echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                          icon: 'success',
                          title: 'Login Berhasil!',
                          text: 'Selamat datang, " . $user['username'] . "!'
                        }).then((result) => {
                          window.location.href = 'dashboard.php';
                        });
                    });
                  </script>";
    } else {
      // Password salah
      echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
      echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                          icon: 'error',
                          title: 'Oops...',
                          text: 'Password salah!'
                        }).then((result) => {
                          window.location.href = 'login.html';
                        });
                    });
                  </script>";
    }
  } else {
    // Email tidak ditemukan
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                      icon: 'error',
                      title: 'Oops...',
                      text: 'Email tidak ditemukan!'
                    }).then((result) => {
                      window.location.href = 'login.html';
                    });
                });
              </script>";
  }

  $stmt->close();
}

$conn->close();
