function showMessage(message, isSuccess = true, redirect = false) {
    Swal.fire({
        icon: isSuccess ? 'success' : 'error', // Menentukan ikon berdasarkan tipe pesan
        title: isSuccess ? 'Thank You!' : 'Oops...',
        text: message,
        confirmButtonText: 'OK',
        confirmButtonColor: isSuccess ? '#4CAF50' : '#d33',
    }).then((result) => {
        if (result.isConfirmed && isSuccess && redirect) {
            window.location.href = "login.html"; // Redirect ke halaman login jika berhasil
        }
    });
}
