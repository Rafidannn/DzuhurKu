<?php
session_start();
// Jika sudah login, redirect ke halaman dashboard masing-masing
if (isset($_SESSION['login'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: ../admin/index.php");
    } else {
        header("Location: ../murid/index.php"); 
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Dzuhurku</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* ============================================
           KEYFRAME ANIMATIONS
           ============================================ */
        @keyframes spinSlow      { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        @keyframes spinReverse   { from { transform: rotate(0deg); } to { transform: rotate(-360deg); } }
        @keyframes floatUp       { 0%,100% { transform: translateY(0px);    } 50% { transform: translateY(-16px); } }
        @keyframes floatUpTilt   { 0%,100% { transform: translateY(0px) rotate(0deg);  } 50% { transform: translateY(-12px) rotate(6deg);  } }
        @keyframes floatDownTilt { 0%,100% { transform: translateY(0px) rotate(0deg);  } 50% { transform: translateY(-10px) rotate(-8deg); } }
        @keyframes swingLamp     { 0%,100% { transform: rotate(-6deg) translateX(0);   } 50% { transform: rotate(6deg)  translateX(0); } }
        @keyframes swingLampAlt  { 0%,100% { transform: rotate(5deg)  translateX(0);   } 50% { transform: rotate(-5deg) translateX(0); } }
        @keyframes glowPulse     { 0%,100% { opacity: 0.7; filter: drop-shadow(0 0 8px rgba(255,200,80,0.5));  }
                                   50%      { opacity: 1.0; filter: drop-shadow(0 0 22px rgba(255,200,80,0.9)); } }
        @keyframes pulseScale    { 0%,100% { transform: scale(1); opacity: 0.15; }  50% { transform: scale(1.08); opacity: 0.22; } }

        /* ============================================
           BASE DECORATION CLASS
           ============================================ */
        .orn, .lamp { position: absolute; pointer-events: none; user-select: none; }

        /* ============================================
           ORNAMENT MANDALA — PANEL KIRI
           ============================================ */

        /* 1. Besar berputar — pojok kanan atas */
        .orn-1 {
            top: -100px; right: -100px;
            width: 360px; opacity: 0.38;
            animation: spinSlow 50s linear infinite;
            filter: brightness(1.2) sepia(0.6) saturate(3) hue-rotate(5deg);
            z-index: 0;
        }
        /* 2. Sedang berputar balik — pojok kiri bawah */
        .orn-2 {
            bottom: -80px; left: -80px;
            width: 280px; opacity: 0.35;
            animation: spinReverse 35s linear infinite;
            filter: brightness(1.2) sepia(0.6) saturate(3) hue-rotate(10deg);
            z-index: 0;
        }
        /* 3. Kecil melayang — pojok kiri atas */
        .orn-3 {
            top: 20px; left: 16px;
            width: 100px; opacity: 0.45;
            animation: floatUpTilt 7s ease-in-out infinite;
            filter: brightness(1.1) sepia(0.5) saturate(3) hue-rotate(5deg);
            z-index: 0;
        }
        /* 4. Sedang melayang — pojok kanan bawah */
        .orn-4 {
            bottom: 20px; right: 16px;
            width: 130px; opacity: 0.40;
            animation: floatDownTilt 9s ease-in-out infinite 1.5s;
            filter: brightness(1.1) sepia(0.5) saturate(3) hue-rotate(5deg);
            z-index: 0;
        }
        /* 5. Tengah (warm golden glow) */
        .orn-5 {
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 220px; opacity: 0.12;
            animation: pulseScale 12s ease-in-out infinite;
            filter: brightness(1.5) sepia(0.8) saturate(4);
            z-index: 0;
        }
        /* 6. Kecil — tengah kiri */
        .orn-6 {
            top: 42%; left: -30px;
            width: 85px; opacity: 0.42;
            animation: floatUp 6s ease-in-out infinite 2s;
            filter: brightness(1.1) sepia(0.5) saturate(3) hue-rotate(5deg);
            z-index: 0;
        }
        /* 7. Kecil — tengah kanan */
        .orn-7 {
            top: 38%; right: -25px;
            width: 80px; opacity: 0.38;
            animation: floatUpTilt 8s ease-in-out infinite 0.5s;
            filter: brightness(1.1) sepia(0.5) saturate(3) hue-rotate(5deg);
            z-index: 0;
        }

        /* ============================================
           ORNAMENT — PANEL KANAN (form) - halus
           ============================================ */
        .orn-form-tr {
            top: -55px; right: -55px;
            width: 180px; opacity: 0.12;
            animation: spinSlow 60s linear infinite;
            filter: sepia(0.8) saturate(3) hue-rotate(5deg) brightness(1.2);
        }
        .orn-form-bl {
            bottom: -55px; left: -55px;
            width: 150px; opacity: 0.10;
            animation: spinReverse 50s linear infinite;
            filter: sepia(0.8) saturate(3) hue-rotate(5deg) brightness(1.2);
        }

        /* ============================================
           LENTERA ISLAMI — PANEL KANAN (menggantung)
           ============================================ */

        /* Lentera utama — tengah atas panel kanan */
        .lamp-main {
            top: -18px; left: 50%;
            transform-origin: top center;
            margin-left: -50px;
            width: 100px;
            animation: swingLamp 5s ease-in-out infinite;
            filter: drop-shadow(0 10px 24px rgba(255,180,50,0.6));
            z-index: 10;
        }
        .lamp-main img { width: 100%; animation: glowPulse 3s ease-in-out infinite; }

        /* Lentera kiri — atas kiri panel kanan */
        .lamp-left {
            top: -10px; left: 8%;
            transform-origin: top center;
            width: 65px;
            animation: swingLampAlt 6.5s ease-in-out infinite 1s;
            filter: drop-shadow(0 6px 14px rgba(255,180,50,0.45));
            z-index: 10;
        }
        .lamp-left img { width: 100%; animation: glowPulse 4s ease-in-out infinite 0.8s; }

        /* Lentera kanan — atas kanan panel kanan */
        .lamp-right {
            top: -14px; right: 8%;
            transform-origin: top center;
            width: 75px;
            animation: swingLamp 7s ease-in-out infinite 0.5s;
            filter: drop-shadow(0 6px 16px rgba(255,180,50,0.5));
            z-index: 10;
        }
        .lamp-right img { width: 100%; animation: glowPulse 3.5s ease-in-out infinite 1.2s; }
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

            <!-- ====== ORNAMEN MANDALA EMAS ====== -->
            <img src="../assets/images/ornament.png" alt="" class="orn orn-1">
            <img src="../assets/images/ornament.png" alt="" class="orn orn-2">
            <img src="../assets/images/ornament.png" alt="" class="orn orn-3">
            <img src="../assets/images/ornament.png" alt="" class="orn orn-4">
            <img src="../assets/images/ornament.png" alt="" class="orn orn-5">
            <img src="../assets/images/ornament.png" alt="" class="orn orn-6">
            <img src="../assets/images/ornament.png" alt="" class="orn orn-7">

            <!-- Custom wavy organic shapes and grid lines in background -->
            <div class="wave-bg"></div>
            <div class="grid-overlay"></div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="login-right" style="position:relative; overflow:hidden;">
            <!-- Ornamen halus pojok panel form -->
            <img src="../assets/images/ornament.png" alt="" class="orn orn-form-tr">
            <img src="../assets/images/ornament.png" alt="" class="orn orn-form-bl">
            <!-- Lentera islami bergantung di atas form -->
            <div class="lamp lamp-left"><img src="../assets/images/lampu.png" alt=""></div>
            <div class="lamp lamp-main"><img src="../assets/images/lampu.png" alt=""></div>
            <div class="lamp lamp-right"><img src="../assets/images/lampu.png" alt=""></div>
            <div class="login-card-new">
                <div class="badge-pill">
                    <span>ASSALAMU ALAIKUM</span>
                    <span class="badge-dot"></span>
                </div>
                
                <h2 class="form-title">Sign in to <span class="highlight">Dzuhurku</span></h2>
                <p class="form-subtitle">Continue your daily spiritual journey.</p>
                
                <form action="proses_login.php" method="POST">
                    <div class="form-group-new">
                        <div class="input-icon-wrapper">
                            <span class="input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </span>
                            <input type="text" id="username" name="username" class="form-input-new" placeholder="Username or NISN" required>
                        </div>
                    </div>
                    
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
                                <!-- Eye Icon Open -->
                                <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <!-- Eye Icon Closed -->
                                <svg id="eyeOffIcon" style="display: none;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                </svg>
                            </span>
                        </div>
                    </div>
                    
                    <div class="form-options">
                        <label class="remember-me">
                            <input type="checkbox" name="remember">
                            <span class="checkmark"></span>
                            <span class="remember-text">Remember me</span>
                        </label>
                        <a href="#" class="forgot-link">Forgot password?</a>
                    </div>
                    
                    <button type="submit" name="login" class="btn-signin">SIGN IN</button>
                </form>
                
                <div class="auth-footer-link">
                    Don't have an account? <a href="register.php" class="signup-link">Sign Up</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const eyeIcon = document.querySelector('#eyeIcon');
        const eyeOffIcon = document.querySelector('#eyeOffIcon');

        togglePassword.addEventListener('click', function () {
            // Ubah tipe password
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            // Toggle ikon
            if (type === 'password') {
                eyeIcon.style.display = 'block';
                eyeOffIcon.style.display = 'none';
            } else {
                eyeIcon.style.display = 'none';
                eyeOffIcon.style.display = 'block';
            }
        });
    </script>
</body>
</html>

