<?php

session_start();

// Cek jika pengguna belum login, arahkan ke login.html
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

require 'db.php';

// Query untuk data buku, termasuk gambar
$sqlBooks = "SELECT id AS book_id, title, category, description, image_path FROM book_details ORDER BY category, title ASC";
$resultBooks = $conn->query($sqlBooks);
if (!$resultBooks) {
    die("Error in SQL Query (Books): " . $conn->error);
}

// Query untuk data bab buku
$sqlChapters = "SELECT * FROM chapters";
$resultChapters = $conn->query($sqlChapters);
if (!$resultChapters) {
    die("Error in SQL Query (Chapters): " . $conn->error);
}

// Kelompokkan bab buku berdasarkan book_id
$chapters = [];
if ($resultChapters->num_rows > 0) {
    while ($row = $resultChapters->fetch_assoc()) {
        if (!empty($row['book_id'])) {
            $chapters[$row['book_id']][] = $row;
        }
    }
}

// Ambil kategori dari database untuk dropdown
$categoryQuery = "SELECT DISTINCT category FROM book_details";
$categories = $conn->query($categoryQuery);

// Ambil parameter kategori dan search dari GET
$selected_category = isset($_GET['category']) ? $_GET['category'] : '';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Query untuk data buku berdasarkan kategori dan pencarian judul
$sqlBooks = "SELECT id AS book_id, title, category, description, image_path 
             FROM book_details 
             WHERE 1=1";

$params = [];
$types = "";

// Filter kategori jika dipilih
if (!empty($selected_category)) {
    $sqlBooks .= " AND category = ?";
    $params[] = $selected_category;
    $types .= "s";
}

// Filter pencarian judul jika diinputkan
if (!empty($search)) {
    $sqlBooks .= " AND title LIKE ?";
    $params[] = "%$search%";
    $types .= "s";
}

$sqlBooks .= " ORDER BY category, title ASC";

// Eksekusi query
$stmt = $conn->prepare($sqlBooks);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$resultBooks = $stmt->get_result();


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Book Chapter &mdash; Buku</title>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* Pastikan elemen di baris sejajar vertikal */
        form .row.g-3>div {
            display: flex;
            align-items: center;
            /* Sejajarkan elemen secara vertikal */
        }

        /* Pastikan tombol, dropdown, dan input memiliki tinggi yang sama */
        form .form-control,
        form .btn {
            border-radius: 50px;
            height: calc(2.875rem + 2px);
            /* Samakan tinggi */
            font-size: 1rem;
            /* Ukuran teks seragam */
            padding: 0.375rem 0.75rem;
            /* Padding seragam */
        }


        /* Pastikan border input dan ikon menyatu */
        .input-group {
            display: flex;
            /* Pastikan elemen dalam input-group sejajar */
            width: 100%;
            /* Input group menggunakan 100% lebar */
        }

        .input-group .form-control {
            border-radius: 50px 0 0 50px;
            /* Membulatkan sisi kiri */
            border-right: none;
            /* Hilangkan border kanan */
            flex: 1;
            /* Pastikan input mengambil ruang penuh */
        }

        .input-group .input-group-text {
            border-radius: 0 50px 50px 0;
            /* Membulatkan sisi kanan */
            background-color: #f8f9fa;
            /* Warna latar belakang */
            border-left: none;
            /* Hilangkan border kiri */
            height: calc(2.875rem + 2px);
            /* Samakan tinggi */
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 15px;
            /* Padding untuk ikon */
        }

        /* Pastikan elemen input group berada dalam baris */
        .row.g-3 .input-group {
            width: 100%;
            /* Input group mengikuti lebar kontainernya */
        }

        /* Hover efek untuk ikon */
        .input-group .input-group-text:hover i {
            color: #007bff;
            /* Warna ikon saat hover */
        }


        /* Responsivitas */
        @media (max-width: 576px) {

            .input-group .form-control,
            .input-group .input-group-text {
                height: calc(2.5rem + 2px);
                /* Sesuaikan untuk layar kecil */
            }

            .centered-image {
                max-width: 80%;
                /* Untuk layar kecil, gambar lebih besar */
            }
        }

        /* Kontainer card */
        .card-container {
            border: 1px solid #ccc;
            /* Border card */
            border-radius: 50px;
            /* Membuat sudut card melengkung */
            padding: 20px;
            /* Padding di dalam card */
            position: relative;
            /* Membuat gambar bisa menonjol */
            background-color: #fff;
            /* Warna latar card */
            text-align: center;
            /* Teks di tengah */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* Bayangan lembut */
        }

        /* Kontainer gambar */
        .image-container {
            position: absolute;
            top: -40px;
            /* Posisi gambar di atas card */
            left: 50%;
            /* Posisi di tengah card */
            transform: translateX(-50%);
            /* Menjaga gambar tetap sejajar di tengah */
        }

        .centered-image {
            max-width: 80%;
            /* Batasi lebar gambar maksimal 80% dari card */
            height: auto;
            /* Jaga proporsi gambar */
            margin: -40px auto 20px;
            /* Sesuaikan margin agar gambar berada di tengah atas card */
            display: block;
            /* Memastikan gambar berada di tengah */
            border: 2px solid #fff;
            /* Opsional: tambahkan border putih */
            border-radius: 8px;
            /* Membuat gambar lebih estetis */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* Berikan efek bayangan */
        }

        /* Isi card */
        .feature-1-content {
            margin-top: 50px;
            /* Jarak isi card dari gambar */
        }
    </style>

</head>

<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">

    <div class="site-wrap">

        <!-- Header and Navigation Code Here -->
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

        <div class="intro-section small" style="background-image: url('images/hero_1.jpg');">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-md-7 mx-auto text-center">
                        <div class="intro">
                            <h1>Daftar Buku</h1>
                            <p>Pilih buku berdasarkan kategori dan judul, lalu pilih bab yang ingin Anda checkout.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mt-5">


            <!-- Form Search -->
            <form method="GET" action="dashboard_bab_buku.php" class="mb-4" id="search-form">
                <div class="row g-3 align-items-center justify-content-center">
                    <!-- Dropdown Kategori -->
                    <div class="col-md-4 col-sm-12">
                        <select class="form-control" name="category" onchange="this.form.submit();">
                            <option value="">Semua Kategori</option>
                            <?php while ($category = $categories->fetch_assoc()): ?>
                                <option value="<?= htmlspecialchars($category['category']); ?>"
                                    <?= ($selected_category == $category['category']) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($category['category']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <!-- Search Bar -->
                    <div class="col-md-8 col-sm-12">
                        <div class="input-group">
                            <input type="text" id="search" name="search" class="form-control form-control-lg" placeholder="Cari judul buku..." aria-label="Search">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </form>
        </div><br><br><br>

        <div class="site-section pb-0">
            <div class="container">
                <div class="row">
                    <?php if ($resultBooks->num_rows > 0): ?>
                        <?php while ($row = $resultBooks->fetch_assoc()): ?>
                            <div class="col-lg-4 col-md-6 mb-5">
                                <div class="feature-1 card-container">
                                    <!-- Gambar di atas card -->
                                    <div class="image-container">
                                        <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Image" class="img-fluid centered-image">
                                    </div>
                                    <!-- Isi card -->
                                    <div class="feature-1-content mt-4"><br><br>
                                        <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                                        <span class="position mb-3 d-block">Kategori: <?php echo htmlspecialchars($row['category']); ?></span>
                                        <p><?php echo htmlspecialchars($row['description']); ?></p>
                                        <a href="#" class="btn btn-primary btn-sm mt-2" data-toggle="modal" data-target="#bookModal<?php echo $row['book_id']; ?>">Pilih Buku</a>
                                    </div>
                                </div>
                            </div>


                            <!-- Modal untuk Detail Buku -->
                            <div class="modal fade" id="bookModal<?php echo $row['book_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="bookModalLabel<?php echo $row['book_id']; ?>" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="bookModalLabel<?php echo $row['book_id']; ?>"><?php echo htmlspecialchars($row['title']); ?></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <h6>Bab Buku:</h6>
                                            <?php if (!empty($chapters[$row['book_id']])): ?>
                                                <ul>
                                                    <?php foreach ($chapters[$row['book_id']] as $chapter): ?>
                                                        <li>
                                                            <strong><?php echo htmlspecialchars($chapter['title']); ?></strong><br>
                                                            Deskripsi: <?php echo htmlspecialchars($chapter['description']); ?><br>
                                                            Harga: Rp<?php echo number_format($chapter['price'], 2, ',', '.'); ?><br>
                                                            <a href="checkout.php?chapter_id=<?php echo $chapter['chapter_id']; ?>" class="btn btn-primary btn-sm mt-2">Checkout</a><br><br>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php else: ?>
                                                <p>Tidak ada bab yang tersedia untuk buku ini.</p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-center">Tidak ada buku yang tersedia saat ini.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>


        <!-- Footer Code Here -->
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

    <!-- jQuery, Bootstrap, and other JavaScript files -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
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

<?php
$conn->close();
?>