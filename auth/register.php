<?php
session_start();
include '../config/koneksi.php';

// Jika sudah login, redirect ke halaman dashboard masing-masing
if (isset($_SESSION['login'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: ../admin/index.php");
    } else {
        header("Location: ../murid/index.php"); 
    }
    exit();
}

// Ambil data kelas untuk diisi ke dropdown
$query_kelas = mysqli_query($koneksi, "SELECT * FROM kelas ORDER BY nama_kelas ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Dzuhurku</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- Tom Select -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <style>
        @keyframes floatOrn { 0%, 100% { transform: translateY(0) rotate(0deg); } 50% { transform: translateY(-14px) rotate(8deg); } }
        @keyframes floatOrnReverse { 0%, 100% { transform: translateY(0) rotate(0deg); } 50% { transform: translateY(-10px) rotate(-10deg); } }
        @keyframes spinSlow { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        @keyframes pulseOrn { 0%, 100% { opacity: 0.18; transform: scale(1) rotate(45deg); } 50% { opacity: 0.28; transform: scale(1.05) rotate(45deg); } }
        .orn { position: absolute; pointer-events: none; user-select: none; }
        .orn-tr { top: -80px; right: -80px; width: 320px; opacity: 0.13; animation: spinSlow 40s linear infinite; filter: brightness(2) sepia(1) saturate(3) hue-rotate(5deg); }
        .orn-bl { bottom: -60px; left: -60px; width: 240px; opacity: 0.16; animation: floatOrn 8s ease-in-out infinite; filter: brightness(2) sepia(1) saturate(3) hue-rotate(5deg); }
        .orn-center { top: 50%; left: 50%; transform: translate(-50%, -50%); width: 160px; opacity: 0.07; animation: floatOrnReverse 10s ease-in-out infinite; filter: brightness(3) saturate(0) invert(1); }
        .orn-tl { top: 30px; left: 20px; width: 90px; opacity: 0.18; animation: floatOrn 6s ease-in-out infinite 1s; filter: brightness(2) sepia(1) saturate(3) hue-rotate(5deg); }
        .orn-form { bottom: -30px; right: -30px; width: 120px; opacity: 0.08; animation: pulseOrn 5s ease-in-out infinite; filter: sepia(1) saturate(4) hue-rotate(350deg); }
    </style>
</head>
<body>

    <div class="login-wrapper">
        <!-- Left Side: Organic Wavy Green Panel -->
        <div class="login-left">
            <div class="brand-container">
                <div class="brand-logo">
                    <!-- Modern Stylized Leaf/Drop SVG Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                        <path d="M12 6a15.3 15.3 0 0 1 1.5 6 15.3 15.3 0 0 1-1.5 6"></path>
                    </svg>
                </div>
                <div class="brand-text">
                    <span class="brand-title">Dzuhurku</span>
                    <span class="brand-subtitle">PRAYER COMPANION</span>
                </div>
            </div>
            
            <!-- Calligraphy Watermarks -->
            <div class="watermark watermark-1">دزوهوركو</div>
            <div class="watermark watermark-2">ذكر الله</div>
            <div class="watermark-decor-1"></div>
            <div class="watermark-decor-2"></div>
            <div class="watermark-bismillah">بِسْمِ اللَّهِ</div>

            <!-- Ornament Mandala Gold -->
            <img src="../assets/images/ornament.png" alt="ornament" class="orn orn-tr">
            <img src="../assets/images/ornament.png" alt="ornament" class="orn orn-bl">
            <img src="../assets/images/ornament.png" alt="ornament" class="orn orn-center">
            <img src="../assets/images/ornament.png" alt="ornament" class="orn orn-tl">

            <!-- Custom wavy organic shapes and grid lines in background -->
            <div class="wave-bg"></div>
            <div class="grid-overlay"></div>
        </div>

        <!-- Right Side: Register Form -->
        <div class="login-right" style="position:relative; overflow:hidden;">
            <img src="../assets/images/ornament.png" alt="ornament" class="orn orn-form">
            <div class="login-card-new" style="margin-top: 20px; margin-bottom: 20px;">
                <div class="badge-pill">
                    <span>DAFTAR AKUN</span>
                    <span class="badge-dot"></span>
                </div>
                
                <h2 class="form-title">Create Account <span class="highlight">Dzuhurku</span></h2>
                <p class="form-subtitle">Register to start tracking your daily prayers.</p>
                
                <form action="proses_register.php" method="POST" id="registerForm">
                    <!-- Input Nama Lengkap -->
                    <div class="form-group-new">
                        <div class="input-icon-wrapper">
                            <span class="input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </span>
                            <input type="text" id="nama" name="nama" class="form-input-new" placeholder="Full Name" required>
                        </div>
                    </div>

                    <!-- Input NISN -->
                    <div class="form-group-new">
                        <div class="input-icon-wrapper">
                            <span class="input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="16" rx="2" ry="2"></rect>
                                    <line x1="7" y1="8" x2="17" y2="8"></line>
                                    <line x1="7" y1="12" x2="17" y2="12"></line>
                                    <line x1="7" y1="16" x2="12" y2="16"></line>
                                </svg>
                            </span>
                            <input type="text" id="nisn" name="nisn" class="form-input-new" placeholder="NISN" maxlength="20" required>
                        </div>
                    </div>

                    <!-- Pilihan Kelas -->
                    <div class="form-group-new">
                        <div class="input-icon-wrapper">
                            <span class="input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                                    <path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"></path>
                                </svg>
                            </span>
                            <select id="id_kelas" name="id_kelas" class="form-input-new form-select-new" required>
                                <option value="" disabled selected hidden>Select your Class</option>
                                <?php while ($row = mysqli_fetch_assoc($query_kelas)): ?>
                                    <option value="<?= $row['id_kelas']; ?>"><?= htmlspecialchars($row['nama_kelas']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Input Password -->
                    <div class="form-group-new">
                        <div class="input-icon-wrapper">
                            <span class="input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                            </span>
                            <input type="password" id="password" name="password" class="form-input-new" placeholder="Password" required>
                            <span id="togglePassword" class="password-toggle">
                                <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <svg id="eyeOffIcon" style="display: none;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                </svg>
                            </span>
                        </div>
                    </div>

                    <!-- Input Konfirmasi Password -->
                    <div class="form-group-new">
                        <div class="input-icon-wrapper">
                            <span class="input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                            </span>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-input-new" placeholder="Confirm Password" required>
                            <span id="toggleConfirmPassword" class="password-toggle">
                                <svg id="eyeIconConfirm" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <svg id="eyeOffIconConfirm" style="display: none;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                </svg>
                            </span>
                        </div>
                    </div>
                    
                    <button type="submit" name="register" class="btn-signin">REGISTER</button>
                </form>

                <div class="auth-footer-link">
                    Already have an account? <a href="login.php" class="signup-link">Sign In</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle Password
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const eyeIcon = document.querySelector('#eyeIcon');
        const eyeOffIcon = document.querySelector('#eyeOffIcon');

        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            if (type === 'password') {
                eyeIcon.style.display = 'block';
                eyeOffIcon.style.display = 'none';
            } else {
                eyeIcon.style.display = 'none';
                eyeOffIcon.style.display = 'block';
            }
        });

        // Toggle Confirm Password
        const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
        const confirmPassword = document.querySelector('#confirm_password');
        const eyeIconConfirm = document.querySelector('#eyeIconConfirm');
        const eyeOffIconConfirm = document.querySelector('#eyeOffIconConfirm');

        toggleConfirmPassword.addEventListener('click', function () {
            const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPassword.setAttribute('type', type);
            if (type === 'password') {
                eyeIconConfirm.style.display = 'block';
                eyeOffIconConfirm.style.display = 'none';
            } else {
                eyeIconConfirm.style.display = 'none';
                eyeOffIconConfirm.style.display = 'block';
            }
        });

        // Client-side Password Match Verification
        const form = document.querySelector('#registerForm');
        form.addEventListener('submit', function (e) {
            if (password.value !== confirmPassword.value) {
                e.preventDefault();
                alert('Password and Confirm Password do not match!');
            }
        });
    </script>
</body>
</html>
