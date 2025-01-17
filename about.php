<?php
session_start(); // Memulai session untuk mengecek status login
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Book Chapter | About</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://fonts.googleapis.com/css?family=Muli:300,400,700,900" rel="stylesheet">
  <link rel="stylesheet" href="fonts/icomoon/style.css">
  <link rel="shortcut icon" type="image/png" href="./src/assets/images/logos/logobuku.png" />
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/jquery-ui.css">
  <link rel="stylesheet" href="css/owl.carousel.min.css">
  <link rel="stylesheet" href="css/owl.theme.default.min.css">
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
            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
              <!-- Jika sudah login, tampilkan nama pengguna dan opsi logout -->
              <div class="dropdown">
                <a href="#" class="small btn btn-primary px-4 py-2 rounded-0 dropdown-toggle" id="accountDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <?php echo htmlspecialchars($_SESSION['username']); ?>
                </a>
                <div class="dropdown-menu" aria-labelledby="accountDropdown">
                  <a class="dropdown-item" href="logout.php">Logout</a>
                </div>
              </div>
            <?php else: ?>
              <!-- Jika belum login, tampilkan tombol login dan register -->
              <a href="login.php" class="small mr-3"><span class="icon-unlock-alt"></span> Log In</a>
              <a href="register.php" class="small btn btn-primary px-4 py-2 rounded-0"><span class="icon-users"></span> Register</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <header class="site-navbar py-4 js-sticky-header site-navbar-target" role="banner">
      <div class="container">
        <div class="d-flex align-items-center">
          <!-- Logo -->
          <div class="site-logo">
            <a href="index.php" class="text-nowrap logo-img">
              <img src="./src/assets/images/logos/logoadmin.svg" width="180" alt="" />
            </a>
          </div>

          <!-- Navigation (Desktop & Mobile) -->
          <div class="mr-auto">
            <!-- Navbar for Desktop -->
            <nav class="site-navigation position-relative text-right" role="navigation">
              <ul class="site-menu main-menu js-clone-nav mr-auto d-none d-lg-block">
                <li><a href="index.php" class="nav-link text-left">Beranda</a></li>
                <li class="active"><a href="about.php" class="nav-link text-left">Tentang</a></li>
                <li><a href="services.php" class="nav-link text-left">Layanan</a></li>
                <li><a href="contact.php" class="nav-link text-left">Hubungi Kami</a></li>
              </ul>
            </nav>

            <!-- Mobile Navbar Toggle Button -->
            <nav class="navbar navbar-expand-lg navbar-light d-lg-none">
              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="site-menu main-menu js-clone-nav mr-auto">
                  <li><a href="index.php" class="nav-link text-left">Beranda</a></li>
                  <li class="active"><a href="about.php" class="nav-link text-left">Tentang</a></li>
                  <li><a href="services.php" class="nav-link text-left">Layanan</a></li>
                  <li><a href="contact.php" class="nav-link text-left">Hubungi Kami</a></li>
                </ul>
              </div>
            </nav>
          </div>

          <!-- User Account Menu -->
        </div>
      </div>
    </header>


    <div class="intro-section small" style="background-image: url('images/bkgabout.jpg');">
      <div class="container">
        <div class="row align-items-center justify-content-center">
          <div class="col-md-7 mx-auto text-center" data-aos="fade-up">
            <div class="intro">
              <h1>Tentang Kami</h1>
              <p>Platform untuk akses mudah ke bab-bab buku berkualitas sesuai kebutuhan Anda.</p>
              <p><a href="login.php" class="btn btn-primary">Mulai Sekarang</a></p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="site-section">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 mb-4 mb-lg-0">
            <img src="images/tentangkami.jpg" alt="Image" class="img-fluid">
          </div>
          <div class="col-lg-5 ml-auto">
            <span class="caption">Tentang Kami</span>
            <h2 class="title-with-line">Akses Mudah ke Bab Buku yang Anda Butuhkan</h2>
            <p class="mb-4">BookChapter adalah platform digital yang menyediakan akses ke bab-bab pilihan dari berbagai buku berkualitas. Kami memahami bahwa setiap orang memiliki kebutuhan literasi yang berbeda. Dengan BookChapter, Anda dapat membeli bab spesifik dari buku tanpa harus membeli seluruh buku, sehingga Anda bisa fokus pada topik yang paling relevan dengan kebutuhan Anda.</p>
          </div>
        </div>
      </div>
    </div>

    <div class="site-section pb-0">
      <div class="container">
        <div class="row mb-5 justify-content-center text-center">
          <div class="col-lg-4 mb-5 text-center">
            <span class="caption">Tim Kami</span>
            <h2 class="title-with-line mb-2 text-center">Kepemimpinan Kami</h2>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-4 col-md-6 mb-5 mb-lg-5">
            <div class="feature-1 border person text-center">
              <img src="images/person_1.jpg" alt="Image" class="img-fluid">
              <div class="feature-1-content">
                <h2>Craig Daniel</h2>
                <span class="position mb-3 d-block">Co-Founder, CEO</span>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit morbi hendrerit elit</p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 mb-5 mb-lg-5">
            <div class="feature-1 border person text-center">
              <img src="images/person_2.jpg" alt="Image" class="img-fluid">
              <div class="feature-1-content">
                <h2>Taylor Simpson</h2>
                <span class="position mb-3 d-block">Co-Founder, CEO</span>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit morbi hendrerit elit</p>
              </div>
            </div>
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