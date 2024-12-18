<?php
require 'db.php';

// Ambil parameter search dan category dari GET
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

// Query dinamis untuk pencarian buku
$sql = "SELECT id AS book_id, title, category, description, image_path 
        FROM book_details 
        WHERE 1=1";

$params = [];
$types = "";

// Filter berdasarkan kategori
if (!empty($category)) {
    $sql .= " AND category = ?";
    $params[] = $category;
    $types .= "s";
}

// Filter berdasarkan judul buku
if (!empty($search)) {
    $sql .= " AND title LIKE ?";
    $params[] = "%$search%";
    $types .= "s";
}

$sql .= " ORDER BY title ASC";

// Eksekusi query
$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Tampilkan hasil pencarian
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
?>
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="row g-0">
                    <div class="col-md-4">
                        <!-- Gambar Buku -->
                        <?php if (!empty($row['image_path'])): ?>
                            <img src="<?= htmlspecialchars($row['image_path']); ?>" class="img-fluid rounded-start" alt="Gambar Buku">
                        <?php else: ?>
                            <img src="images/no-image.png" class="img-fluid rounded-start" alt="No Image">
                        <?php endif; ?>
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($row['title']); ?></h5>
                            <p class="card-text"><strong>Kategori:</strong> <?= htmlspecialchars($row['category']); ?></p>
                            <p class="card-text"><strong>Deskripsi:</strong> <?= htmlspecialchars($row['description']); ?></p>
                            <a href="#" class="btn btn-primary btn-sm">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
} else {
    echo "<p class='text-center'>Tidak ada buku yang ditemukan.</p>";
}

$conn->close();
?>