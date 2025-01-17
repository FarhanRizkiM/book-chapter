<?php
session_start();

require 'db.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['order_id'])) {
    header("Location: login.html");
    exit();
}

$order_id = $_GET['order_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $allowed_types = ['png', 'jpg', 'jpeg'];
    $file_name = basename($_FILES['payment_proof']['name']);
    $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $upload_dir = 'uploads/';
    $payment_proof_path = $upload_dir . $file_name;

    if (in_array($file_type, $allowed_types)) {
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        if (move_uploaded_file($_FILES['payment_proof']['tmp_name'], $payment_proof_path)) {
            $sql = "UPDATE orders SET payment_proof_path = '$payment_proof_path', status = 'waiting_confirmation' WHERE order_id = '$order_id'";
            if ($conn->query($sql) === TRUE) {
                header("Location: dashboard_pending_confirmation.php");
                exit();
            } else {
                echo "Error: " . $conn->error;
            }
        } else {
            echo "Failed to upload file. Please check folder permissions.";
        }
    } else {
        echo "Only PNG, JPG, and JPEG files are allowed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Book Chapter | Upload Payment</title>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .upload-container {
            text-align: center;
            padding: 50px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        .upload-title {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .upload-description {
            font-size: 1.2rem;
            color: #555;
            margin-bottom: 20px;
        }

        .upload-icon {
            font-size: 4rem;
            color: #4CAF50;
            margin-bottom: 15px;
        }

        .btn-upload {
            background-color: #4CAF50;
            color: white;
            padding: 15px 30px;
            font-size: 1.2rem;
            border-radius: 50px;
            text-transform: uppercase;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-upload:hover {
            background-color: #45a049;
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

        input[type="file"] {
            display: block;
            margin: 20px auto;
            font-size: 1rem;
            text-align: center;
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
                    <div class="site-logo">
                        <a href="dashboard.php" class="text-nowrap logo-img">
                            <img src="./src/assets/images/logos/logoadmin.svg" width="180" alt="" />
                        </a>
                    </div>
                    <div class="mr-auto">
                        <nav class="site-navigation position-relative text-right" role="navigation">
                            <ul class="site-menu main-menu js-clone-nav mr-auto d-none d-lg-block">
                                <li>
                                    <a href="dashboard.php" class="nav-link text-left">Beranda</a>
                                </li>
                                <li>
                                    <a href="dashboard_buku.php" class="nav-link text-left">Buku</a>
                                </li>
                                <li>
                                    <a href="dashboard_kontak.php" class="nav-link text-left">Bantuan</a>
                                </li>
                            </ul>
                        </nav>

                    </div>
                    <div class="ml-auto">
                        <div class="social-wrap">

                            <a href="#" class="d-inline-block d-lg-none site-menu-toggle js-menu-toggle text-black"><span
                                    class="icon-menu h3"></span></a>
                        </div>
                    </div>

                </div>
            </div>

        </header>


        <div class="intro-section small" style="background-image: url('images/backgroundpage2.jpg');">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-md-7 mx-auto text-center" data-aos="fade-up">
                        <div class="intro">
                            <h1>Upload Bukti Pembayaran</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="site-section">
            <div class="container">
                <div class="upload-container">
                    <!-- Ikon Upload -->
                    <i class="fas fa-file-upload upload-icon"></i>
                    <h2 class="upload-title">Upload Bukti Pembayaran</h2>
                    <p class="upload-description">
                        Unggah bukti pembayaran Anda dalam format PNG, JPG, atau JPEG.
                        <br>Pastikan file tidak melebihi ukuran maksimum yang diizinkan.
                    </p>
                    <form method="POST" enctype="multipart/form-data">
                        <input type="file" name="payment_proof" accept="image/png, image/jpg, image/jpeg" required>
                        <button type="submit" class="btn-upload">Upload</button>
                    </form>
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