<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit();
}

require '../db.php';

// Query untuk mendapatkan data hasil pengerjaan bab buku
$sql_uploads = "SELECT uploads.upload_id, uploads.file_path, uploads.uploaded_at, users.username, chapters.title AS chapter_title 
                FROM uploads
                JOIN orders ON uploads.order_id = orders.order_id
                JOIN users ON orders.user_id = users.user_id
                JOIN chapters ON orders.chapter_id = chapters.chapter_id";

$result_uploads = $conn->query($sql_uploads);

// Hitung jumlah hasil pengerjaan bab buku berdasarkan jumlah baris hasil query
$chapter_count = $result_uploads->num_rows;
?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Book Chapter | Hasil Pengerjaan</title>
    <link rel="shortcut icon" type="image/png" href="../src/assets/images/logos/logobuku.png" />
    <link rel="stylesheet" href="../src/assets/css/styles.min.css" />
</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar Start -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div>
                <div class="brand-logo d-flex align-items-center justify-content-between">
                    <a href="dashboard.php" class="text-nowrap logo-img">
                        <img src="../src/assets/images/logos/logoadmin.svg" width="180" alt="" />
                    </a>
                    <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                        <i class="ti ti-x fs-8"></i>
                    </div>
                </div>
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
                    <ul id="sidebarnav">
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">Beranda</span>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="dashboard.php" aria-expanded="false">
                                <span>
                                    <i class="ti ti-layout-dashboard"></i>
                                </span>
                                <span class="hide-menu">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">Manajemen</span>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="data-user.php" aria-expanded="false">
                                <span>
                                    <i class="ti ti-users"></i>
                                </span>
                                <span class="hide-menu">Data Pengguna</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="book-chapter.php" aria-expanded="false">
                                <span>
                                    <i class="ti ti-book-2"></i>
                                </span>
                                <span class="hide-menu">Bab Buku</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="category-chapter.php" aria-expanded="false">
                                <span>
                                    <i class="ti ti-category"></i>
                                </span>
                                <span class="hide-menu">Kategori Buku</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="payment.php" aria-expanded="false">
                                <span>
                                    <i class="ti ti-credit-card"></i>
                                </span>
                                <span class="hide-menu">Pembayaran</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="upload_buku.php" aria-expanded="false">
                                <span>
                                    <i class="ti ti-book"></i>
                                </span>
                                <span class="hide-menu">Hasil Pengerjaan</span>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!--  Sidebar End -->
        <!--  Main wrapper -->
        <div class="body-wrapper">
            <!--  Header Start -->
            <header class="app-header">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <ul class="navbar-nav">
                        <li class="nav-item d-block d-xl-none">
                            <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                                <i class="ti ti-menu-2"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-icon-hover" href="javascript:void(0)">
                                <i class="ti ti-bell-ringing"></i>
                                <div class="notification bg-primary rounded-circle"></div>
                            </a>
                        </li>
                    </ul>
                    <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
                        <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                            <li class="nav-item dropdown">
                                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="ti ti-user fs-6"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                                    <div class="message-body">
                                        <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                                            <!-- Menampilkan nama admin dari session -->
                                            <p class="mb-0 fs-3"><?php echo isset($_SESSION['admin_full_name']) ? $_SESSION['admin_full_name'] : 'Admin'; ?></p>
                                        </a>
                                        <a href="logout.php" onclick="confirmLogout(event)" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <!--  Header End -->
            <div class="container-fluid">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title fw-semibold mb-3">Daftar Hasil Pengerjaan Bab Buku</h4><br>
                            <div class="card">
                                <div class="card-body p-4">
                                    <h5 class="card-title">Jumlah Hasil Pengerjaan Bab Buku</h5>
                                    <p class="card-text">Total Pengerjaan Bab Buku: <?php echo $chapter_count; ?></p>
                                </div>
                            </div><br>
                            <div class="card mb-0">
                                <div class="card-body p-4">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">No</th>
                                                    <th scope="col">Judul Bab</th>
                                                    <th scope="col">Nama Pengguna</th>
                                                    <th scope="col">File</th>
                                                    <th scope="col">Tanggal Unggah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if ($result_uploads->num_rows > 0) {
                                                    $no = 1;
                                                    while ($row = $result_uploads->fetch_assoc()) {
                                                        echo "<tr>";
                                                        echo "<td>" . $no++ . "</td>";
                                                        echo "<td>" . htmlspecialchars($row['chapter_title']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                                                        echo "<td><a href='" . htmlspecialchars($row['file_path']) . "' target='_blank'>Unduh File</a></td>";
                                                        echo "<td>" . htmlspecialchars($row['uploaded_at']) . "</td>";
                                                        echo "</tr>";
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='5' class='text-center'>Belum ada bab buku yang diunggah</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function confirmLogout(event) {
            // Mencegah aksi default tombol
            event.preventDefault();

            // Tampilkan konfirmasi
            if (confirm("Apakah Anda yakin ingin logout?")) {
                // Jika OK ditekan, arahkan ke logout.php
                window.location.href = 'logout.php';
            }
            // Jika Cancel ditekan, tidak melakukan apa-apa
        }
    </script>

    <script src="../src/assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../src/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../src/assets/js/sidebarmenu.js"></script>
    <script src="../src/assets/js/app.min.js"></script>
    <script src="../src/assets/libs/simplebar/dist/simplebar.js"></script>
</body>

</html>