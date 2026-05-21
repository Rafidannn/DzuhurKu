<?php
// Tentukan menu aktif berdasarkan nama file saat ini
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-logo">
            <!-- Modern Stylized Leaf/Drop SVG Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                <path d="M12 6a15.3 15.3 0 0 1 1.5 6 15.3 15.3 0 0 1-1.5 6"></path>
            </svg>
        </div>
        <div class="sidebar-brand-text">
            <span class="sidebar-title">Dzuhurku</span>
            <span class="sidebar-subtitle">PRAYER COMPANION</span>
        </div>
    </div>

    <nav class="sidebar-menu">
        <a href="index.php" class="menu-item <?= $current_page == 'index.php' ? 'active' : ''; ?>">
            <span class="menu-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7"></rect>
                    <rect x="14" y="3" width="7" height="7"></rect>
                    <rect x="14" y="14" width="7" height="7"></rect>
                    <rect x="3" y="14" width="7" height="7"></rect>
                </svg>
            </span>
            <span class="menu-text">Dashboard</span>
        </a>
        
        <a href="presensi.php" class="menu-item <?= $current_page == 'presensi.php' ? 'active' : ''; ?>">
            <span class="menu-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
            </span>
            <span class="menu-text">Input Presensi</span>
        </a>
        
        <a href="rekap.php" class="menu-item <?= $current_page == 'rekap.php' ? 'active' : ''; ?>">
            <span class="menu-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="20" x2="18" y2="10"></line>
                    <line x1="12" y1="20" x2="12" y2="4"></line>
                    <line x1="6" y1="20" x2="6" y2="14"></line>
                </svg>
            </span>
            <span class="menu-text">Rekap Laporan</span>
        </a>

        <div class="menu-divider">MANAJEMEN DATA</div>
        
        <a href="data_murid.php" class="menu-item <?= $current_page == 'data_murid.php' ? 'active' : ''; ?>">
            <span class="menu-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
            </span>
            <span class="menu-text">Data Murid</span>
        </a>
        
        <a href="data_kelas.php" class="menu-item <?= $current_page == 'data_kelas.php' ? 'active' : ''; ?>">
            <span class="menu-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                    <path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"></path>
                </svg>
            </span>
            <span class="menu-text">Data Kelas</span>
        </a>
        
        <a href="data_guru.php" class="menu-item <?= $current_page == 'data_guru.php' ? 'active' : ''; ?>">
            <span class="menu-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
            </span>
            <span class="menu-text">Data Guru</span>
        </a>
    </nav>
    
    <div class="sidebar-footer">
        <a href="../auth/logout.php" class="btn-logout-sidebar">
            <span class="menu-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
            </span>
            <span class="menu-text">Keluar</span>
        </a>
    </div>
</aside>
<main class="main-content">
    <header class="topbar">
        <div class="topbar-left">
            <button class="mobile-toggle" id="mobileToggle">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
            <div class="topbar-greeting">
                <h1 class="topbar-title">Assalamu Alaikum, <?= htmlspecialchars($_SESSION['nama']); ?>! 👋</h1>
                <p class="topbar-subtitle">Role: <span class="badge-role"><?= strtoupper($_SESSION['role']); ?></span></p>
            </div>
        </div>
        <div class="topbar-right">
            <div class="topbar-date">
                <span class="date-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                </span>
                <span class="date-text" id="currentDate"><?= date('d F Y'); ?></span>
            </div>
        </div>
    </header>
    <div class="content-body">
