<?php
session_start();

require 'db.php';

// Cek jika pengguna belum login, arahkan ke login.html
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Book Chapter</title>
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
        .card {
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .card-img-top {
            width: 100%;
            height: auto;
            /* Agar gambar mempertahankan rasio aslinya */
            object-fit: contain;
            /* Menampilkan gambar penuh tanpa memotong */
            border-radius: 10px 10px 0 0;
            background-color: #f8f9fa;
            /* Tambahkan latar belakang jika gambar tidak memenuhi card */
        }

        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            text-align: center;
            min-height: 150px;
            /* Atur tinggi minimum untuk bagian konten */
        }

        .card-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 5px;
            white-space: normal;
        }

        .text-muted {
            margin-bottom: 10px;
            font-size: 0.85rem;
        }

        .text-danger {
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 15px;
        }


        .btn-sm {
            margin-top: auto;
            /* Agar tombol berada di bagian bawah card */
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
                    <div class="col-lg-9 d-none d-lg-block"></div>
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
                                <li class="active"><a href="dashboard.php" class="nav-link text-left">Beranda</a></li>
                                <li><a href="dashboard_buku.php" class="nav-link text-left">Buku</a></li>
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
                                    <li class="nav-item active">
                                        <a class="nav-link" href="dashboard.php">Beranda</a>
                                    </li>
                                    <li class="nav-item">
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


        <!-- Bagian konten utama -->
        <div class="hero-slide owl-carousel site-blocks-cover">
            <div class="intro-section" style="background-image: url('images/backgroundpage1.jpg');">
                <div class="container">
                    <div class="row align-items-center justify-content-center">
                        <div class="col-md-7 mx-auto text-center" data-aos="fade-up">
                            <h1>Jelajahi Bab Buku Pilihan</h1>
                            <p>Temukan bab buku berkualitas tanpa harus membeli keseluruhan buku. Akses pengetahuan dengan mudah dan praktis.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="intro-section" style="background-image: url('images/backgroundpage2.jpg');">
                <div class="container">
                    <div class="row align-items-center justify-content-center">
                        <div class="col-md-7 mx-auto text-center" data-aos="fade-up">
                            <div class="intro">
                                <h1>Beli Hanya yang Anda Butuhkan</h1>
                                <p>Hemat waktu dan biaya dengan membeli bab-bab tertentu dari berbagai judul buku populer dan edukatif.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="site-section">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="font-weight-bold">Daftar Pilihan Buku</h3>
                    <a href="dashboard_bab_buku.php" class="btn btn-primary px-4 py-2">Lihat Semua</a>
                </div>
                <div class="row">
                    <?php
                    // Query untuk mengambil data buku dari tabel `book_details`
                    $sqlBooks = "SELECT id, category, title, image_path, description FROM book_details LIMIT 4";
                    $resultBooks = $conn->query($sqlBooks);

                    if ($resultBooks->num_rows > 0):
                        while ($row = $resultBooks->fetch_assoc()):
                    ?>
                            <div class="col-md-3 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <!-- Gambar Buku -->
                                    <img src="<?= htmlspecialchars($row['image_path']) ?>" alt="<?= htmlspecialchars($row['title']) ?>" class="card-img-top">

                                    <!-- Detail Buku -->
                                    <div class="card-body text-center">
                                        <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
                                        <p class="text-danger"><?= htmlspecialchars($row['category']) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php
                        endwhile;
                    else:
                        ?>
                        <p class="text-center">No books available at the moment.</p>
                    <?php endif; ?>
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