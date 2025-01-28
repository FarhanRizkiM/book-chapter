<?php

session_start();
if (!isset($_SESSION['admin_logged_in'])) {
  header('Location: index.php');
  exit();
}

require '../db.php';

// Set timezone untuk memastikan kesesuaian waktu
date_default_timezone_set('Asia/Jakarta');

// Fungsi untuk men-debug query
function debugQuery($conn, $query)
{
  $result = $conn->query($query);
  if (!$result) {
    die("Query Error: " . $conn->error);
  }
  return $result;
}

// Penghasilan harian
$today = date('Y-m-d');
$yesterday = date('Y-m-d', strtotime('-1 day'));

// Query untuk penghasilan hari ini
$sqlDailyIncome = "
    SELECT IFNULL(SUM(c.price), 0) AS today_income 
    FROM orders o
    JOIN chapters c ON o.chapter_id = c.chapter_id
    WHERE o.status = 'approved' AND DATE(o.order_date) = '$today'
";
$todayIncomeResult = debugQuery($conn, $sqlDailyIncome);
$todayIncome = $todayIncomeResult->fetch_assoc()['today_income'] ?? 0;

// Query untuk penghasilan kemarin
$sqlYesterdayIncome = "
    SELECT IFNULL(SUM(c.price), 0) AS yesterday_income 
    FROM orders o
    JOIN chapters c ON o.chapter_id = c.chapter_id
    WHERE o.status = 'approved' AND DATE(o.order_date) = '$yesterday'
";
$yesterdayIncomeResult = debugQuery($conn, $sqlYesterdayIncome);
$yesterdayIncome = $yesterdayIncomeResult->fetch_assoc()['yesterday_income'] ?? 0;

// Hitung pertumbuhan harian
$dailyGrowth = $yesterdayIncome > 0 ? (($todayIncome - $yesterdayIncome) / $yesterdayIncome) * 100 : 0;

// Penghasilan bulanan
$currentMonth = date('Y-m');
$lastMonth = date('Y-m', strtotime('-1 month'));

// Query untuk penghasilan bulan ini
$sqlMonthlyIncome = "
    SELECT IFNULL(SUM(c.price), 0) AS current_month_income 
    FROM orders o
    JOIN chapters c ON o.chapter_id = c.chapter_id
    WHERE o.status = 'approved' AND DATE_FORMAT(o.order_date, '%Y-%m') = '$currentMonth'
";
$currentMonthIncomeResult = debugQuery($conn, $sqlMonthlyIncome);
$currentMonthIncome = $currentMonthIncomeResult->fetch_assoc()['current_month_income'] ?? 0;

// Query untuk penghasilan bulan lalu
$sqlLastMonthIncome = "
    SELECT IFNULL(SUM(c.price), 0) AS last_month_income 
    FROM orders o
    JOIN chapters c ON o.chapter_id = c.chapter_id
    WHERE o.status = 'approved' AND DATE_FORMAT(o.order_date, '%Y-%m') = '$lastMonth'
";
$lastMonthIncomeResult = debugQuery($conn, $sqlLastMonthIncome);
$lastMonthIncome = $lastMonthIncomeResult->fetch_assoc()['last_month_income'] ?? 0;

// Hitung pertumbuhan bulanan
$monthlyGrowth = $lastMonthIncome > 0 ? (($currentMonthIncome - $lastMonthIncome) / $lastMonthIncome) * 100 : 0;

// Query untuk recent transactions (10 transaksi terbaru)
$sqlRecentTransactions = "
    SELECT o.order_date, c.title AS chapter_title, o.user_id, c.price 
    FROM orders o
    JOIN chapters c ON o.chapter_id = c.chapter_id
    WHERE o.status = 'approved'
    ORDER BY o.order_date DESC
    LIMIT 10
";
$recentTransactionsResult = debugQuery($conn, $sqlRecentTransactions);

// Data untuk ditampilkan
$recentTransactions = [];
while ($row = $recentTransactionsResult->fetch_assoc()) {
  $recentTransactions[] = $row;
}

$sqlDetailedTransactions = "
   SELECT 
    o.order_id AS transaction_id,
    u.full_name AS user_name, -- Menggunakan 'full_name' sesuai dengan struktur tabel
    c.title AS chapter_title,
    c.price AS chapter_price,
    o.order_date,
    CASE 
        WHEN c.price < 10000 THEN 'Low'
        WHEN c.price BETWEEN 10000 AND 20000 THEN 'Medium'
        ELSE 'High'
    END AS priority,
    CASE 
        WHEN c.price < 10000 THEN '#00A3FF'
        WHEN c.price BETWEEN 10000 AND 20000 THEN '#FFC107'
        ELSE '#FF5722'
    END AS priority_color
FROM orders o
JOIN chapters c ON o.chapter_id = c.chapter_id
JOIN users u ON o.user_id = u.user_id -- Menggunakan 'user_id' sebagai foreign key
WHERE o.status = 'approved'
ORDER BY o.order_date DESC
LIMIT 10;


";
$detailedTransactionsResult = debugQuery($conn, $sqlDetailedTransactions);

$detailedTransactions = [];
while ($row = $detailedTransactionsResult->fetch_assoc()) {
  $detailedTransactions[] = $row;
}

?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Book Chapter | Dashboard Admin</title>
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
        <!--  Row 1 -->
        <!-- Row for Sales Overview -->
        <div class="row">
          <div class="col-lg-12">
            <div class="card w-100">
              <div class="card-body">
                <div class="d-sm-flex d-block align-items-center justify-content-between mb-4">
                  <h5 class="card-title fw-semibold mb-0">Sales Overview</h5>
                  <select class="form-select w-auto">
                    <option value="1">March 2023</option>
                    <option value="2">April 2023</option>
                    <option value="3">May 2023</option>
                    <option value="4">June 2023</option>
                  </select>
                </div>
                <div id="chart"></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Row for Daily and Monthly Income -->
        <div class="row mt-4">
          <!-- Penghasilan Harian -->
          <div class="col-lg-6">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Penghasilan Harian</h5>
                <h4 class="fw-bold">Rp <?= number_format($todayIncome, 0, ',', '.') ?></h4>
                <div class="d-flex align-items-center">
                  <span class="me-2 rounded-circle bg-<?= $dailyGrowth >= 0 ? 'light-success' : 'light-danger' ?> d-flex align-items-center justify-content-center">
                    <i class="ti ti-arrow-<?= $dailyGrowth >= 0 ? 'up-left text-success' : 'down-right text-danger' ?>"></i>
                  </span>
                  <p class="mb-0 fs-6"><?= $dailyGrowth >= 0 ? '+' : '' ?><?= number_format($dailyGrowth, 2) ?>%</p>
                  <p class="mb-0 ms-2 fs-6">Dibandingkan kemarin</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Penghasilan Bulanan -->
          <div class="col-lg-6">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Penghasilan Bulanan</h5>
                <h4 class="fw-bold">Rp <?= number_format($currentMonthIncome, 0, ',', '.') ?></h4>
                <div class="d-flex align-items-center">
                  <span class="me-2 rounded-circle bg-<?= $monthlyGrowth >= 0 ? 'light-success' : 'light-danger' ?> d-flex align-items-center justify-content-center">
                    <i class="ti ti-arrow-<?= $monthlyGrowth >= 0 ? 'up-left text-success' : 'down-right text-danger' ?>"></i>
                  </span>
                  <p class="mb-0 fs-6"><?= $monthlyGrowth >= 0 ? '+' : '' ?><?= number_format($monthlyGrowth, 2) ?>%</p>
                  <p class="mb-0 ms-2 fs-6">Dibandingkan bulan lalu</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-4 d-flex align-items-stretch">
            <div class="card w-100">
              <div class="card-body p-4">
                <div class="mb-4">
                  <h5 class="card-title fw-semibold">Recent Transactions</h5>
                </div>
                <div style="position: relative; padding-left: 30px;">
                  <!-- Garis Tengah -->
                  <div style="position: absolute; top: 0; left: 14px; width: 2px; height: 100%; background-color: #007bff;"></div>
                  <!-- Item -->
                  <?php foreach ($recentTransactions as $transaction) : ?>
                    <div style="position: relative; margin-bottom: 20px; display: flex; align-items: flex-start;">
                      <!-- Lingkaran -->
                      <div style="position: absolute; top: 5px; left: 8px; width: 12px; height: 12px; border: 2px solid #007bff; background-color: #fff; border-radius: 50%;"></div>
                      <!-- Konten -->
                      <div style="margin-left: 30px;">
                        <div class="text-primary fw-bold mb-1">
                          <?= date('d M Y | H:i', strtotime($transaction['order_date'])) ?>
                          <!-- Format: 'dd Mon yyyy, HH:mm' -->
                        </div>
                        <div class="fw-semibold mb-1">
                          <?= htmlspecialchars($transaction['chapter_title']) ?>
                        </div>
                        <div class="text-muted">
                          Rp <?= number_format($transaction['price'], 0, ',', '.') ?>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-8 d-flex align-items-stretch">
            <div class="card w-100">
              <div class="card-body p-4">
                <h5 class="card-title fw-semibold mb-4">Recent Transactions</h5>
                <div class="table-responsive">
                  <table class="table text-nowrap mb-0 align-middle">
                    <thead class="text-dark fs-4">
                      <tr>
                        <th>Transaction ID</th>
                        <th>User</th>
                        <th>Chapter Title</th>
                        <th>Price</th>
                        <th>Priority</th>
                        <th>Date</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($detailedTransactions as $transaction): ?>
                        <tr>
                          <td><?= htmlspecialchars($transaction['transaction_id']) ?></td>
                          <td>
                            <h6 class="fw-semibold mb-1"><?= htmlspecialchars($transaction['user_name']) ?></h6>
                          </td>
                          <td><?= htmlspecialchars($transaction['chapter_title']) ?></td>
                          <td>Rp <?= number_format($transaction['chapter_price'], 0, ',', '.') ?></td>
                          <td>
                            <span
                              class="badge rounded-pill"
                              style="background-color: <?= $transaction['priority_color'] ?>;">
                              <?= htmlspecialchars($transaction['priority']) ?>
                            </span>
                          </td>
                          <td><?= date('d M Y, H:i', strtotime($transaction['order_date'])) ?></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>


        </div>
        <div class="py-6 px-6 text-center">
          <p class="mb-0 fs-4">Design and Developed by BookChapter</p>
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
  <script src="../src/assets/libs/apexcharts/dist/apexcharts.min.js"></script>
  <script src="../src/assets/libs/simplebar/dist/simplebar.js"></script>
  <script src="../src/assets/js/dashboard.js"></script>
</body>

</html>