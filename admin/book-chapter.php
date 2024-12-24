<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
  header('Location: index.php');
  exit();
}

require '../db.php';

// Proses form input
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $action = $_POST['action'];

  if ($action == 'add') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];

    // Validasi input
    if (empty($title) || empty($description) || empty($price) || empty($category_id)) {
      $_SESSION['error'] = "Semua field wajib diisi!";
      header("Location: book-chapter.php");
      exit;
    }

    // Mengupload file .docx
    $file_name = str_replace(' ', '_', basename($_FILES['chapter_file']['name']));
    $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_types = ['docx'];
    if (!in_array($file_type, $allowed_types)) {
      $_SESSION['error'] = "Hanya file DOCX yang diperbolehkan.";
      header("Location: book-chapter.php");
      exit;
    }

    $upload_dir = '../uploads/chapters/';
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    $file_path = $upload_dir . $file_name;
    if (!move_uploaded_file($_FILES['chapter_file']['tmp_name'], $file_path)) {
      $_SESSION['error'] = "Gagal mengunggah file bab buku.";
      header("Location: book-chapter.php");
      exit;
    }

    // Path file yang disimpan ke database
    $file_path_db = 'uploads/chapters/' . $file_name;

    // Simpan ke database
    $stmt = $conn->prepare("INSERT INTO chapters (book_id, title, description, price, file_path) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issds", $category_id, $title, $description, $price, $file_path_db);

    if ($stmt->execute()) {
      $_SESSION['success'] = "Bab buku berhasil ditambahkan!";
    } else {
      $_SESSION['error'] = "Error: " . $stmt->error;
    }
    $stmt->close();

    header("Location: book-chapter.php");
    exit;
  } elseif ($action == 'delete') {
    $chapter_id = $_POST['chapter_id'];
    $stmt = $conn->prepare("DELETE FROM chapters WHERE chapter_id = ?");
    $stmt->bind_param("i", $chapter_id);
    if ($stmt->execute()) {
      $_SESSION['success'] = "Bab buku berhasil dihapus!";
    } else {
      $_SESSION['error'] = "Error: " . $stmt->error;
    }
    $stmt->close();

    header("Location: book-chapter.php");
    exit;
  }
}

// Query untuk kategori buku
$categories = $conn->query("SELECT id, title FROM book_details");
if (!$categories) {
  die("Error: " . $conn->error);
}

// Query untuk daftar bab buku
$chapters = $conn->query("SELECT chapters.*, book_details.title AS category_title FROM chapters LEFT JOIN book_details ON chapters.book_id = book_details.id");
if (!$chapters) {
  die("Error: " . $conn->error);
}

// Hitung jumlah bab buku
$book_count = $conn->query("SELECT COUNT(*) as total_book FROM chapters")->fetch_assoc()['total_book'] ?? 0;
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Book Chapter | Bab Buku</title>
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
              <h4 class="card-title fw-semibold mb-3">Manajemen Bab Buku</h4><br>
              <div class="card">
                <div class="card-body p-4">
                  <h5 class="card-title">Jumlah Bab Buku</h5>
                  <p class="card-text">Total Buku: <?php echo $book_count; ?></p>
                </div>
              </div><br>
              <h4 class="card-title fw-semibold mb-3">Form Input Bab Buku</h4>
              <div class="card">
                <div class="card-body p-3">
                  <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                      <label for="title">Judul Bab Buku</label>
                      <input type="text" class="form-control" id="title" name="title" placeholder="Judul Bab Buku" required>
                    </div>
                    <div class="mb-3">
                      <label for="description">Deskripsi</label>
                      <textarea class="form-control" id="description" name="description" rows="3" placeholder="Deskripsi" required></textarea>
                    </div>
                    <div class="mb-3">
                      <label for="price">Harga</label>
                      <input type="number" class="form-control" id="price" name="price" placeholder="Harga" required>
                    </div>
                    <div class="mb-3">
                      <label for="chapter_file">File Bab (.docx)</label>
                      <input type="file" class="form-control" id="chapter_file" name="chapter_file" required>
                    </div>
                    <div class="mb-3">
                      <label for="category_id">Pilih Judul Buku</label>
                      <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">Judul Buku</option>
                        <?php while ($category = $categories->fetch_assoc()): ?>
                          <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['title']) ?></option>
                        <?php endwhile; ?>
                      </select>
                    </div>
                    <button type="submit" class="btn btn-primary" name="action" value="add">Tambah Bab</button>
                  </form>
                </div>
              </div>
              <h4 class="card-title fw-semibold mb-3">Daftar Bab Buku</h4>
              <div class="card">
                <div class="card-body p-2">
                  <div class="table-responsive">
                    <table class="table">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Judul Buku</th>
                          <th>Bab Buku</th>
                          <th>Deskripsi Bab Buku</th>
                          <th>Harga</th>
                          <th>File</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php while ($chapter = $chapters->fetch_assoc()): ?>
                          <tr>
                            <td><?= $chapter['chapter_id'] ?></td>
                            <td><?= htmlspecialchars($chapter['category_title']) ?></td>
                            <td><?= htmlspecialchars($chapter['title']) ?></td>
                            <td><?= htmlspecialchars($chapter['description']) ?></td>
                            <td>Rp<?= number_format($chapter['price'], 2, ',', '.') ?></td>
                            <td><a href="../<?= htmlspecialchars($chapter['file_path']) ?>" download>Unduh</a></td>
                            <td>
                              <form method="POST" style="display:inline;">
                                <input type="hidden" name="chapter_id" value="<?= $chapter['chapter_id'] ?>">
                                <button type="submit" name="action" value="delete" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus kategori ini?')">Hapus</button>
                              </form>
                            </td>
                          </tr>
                        <?php endwhile; ?>
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