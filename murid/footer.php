    </div><!-- end murid-content-body -->
</main>
</div><!-- end murid-wrapper -->

<script>
    // Format tanggal Bahasa Indonesia
    const days   = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    const months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    document.querySelectorAll('.dynamic-date').forEach(el => {
        const now = new Date();
        el.textContent = `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
    });

    // Animasi angka counter naik
    document.querySelectorAll('.count-up').forEach(el => {
        const target = parseInt(el.getAttribute('data-target'));
        const isFloat = el.getAttribute('data-float') === '1';
        let start = 0;
        const duration = 1200;
        const step = target / (duration / 16);
        const timer = setInterval(() => {
            start += step;
            if (start >= target) {
                start = target;
                clearInterval(timer);
            }
            el.textContent = isFloat ? start.toFixed(1) : Math.floor(start);
        }, 16);
    });

    // Mobile sidebar toggle
    const mobileToggle = document.getElementById('mobileSidebarToggle');
    const sidebar = document.querySelector('.murid-sidebar');
    if (mobileToggle && sidebar) {
        mobileToggle.addEventListener('click', () => sidebar.classList.toggle('open'));
    }
</script>
</body>
</html>
