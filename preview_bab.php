<?php
session_start();
require 'db.php';

// Cek jika pengguna belum login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Ambil chapter_id dari query string
if (!isset($_GET['chapter_id'])) {
    die("Chapter ID tidak ditemukan.");
}

$chapter_id = (int) $_GET['chapter_id'];

// Query untuk mendapatkan preview_file_path dari bab
$query = "SELECT preview_file_path FROM chapters WHERE chapter_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $chapter_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $preview_file_path = $row['preview_file_path'];

    // Pastikan file PDF ada di server
    if (!empty($preview_file_path) && file_exists($preview_file_path)) {
        $file_url = "http://localhost/book-chapter/" . $preview_file_path; // Pastikan URL sesuai struktur direktori
    } else {
        die("File tidak ditemukan atau path tidak valid.");
    }
} else {
    die("Bab buku tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Bab Buku</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        #pdfViewer {
            width: 100%;
            height: 100vh;
            overflow: auto;
            background: #ddd;
        }

        canvas {
            display: block;
            margin: 0 auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border: 1px solid #ccc;
        }
    </style>
</head>

<body>
    <div id="pdfViewer"></div>

    <script>
        const url = "<?php echo $file_url; ?>";

        // Atur konfigurasi PDF.js
        const pdfViewer = document.getElementById('pdfViewer');
        pdfjsLib.GlobalWorkerOptions.workerSrc = "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js";

        // Memuat dokumen PDF
        const loadingTask = pdfjsLib.getDocument(url);
        loadingTask.promise.then(function(pdf) {
            console.log('PDF loaded');

            // Render setiap halaman PDF
            for (let pageNumber = 1; pageNumber <= pdf.numPages; pageNumber++) {
                pdf.getPage(pageNumber).then(function(page) {
                    const viewport = page.getViewport({
                        scale: 1.5
                    });

                    // Membuat elemen canvas untuk halaman ini
                    const canvas = document.createElement('canvas');
                    pdfViewer.appendChild(canvas);

                    const context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    const renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };

                    // Render halaman ke canvas
                    page.render(renderContext);
                });
            }
        }, function(reason) {
            // Penanganan error jika gagal memuat file PDF
            console.error(reason);
            pdfViewer.innerHTML = "<p>Gagal memuat file PDF.</p>";
        });
    </script>
</body>

</html>