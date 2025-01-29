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


function truncateText($text, $wordLimit = 50)
{
    $words = explode(' ', $text);
    if (count($words) > $wordLimit) {
        return implode(' ', array_slice($words, 0, $wordLimit));
    }
    return $text;
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Book Chapter | Dashboard Bab Buku</title>
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
            border-radius: 20px;
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
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            /* Membuat card mengambil tinggi penuh */
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

        /* Atur feature-1-content agar tinggi seragam */
        .feature-1-content {
            flex-grow: 1;
            /* Membuat konten mengisi ruang kosong */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            text-align: center;
        }

        .modal {
            overflow-y: auto !important;
        }

        .modal .modal-content {
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            padding: 20px;
        }

        .modal-header {
            border-bottom: none;
        }

        .modal-footer {
            border-top: none;
            justify-content: flex-end;
        }

        .modal-title {
            font-weight: bold;
            font-size: 1.5rem;
        }

        .list-group-item {
            border: none;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .list-group-item:hover {
            background-color: #f1f1f1;
            transition: background-color 0.3s;
        }

        .list-group-item h6 {
            font-weight: bold;
            color: #0056b3;
        }

        .list-group-item p {
            color: #000;
            font-weight: normal;
        }

        .btn-primary {
            background-color: #0056b3;
            border: none;
            border-radius: 20px;
            padding: 5px 15px;
            font-size: 0.9rem;
        }

        .btn-primary:hover {
            background-color: #007bff;
        }

        .btn-success {
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
            font-size: 1rem;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
            font-size: 1rem;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
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

        <div class="intro-section small" style="background-image: url('images/bkgabout.jpg');">
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
                                        <p>
                                            <span class="short-description">
                                                <?php echo htmlspecialchars(truncateText($row['description'], 30)); ?>
                                            </span>
                                            <span class="full-description" style="display: none;">
                                                <?php echo htmlspecialchars($row['description']); ?>
                                            </span>
                                            <a href="javascript:void(0);" class="toggle-description">...</a>
                                        </p>

                                        <a href="#" class="btn btn-primary btn-sm mt-2" data-toggle="modal" data-target="#bookModal<?php echo $row['book_id']; ?>">Pilih Buku</a>
                                    </div>
                                </div>
                            </div>


                            <!-- Modal Utama -->
                            <div class="modal fade" id="bookModal<?= $row['book_id']; ?>" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title"><?= htmlspecialchars($row['title']); ?></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <h6 class="font-weight-bold text-center mb-4">Daftar Bab Buku</h6>
                                            <ul class="list-group">
                                                <?php if (!empty($chapters[$row['book_id']])): ?>
                                                    <?php foreach ($chapters[$row['book_id']] as $chapter): ?>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <h6><?= htmlspecialchars($chapter['title']); ?></h6>
                                                                <p class="text-muted"><?= htmlspecialchars(truncateText($chapter['description'], 15)); ?></p>
                                                            </div>
                                                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#chapterModal<?= $chapter['chapter_id']; ?>" data-dismiss="modal">Detail</button>
                                                        </li>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <li class="list-group-item">Tidak ada bab untuk buku ini.</li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Detail Bab -->
                            <?php foreach ($chapters[$row['book_id']] as $chapter): ?>
                                <div class="modal fade" id="chapterModal<?= $chapter['chapter_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="chapterModalLabel<?= $chapter['chapter_id']; ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-md" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="chapterModalLabel<?= $chapter['chapter_id']; ?>"><?= htmlspecialchars($chapter['title']); ?></h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Deskripsi:</strong> <?= htmlspecialchars($chapter['description']); ?></p>
                                                <p><strong>Harga:</strong> Rp<?= number_format($chapter['price'], 2, ',', '.'); ?></p>
                                                <?php
                                                // Status pesanan global
                                                $sqlGlobalStatus = "SELECT status FROM orders WHERE chapter_id = ? AND status IN ('waiting_confirmation', 'approved') LIMIT 1";
                                                $stmtGlobalStatus = $conn->prepare($sqlGlobalStatus);
                                                $stmtGlobalStatus->bind_param("i", $chapter['chapter_id']);
                                                $stmtGlobalStatus->execute();
                                                $resultGlobalStatus = $stmtGlobalStatus->get_result();
                                                $globalOrderStatus = $resultGlobalStatus->fetch_assoc()['status'] ?? null;

                                                // Status pesanan user saat ini
                                                $sqlOrderStatus = "SELECT status FROM orders WHERE user_id = ? AND chapter_id = ? LIMIT 1";
                                                $stmtOrderStatus = $conn->prepare($sqlOrderStatus);
                                                $stmtOrderStatus->bind_param("ii", $user_id, $chapter['chapter_id']);
                                                $stmtOrderStatus->execute();
                                                $resultOrderStatus = $stmtOrderStatus->get_result();
                                                $userOrderStatus = $resultOrderStatus->fetch_assoc()['status'] ?? null;

                                                // Tampilan tombol
                                                if ($userOrderStatus === 'waiting_confirmation') {
                                                    echo '<button class="btn btn-warning" disabled>Sedang Diverifikasi</button>';
                                                } elseif ($userOrderStatus === 'approved') {
                                                    echo '<button class="btn btn-success" disabled>Sudah Dibeli</button>';
                                                } elseif ($userOrderStatus === 'rejected') {
                                                    echo '<a href="checkout.php?chapter_id=' . $chapter['chapter_id'] . '" class="btn btn-danger">Checkout Ulang</a>';
                                                } elseif ($globalOrderStatus === 'waiting_confirmation') {
                                                    echo '<button class="btn btn-secondary" disabled>Sedang Dalam Proses Pembelian</button>';
                                                } elseif ($globalOrderStatus === 'approved') {
                                                    echo '<button class="btn btn-success" disabled>Sudah Dibeli</button>';
                                                } else {
                                                    echo '<a href="checkout.php?chapter_id=' . $chapter['chapter_id'] . '" class="btn btn-primary">Checkout</a>';
                                                }
                                                ?>
                                            </div>
                                            <div class="modal-footer">
                                                <button
                                                    onclick="window.open('preview_bab.php?chapter_id=<?= $chapter['chapter_id']; ?>', '_blank');"
                                                    class="btn btn-secondary">
                                                    Preview Bab
                                                </button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="$('#bookModal<?= $row['book_id']; ?>').modal('show')">Kembali</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>



                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-center">Tidak ada buku yang tersedia saat ini.</p>
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

    <script>
        // Mengatasi masalah scroll saat kembali ke modal utama
        $(document).on('hidden.bs.modal', '.modal', function() {
            if ($('.modal:visible').length) {
                $('body').addClass('modal-open');
            } else {
                $('body').removeClass('modal-open');
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const toggleLinks = document.querySelectorAll('.toggle-description');

            toggleLinks.forEach(function(link) {
                link.addEventListener('click', function() {
                    const shortDesc = this.previousElementSibling.previousElementSibling;
                    const fullDesc = this.previousElementSibling;

                    if (shortDesc.style.display === 'none') {
                        shortDesc.style.display = 'inline';
                        fullDesc.style.display = 'none';
                        this.textContent = '...';
                    } else {
                        shortDesc.style.display = 'none';
                        fullDesc.style.display = 'inline';
                        this.textContent = ' Tampilkan lebih sedikit';
                    }
                });
            });
        });
    </script>

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