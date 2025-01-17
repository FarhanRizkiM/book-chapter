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
    <title>Book Chapter | Buku Dibeli</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Muli:300,400,700,900" rel="stylesheet">
    <link rel="stylesheet" href="fonts/icomoon/style.css">
    <link rel="shortcut icon" type="image/png" href="./src/assets/images/logos/logobuku.png" />
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
        .order-list {
            margin-top: 20px;
        }

        .order-item {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            display: flex;
            gap: 15px;
            align-items: flex-start;
        }

        .order-image img {
            width: 100px;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .order-content {
            flex: 1;
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .order-number {
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }

        .order-status {
            font-size: 12px;
            padding: 5px 10px;
            border-radius: 20px;
        }

        .badge-success {
            background-color: #28a745;
            color: #fff;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #000;
        }

        .badge-danger {
            background-color: #dc3545;
            color: #fff;
        }

        .chapter-title {
            font-size: 16px;
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
        }

        .chapter-price {
            font-size: 14px;
            color: #666;
        }

        .actions {
            margin-top: 10px;
        }

        .btn-primary {
            border: none;
            color: #fff;
            border-radius: 20px;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
            color: #fff;
            border-radius: 20px;
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
                            <div class="dropdown">
                                <a href="#" class="small btn btn-primary px-4 py-2 rounded-0 dropdown-toggle" id="accountDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo htmlspecialchars($_SESSION['username']); ?>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="accountDropdown">
                                    <a class="dropdown-item" href="logout.php">Logout</a>
                                </div>
                            </div>
                        <?php else: ?>
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

        <div class="intro-section small" style="background-image: url('images/bkgabout.jpg');">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-md-7 mx-auto text-center" data-aos="fade-up">
                        <div class="intro">
                            <h1>Bab Buku Sudah Dibeli</h1><br>
                            <p>Akses bab-bab yang sudah Anda beli kapan saja. Semua tersedia di satu tempat untuk kemudahan Anda.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="site-section pb-0">
            <div class="container">
                <?php
                // Mengambil daftar bab yang dibeli user
                $orders = $conn->query("SELECT orders.order_id, chapters.title AS chapter_title, chapters.file_path, chapters.price, orders.status, book_details.image_path 
                FROM orders 
                JOIN chapters ON orders.chapter_id = chapters.chapter_id 
                JOIN book_details ON chapters.book_id = book_details.id 
                WHERE orders.user_id = '$user_id'");
                ?>

                <?php if ($orders->num_rows > 0): ?>
                    <div class="order-list">
                        <?php $i = 1; ?>
                        <?php while ($order = $orders->fetch_assoc()): ?>
                            <div class="order-item d-flex flex-wrap align-items-center">
                                <div class="order-image">
                                    <img src="<?= htmlspecialchars($order['image_path']); ?>" alt="Cover Buku" class="img-fluid rounded">
                                </div>
                                <div class="order-content">
                                    <div class="order-header d-flex justify-content-between">
                                        <span class="order-number">Pesanan #<?= $i++ ?></span>
                                        <span class="order-status badge 
                                    <?= $order['status'] == 'approved' ? 'badge-success' : ($order['status'] == 'waiting_confirmation' ? 'badge-warning' : 'badge-danger') ?>">
                                            <?= ucfirst($order['status']); ?>
                                        </span>
                                    </div>
                                    <h5 class="chapter-title mt-2"><?= htmlspecialchars($order['chapter_title']); ?></h5>
                                    <p class="chapter-price">Harga: Rp<?= number_format($order['price'], 2, ',', '.'); ?></p>
                                    <div class="actions mt-3">
                                        <?php if ($order['status'] == 'approved'): ?>
                                            <a href="<?= htmlspecialchars($order['file_path']); ?>" class="btn btn-primary btn-sm" download>Unduh Bab Buku</a>
                                        <?php else: ?>
                                            <span class="btn btn-secondary btn-sm disabled">Unduh Bab Buku</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <h5 class="text-center">Belum ada bab yang dapat diunduh. Pastikan pembayaran Anda sudah diverifikasi.</h5>
                <?php endif; ?>
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