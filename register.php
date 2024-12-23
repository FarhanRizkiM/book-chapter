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
                      window.location.href = 'login.php';
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
                      window.location.href = 'register.php';
                    });
                });
              </script>";
  }

  $stmt->close();
}

$conn->close();
ob_end_flush(); // Akhiri output buffering

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Book Chapter | Register</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://fonts.googleapis.com/css?family=Muli:300,400,700,900" rel="stylesheet">
  <link rel="shortcut icon" type="image/png" href="./src/assets/images/logos/logobuku.png" />
  <link rel="stylesheet" href="fonts/icomoon/style.css">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/jquery-ui.css">
  <link rel="stylesheet" href="css/owl.carousel.min.css">
  <link rel="stylesheet" href="css/owl.theme.default.min.css">
  <link rel="stylesheet" href="css/jquery.fancybox.min.css">
  <link rel="stylesheet" href="css/bootstrap-datepicker.css">
  <link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">
  <link rel="stylesheet" href="css/aos.css">
  <link href="css/jquery.mb.YTPlayer.min.css" media="all" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <style>
    .footer {
      background-color: #1f3c88;
      /* Warna latar belakang */
      padding: 30px 0;
      /* Menambah padding vertikal */
      color: #ffffff;
      /* Warna teks */
      text-align: center;
      font-size: 1rem;
      /* Ukuran teks lebih besar */
      line-height: 1.8;
      /* Jarak antar baris */
      margin-top: 20px;
      /* Jarak dari konten di atas */
      box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
      /* Tambahkan bayangan untuk efek */
    }

    .footer p {
      margin: 0;
      /* Hapus margin default */
      padding: 0;
    }

    .footer a {
      color: #ffffff;
      /* Warna teks tautan */
      text-decoration: none;
      font-weight: bold;
    }

    .footer a:hover {
      color: #ffc107;
      /* Warna hover untuk tautan */
      text-decoration: underline;
    }
  </style>
</head>

<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">

  <div class="site-wrap">
    <div class="site-mobile-menu site-navbar-target">
      <div class="site-mobile-menu-header">
        <div class="site-mobile-menu-close mt-3">
          <span class="icon-close2 js-menu-toggle"></span>
        </div>
      </div>
      <div class="site-mobile-menu-body"></div>
    </div>

    <div class="py-2 bg-light">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-9 d-none d-lg-block">
          </div>
          <div class="col-lg-3 text-right">
            <a href="login.php" class="small mr-3"><span class="icon-unlock-alt"></span> Log In</a>
            <a href="register.php" class="small btn btn-primary px-4 py-2 rounded-0"><span class="icon-users"></span> Register</a>
          </div>
        </div>
      </div>
    </div>

    <div class="site-section ftco-subscribe-1 site-blocks-cover pb-4" style="background-image: url('images/bg_1.jpg')">
      <div class="container">
        <div class="row align-items-end justify-content-center text-center">
          <div class="col-lg-7">
            <h2 class="mb-0">Silahkan Register untuk Masuk BookChapter.</h2><br><br>
          </div>
        </div>
      </div>
    </div>

    <div class="custom-breadcrumns border-bottom">
      <div class="container">
        <a href="index.php">Home</a>
        <span class="mx-3 icon-keyboard_arrow_right"></span>
        <span class="current">Register</span>
      </div>
    </div>

    <div class="site-section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-5">
            <form action="register.php" method="POST">
              <div class="row">
                <div class="col-md-12 form-group">
                  <label for="full_name">Nama Lengkap</label>
                  <input type="text" id="full_name" name="full_name" class="form-control form-control-lg" placeholder="Masukan Username Anda" required>
                </div>
                <div class="col-md-12 form-group">
                  <label for="username">Username</label>
                  <input type="text" id="username" name="username" class="form-control form-control-lg" placeholder="Masukan Username Anda" required>
                </div>
                <div class="col-md-12 form-group">
                  <label for="email">Email</label>
                  <input type="email" id="email" name="email" class="form-control form-control-lg" placeholder="Masukan Email Anda" required>
                </div>
                <div class="col-md-12 form-group">
                  <label for="pword">Kata Sandi</label>
                  <input type="password" id="pword" name="password" class="form-control form-control-lg" placeholder="Masukan Kata Sandi Anda" required>
                </div>
                <div class="col-md-12 form-group">
                  <label for="pword2">Ketik Ulang Kata Sandi</label>
                  <input type="password" id="pword2" name="password2" class="form-control form-control-lg" placeholder="Masukan Ketik Ulang Kata Sandi Anda" required>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <input type="submit" value="Register" class="btn btn-primary btn-lg px-5">
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>


    <!-- Footer -->
    <div class="footer">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <p>
              Copyright &copy;<script>
                document.write(new Date().getFullYear());
              </script>
              All rights reserved | <a href="#" style="color: #ffffff; text-decoration: none;">BookChapter</a>.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- .site-wrap -->

  <!-- loader -->
  <div id="loader" class="show fullscreen">
    <svg class="circular" width="48px" height="48px">
      <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee" />
      <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#51be78" />
    </svg>
  </div>

  <script>
    // Validasi sebelum submit form
    document.getElementById("registerForm").addEventListener("submit", function(event) {
      const password = document.getElementById("pword").value;
      const password2 = document.getElementById("pword2").value;

      if (password !== password2) {
        event.preventDefault(); // Menghentikan submit form jika password tidak cocok

        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Password dan Re-type Password tidak cocok!"
        });
      }
    });
  </script>
  <script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/jquery-migrate-3.0.1.min.js"></script>
  <script src="js/jquery-ui.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.stellar.min.js"></script>
  <script src="js/jquery.countdown.min.js"></script>
  <script src="js/bootstrap-datepicker.min.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/jquery.fancybox.min.js"></script>
  <script src="js/jquery.sticky.js"></script>
  <script src="js/jquery.mb.YTPlayer.min.js"></script>
  <script src="js/messageModal.js"></script>
  <!-- jQuery, Bootstrap, dan SweetAlert2 -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="js/main.js"></script>

</body>

</html>