// Menangani klik pada elemen proyek
document.addEventListener("DOMContentLoaded", function() {
    // Seleksi semua elemen dengan class 'project-item'
    const projectItems = document.querySelectorAll('.project-item');
    
    projectItems.forEach(item => {
        item.addEventListener('click', function(event) {
            event.preventDefault(); // Mencegah aksi default link
            const projectId = item.getAttribute('data-id');
            // Mengarahkan ke halaman review.html dengan parameter ID
            window.location.href = `review.html?id=${projectId}`;
        });
    });
});
