<?php
session_start(); // Memulai session untuk mengecek status login
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Book Chapter</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


  <link href="https://fonts.googleapis.com/css?family=Muli:300,400,700,900" rel="stylesheet">
  <link rel="stylesheet" href="fonts/icomoon/style.css">
  <link rel="shortcut icon" type="image/png" href="./src/assets/images/logos/logo_bc.png" />
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
          <div class="col-lg-9 d-none d-lg-block"></div>
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
              <a href="login.html" class="small mr-3"><span class="icon-unlock-alt"></span> Log In</a>
              <a href="register.html" class="small btn btn-primary px-4 py-2 rounded-0"><span class="icon-users"></span> Register</a>
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
                        <a href="index.php" class="d-block">BookChapter.</a>
                    </div>

                    <!-- Navigation (Desktop & Mobile) -->
                    <div class="mr-auto">
                        <!-- Navbar for Desktop -->
                        <nav class="site-navigation position-relative text-right" role="navigation">
                            <ul class="site-menu main-menu js-clone-nav mr-auto d-none d-lg-block">
                                <li class="active"><a href="index.php" class="nav-link text-left">Beranda</a></li>
                                <li><a href="about.php" class="nav-link text-left">Tentang</a></li>
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
                                    <li class="active"><a href="index.php" class="nav-link text-left">Beranda</a></li>
                                    <li><a href="about.php" class="nav-link text-left">Tentang</a></li>
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


    <!-- Bagian konten utama -->
    <div class="hero-slide owl-carousel site-blocks-cover">
      <div class="intro-section" style="background-image: url('images/hero_1.jpg');">
        <div class="container">
          <div class="row align-items-center justify-content-center">
            <div class="col-md-7 mx-auto text-center" data-aos="fade-up">
              <h1>Jelajahi Bab Buku Pilihan</h1>
              <p>Temukan bab buku berkualitas tanpa harus membeli keseluruhan buku. Akses pengetahuan dengan mudah dan praktis.</p>
              <p><a href="login.html" class="btn btn-primary">Mulai Sekarang.</a></p>
            </div>
          </div>
        </div>
      </div>
      <div class="intro-section" style="background-image: url('images/hero_2.jpg');">
        <div class="container">
          <div class="row align-items-center justify-content-center">
            <div class="col-md-7 mx-auto text-center" data-aos="fade-up">
              <div class="intro">
                <h1>Beli Hanya yang Anda Butuhkan</h1>
                <p>Hemat waktu dan biaya dengan membeli bab-bab tertentu dari berbagai judul buku populer dan edukatif.</p>
                <p><a href="login.html" class="btn btn-primary">Mulai Sekarang</a></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="site-section">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 mb-4 mb-lg-0">
            <img src="images/hero_1.jpg" alt="Image" class="img-fluid">
          </div>
          <div class="col-lg-5 ml-auto">
            <span class="caption">Tentang Kami</span>
            <h2 class="title-with-line">Akses Mudah ke Bab Buku yang Anda Butuhkan</h2>
            <p class="mb-4">BookChapter adalah platform digital yang menyediakan akses ke bab-bab pilihan dari berbagai buku berkualitas. Kami memahami bahwa setiap orang memiliki kebutuhan literasi yang berbeda. Dengan BookChapter, Anda dapat membeli bab spesifik dari buku tanpa harus membeli seluruh buku, sehingga Anda bisa fokus pada topik yang paling relevan dengan kebutuhan Anda.</p>
            <p><a href="#" class="btn btn-primary px-4 ">Selengkapnya</a></p>
          </div>
        </div>
      </div>
    </div>

    <!-- Layanan -->
    <div class="site-section bg-light pb-0">
      <div class="container">
        <div class="row mb-5 justify-content-center text-center">
          <div class="col-lg-5">
            <span class="caption">Layanan Kami</span>
            <h2 class="title-with-line text-center mb-5">Apa yang Kami Tawarkan.</h2>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-4 col-md-6">

            <div class="feature-1">
              <div class="icon-wrapper bg-primary">

                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-bar-chart-line" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" d="M4 11H2v3h2v-3zm5-4H7v7h2V7zm5-5h-2v12h2V2zm-2-1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1h-2zM6 7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7zm-5 4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-3z" />
                  <path fill-rule="evenodd" d="M0 14.5a.5.5 0 0 1 .5-.5h15a.5.5 0 0 1 0 1H.5a.5.5 0 0 1-.5-.5z" />
                </svg>
              </div>
              <div class="feature-1-content">
                <h2>Pembelian bab Buku</h2>
                <p>Pilih dan beli bab spesifik yang Anda butuhkan tanpa harus membeli keseluruhan buku.</p>
                <p><a href="#" class="btn btn-primary px-4 ">Selengkapnya</a></p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="feature-1">
              <div class="icon-wrapper bg-primary">
                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-life-preserver" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                  <path fill-rule="evenodd" d="M8 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 1a4 4 0 1 0 0-8 4 4 0 0 0 0 8z" />
                  <path d="M11.642 6.343L15 5v6l-3.358-1.343A3.99 3.99 0 0 0 12 8a3.99 3.99 0 0 0-.358-1.657zM9.657 4.358L11 1H5l1.343 3.358A3.985 3.985 0 0 1 8 4c.59 0 1.152.128 1.657.358zM4.358 6.343L1 5v6l3.358-1.343A3.985 3.985 0 0 1 4 8c0-.59.128-1.152.358-1.657zm1.985 5.299L5 15h6l-1.343-3.358A3.984 3.984 0 0 1 8 12a3.99 3.99 0 0 1-1.657-.358z" />
                </svg>
              </div>
              <div class="feature-1-content">
                <h2>Unduh Bab Buku</h2>
                <p>Akses dan unduh bab yang telah Anda beli dengan mudah untuk dibaca kapan saja.</p>
                <p><a href="#" class="btn btn-primary px-4 ">Selengkapnya</a></p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="feature-1">
              <div class="icon-wrapper bg-primary">
                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-circle-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                  <path d="M0 6a6 6 0 1 1 12 0A6 6 0 0 1 0 6z" />
                  <path d="M12.93 5h1.57a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5v-1.57a6.953 6.953 0 0 1-1-.22v1.79A1.5 1.5 0 0 0 5.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 4h-1.79c.097.324.17.658.22 1z" />
                </svg>
              </div>
              <div class="feature-1-content">
                <h2>Akses Bab yang Dibeli</h2>
                <p>Simpan dan kelola bab yang sudah dibeli untuk akses mudah kapan pun diperlukan.</p>
                <p><a href="#" class="btn btn-primary px-4 ">Selengkapnya</a></p>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6">

            <div class="feature-1">
              <div class="icon-wrapper bg-primary">

                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-wallet2" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                  <path d="M2.5 4l10-3A1.5 1.5 0 0 1 14 2.5v2h-1v-2a.5.5 0 0 0-.5-.5L5.833 4H2.5z" />
                  <path fill-rule="evenodd" d="M1 5.5A1.5 1.5 0 0 1 2.5 4h11A1.5 1.5 0 0 1 15 5.5v8a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 1 13.5v-8zM2.5 5a.5.5 0 0 0-.5.5v8a.5.5 0 0 0 .5.5h11a.5.5 0 0 0 .5-.5v-8a.5.5 0 0 0-.5-.5h-11z" />
                </svg>
              </div>
              <div class="feature-1-content">
                <h2>Layanan Pelanggan</h2>
                <p>Tim dukungan siap membantu Anda dengan segala pertanyaan atau kendala dalam menggunakan platform.</p>
                <p><a href="#" class="btn btn-primary px-4 ">Selengkapnya</a></p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="feature-1">
              <div class="icon-wrapper bg-primary">
                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-briefcase" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" d="M0 12.5A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-6h-1v6a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-6H0v6z" />
                  <path fill-rule="evenodd" d="M0 4.5A1.5 1.5 0 0 1 1.5 3h13A1.5 1.5 0 0 1 16 4.5v2.384l-7.614 2.03a1.5 1.5 0 0 1-.772 0L0 6.884V4.5zM1.5 4a.5.5 0 0 0-.5.5v1.616l6.871 1.832a.5.5 0 0 0 .258 0L15 6.116V4.5a.5.5 0 0 0-.5-.5h-13zM5 2.5A1.5 1.5 0 0 1 6.5 1h3A1.5 1.5 0 0 1 11 2.5V3h-1v-.5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5V3H5v-.5z" />
                </svg>
              </div>
              <div class="feature-1-content">
                <h2>Pembaca Pratinjau</h2>
                <p>Lihat cuplikan bab atau buku sebelum membeli untuk memastikan kontennya sesuai kebutuhan Anda.</p>
                <p><a href="#" class="btn btn-primary px-4 ">Selengkapnya</a></p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="feature-1">
              <div class="icon-wrapper bg-primary">
                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-calculator-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" d="M12 1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4z" />
                  <path d="M4 2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5v-2zm0 4a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm3-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm3-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-4z" />
                </svg>
              </div>
              <div class="feature-1-content">
                <h2>Riwayat Pembelian</h2>
                <p>Cek Riwayat daftar pembelian dan unduh ulang bab atau buku yang pernah Anda beli.</p>
                <p><a href="#" class="btn btn-primary px-4 ">Selengkapnya</a></p>
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
            <div class="copyright">
              <p>
                <a href="#" class="d-block" style="text-decoration: none;">
                  <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                  Copyright &copy;<script>
                    document.write(new Date().getFullYear());
                  </script> All rights reserved | BookChapter.</a>
                <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>


  </div>
  <!-- .site-wrap -->


  <!-- loader -->
  <div id="loader" class="show fullscreen"><svg class="circular" width="48px" height="48px">
      <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee" />
      <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#51be78" />
    </svg></div>

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