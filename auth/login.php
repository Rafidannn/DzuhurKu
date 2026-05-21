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

            <!-- Custom wavy organic shapes and grid lines in background -->
            <div class="wave-bg"></div>
            <div class="grid-overlay"></div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="login-right">
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

