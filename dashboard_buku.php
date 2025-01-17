<?php
session_start();
require 'db.php';

// Cek jika pengguna belum login, arahkan ke login.html
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Book Chapter | Dashboard Buku</title>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .dashboard-card {
            text-align: center;
            background-color: rgb(243, 243, 243);
            border-radius: 10px;
            padding: 30px 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
        }

        .dashboard-icon {
            font-size: 50px;
            color: rgb(6, 97, 194);
            margin-bottom: 20px;
        }

        .dashboard-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
        }

        .dashboard-text {
            color: #666;
            font-size: 1rem;
            margin-bottom: 20px;
        }

        .btn-dashboard {
            background-color: #0056b3;
            color: white;
            border-radius: 50px;
            padding: 10px 25px;
            font-size: 1rem;
            text-transform: uppercase;
            text-decoration: none;
            transition: background-color 0.3s, color 0.3s;
        }

        .btn-dashboard:hover {
            background-color: #007bff;
            color: #ffffff;
            /* Pastikan warna tetap kontras */
        }

        .dashboard-container {
            margin-top: 50px;
        }

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
                        <?php if (isset($_SESSION['user_id'])): ?>
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
                        <a href="dashboard.php" class="text-nowrap logo-img">
                            <img src="./src/assets/images/logos/logoadmin.svg" width="180" alt="" />
                        </a>
                    </div>

                    <!-- Navigation (for Desktop and Mobile) -->
                    <div class="mr-auto">
                        <!-- Navbar for mobile devices -->
                        <nav class="site-navigation position-relative text-right" role="navigation">
                            <ul class="site-menu main-menu js-clone-nav mr-auto d-none d-lg-block">
                                <li><a href="dashboard.php" class="nav-link text-left">Beranda</a></li>
                                <li class="active"><a href="dashboard_buku.php" class="nav-link text-left">Buku</a></li>
                                <li><a href="dashboard_kontak.php" class="nav-link text-left">Bantuan</a></li>
                            </ul>
                        </nav>

                        <!-- Mobile Navbar Toggle Button -->
                        <nav class="navbar navbar-expand-lg navbar-light d-lg-none">
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarNav">
                                <ul class="navbar-nav ml-auto">
                                    <li class="nav-item">
                                        <a class="nav-link" href="dashboard.php">Beranda</a>
                                    </li>
                                    <li class="nav-item active">
                                        <a class="nav-link" href="dashboard_buku.php">Buku</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="dashboard_kontak.php">Bantuan</a>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                    </div>

                    <!-- User Account Menu (for logged-in users) -->
                    <div class="ml-auto">
                        <!-- <div class="">
                    <a class="">
                        <span class=""></span>
                    </a>
                </div> -->
                    </div>
                </div>
            </div>
        </header>


        <div class="intro-section small" style="background-image: url('images/bkgbuku.jpg');">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-md-7 mx-auto text-center" data-aos="fade-up">
                        <div class="intro">
                            <h1>Temukan Bab Buku</h1><br>
                            <p>Temukan berbagai buku dan bab berkualitas sesuai kebutuhan Anda di BookChapter. Mulai eksplorasi pengetahuan sekarang!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="site-section">
            <div class="container">
                <div class="row dashboard-container">
                    <!-- Card 1 -->
                    <div class="col-md-4 mb-4">
                        <div class="dashboard-card">
                            <i class="fas fa-book-open dashboard-icon"></i>
                            <h3 class="dashboard-title">Daftar Bab Buku</h3>
                            <p class="dashboard-text">Telusuri bab buku dari berbagai kategori. Pilih yang sesuai kebutuhan Anda.</p>
                            <a href="dashboard_bab_buku.php" class="btn-dashboard">Selengkapnya</a>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="col-md-4 mb-4">
                        <div class="dashboard-card">
                            <i class="fas fa-shopping-cart dashboard-icon"></i>
                            <h3 class="dashboard-title">Bab Buku yang Dibeli</h3>
                            <p class="dashboard-text">Lihat semua bab buku yang sudah Anda beli sebelumnya. Akses kapan saja.</p>
                            <a href="dashboard_buku_dibeli.php" class="btn-dashboard">Selengkapnya</a>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="col-md-4 mb-4">
                        <div class="dashboard-card">
                            <i class="fas fa-upload dashboard-icon"></i>
                            <h3 class="dashboard-title">Upload Hasil Pengerjaan</h3>
                            <p class="dashboard-text">Unggah hasil pekerjaan atau catatan dari bab yang telah Anda pelajari.</p>
                            <a href="dashboard_upload_pengerjaan.php" class="btn-dashboard">Selengkapnya</a>
                        </div>
                    </div>
                </div>
            </div>
        </div><br><br><br>

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
    <script src="js/project-navigation.js"></script>


    <script src="js/main.js"></script>

</body>

</html>