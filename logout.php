<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Logout</title>
</head>

<body>
    <script>
        // Ambil URL halaman saat ini
        const currentUrl = document.referrer || window.location.href;

        // SweetAlert konfirmasi logout
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda akan keluar dari akun ini.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, keluar',
            cancelButtonText: 'Tidak, kembali'
        }).then((result) => {
            if (result.isConfirmed) {
                // Lakukan logout jika pengguna memilih "Ya"
                fetch('perform_logout.php')
                    .then(() => {
                        Swal.fire(
                            'Berhasil!',
                            'Anda telah keluar.',
                            'success'
                        ).then(() => {
                            window.location.href = 'index.php'; // Arahkan ke halaman utama
                        });
                    })
                    .catch(() => {
                        Swal.fire(
                            'Gagal!',
                            'Terjadi kesalahan saat logout. Coba lagi.',
                            'error'
                        );
                    });
            } else {
                // Kembali ke halaman awal jika memilih "Tidak"
                window.location.href = currentUrl;
            }
        });
    </script>
</body>

</html>