    </div> <!-- end content-body -->
</main> <!-- end main-content -->
</div> <!-- end admin-wrapper -->

<script>
    // Script untuk menu toggle di mobile
    const mobileToggle = document.getElementById('mobileToggle');
    const sidebar = document.querySelector('.sidebar');
    if (mobileToggle && sidebar) {
        mobileToggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
        });
    }

    // Format Hari & Tanggal dalam Bahasa Indonesia secara dinamis
    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    
    const dateElement = document.getElementById('currentDate');
    if (dateElement) {
        const now = new Date();
        const dayName = days[now.getDay()];
        const day = now.getDate();
        const monthName = months[now.getMonth()];
        const year = now.getFullYear();
        dateElement.textContent = `${dayName}, ${day} ${monthName} ${year}`;
    }
</script>
</body>
</html>
