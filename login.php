<?php
// loginuser.php
session_start();
include 'db.php';

// Proses login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Query untuk mendapatkan pengguna berdasarkan email
  $sql = "SELECT * FROM users WHERE email = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Verifikasi password
    if (password_verify($password, $row['password'])) {
      // Jika login berhasil, simpan informasi ke sesi
      $_SESSION['user_id'] = $row['user_id'];
      $_SESSION['username'] = $row['username'];
      $_SESSION['role'] = 'user';
      header("Location: dashboard.php");
      exit();
    } else {
      $error_message = "Password salah.";
    }
  } else {
    $error_message = "Email tidak ditemukan.";
  }

  $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Book Chapter | Login</title>
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
    <div class="py-2 bg-light">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-9 d-none d-lg-block"></div>
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
            <h2 class="mb-0">Silahkan Masuk untuk Akses BookChapter.</h2><br><br>
          </div>
        </div>
      </div>
    </div>

    <div class="custom-breadcrumns border-bottom">
      <div class="container">
        <a href="index.php">Home</a>
        <span class="mx-3 icon-keyboard_arrow_right"></span>
        <span class="current">Login</span>
      </div>
    </div>

    <div class="site-section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-5">
            <form method="POST">
              <?php if (!empty($error_message)) : ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
              <?php endif; ?>
              <div class="row">
                <div class="col-md-12 form-group">
                  <label for="email">Email</label>
                  <input type="email" id="email" name="email" class="form-control form-control-lg" placeholder="Masukan Email Anda" required>
                </div>
                <div class="col-md-12 form-group">
                  <label for="password">Kata Sandi</label>
                  <input type="password" id="password" name="password" class="form-control form-control-lg" placeholder="Masukan Kata Sandi Anda" required>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <input type="submit" value="Masuk" class="btn btn-primary btn-lg px-5">
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
  <script src="js/main.js"></script>
</body>

</html>