<?php
session_start();
// Jika sudah login, redirect ke halaman dashboard masing-masing
if (isset($_SESSION['login'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: ../admin/index.php");
    } else {
        // Jika nanti ada dashboard murid
        header("Location: ../admin/index.php"); 
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DzuhurKu</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <div class="login-container">
        <div class="login-card">
            <!-- Bisa panggil logo di folder images -->
            <img src="../assets/images/logosmk.png" alt="Logo SMKN 24" class="login-logo" onerror="this.style.display='none'">
            
            <h1 class="app-title">DzuhurKu</h1>
            <p class="app-subtitle">Monitoring Salat Zuhur - SMKN 24 Jakarta</p>

            <div class="quote-box">
                <p class="quote-text">
                    "Sesungguhnya salat itu adalah kewajiban yang ditentukan waktunya atas orang-orang yang beriman." <br>— Q.S. An-Nisa: 103
                </p>
            </div>

            <div class="bismillah">بِسْمِ اللَّهِ</div>

            <form action="proses_login.php" method="POST">
                
                <div class="form-group">
                    <label for="username" class="form-label">Username / NISN</label>
                    <input type="text" id="username" name="username" class="form-input" required>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div style="position: relative;">
                        <input type="password" id="password" name="password" class="form-input" style="padding-right: 50px;" required>
                        <span id="togglePassword" style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); cursor: pointer; color: var(--text-muted); display: flex; align-items: center; justify-content: center;">
                            <!-- Icon Mata Terbuka -->
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <!-- Icon Mata Tercoret (Sembunyi) -->
                            <svg id="eyeOffIcon" style="display: none;" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                            </svg>
                        </span>
                    </div>
                </div>

                <a href="#" class="forgot-pwd">Lupa password? (Khusus Siswa)</a>

                <button type="submit" name="login" class="btn-primary">Masuk</button>
            </form>

                        <div class="card-footer">
                <span>Sistem aman & terenkripsi</span>
                <span class="dot"></span>
                <span>SMKN 24 Jakarta</span>
            </div>
        </div>

        <div class="main-footer">
            DzuhurKu v1.0 - SMKN 24 Jakarta &copy; 2026
        </div>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const eyeIcon = document.querySelector('#eyeIcon');
        const eyeOffIcon = document.querySelector('#eyeOffIcon');

        togglePassword.addEventListener('click', function () {
            // Ubah tipe dari password ke teks atau sebaliknya
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            // Tampilkan icon yang sesuai
            if (type === 'password') {
                eyeIcon.style.display = 'block';
                eyeOffIcon.style.display = 'none';
                this.style.color = 'var(--text-muted)';
            } else {
                eyeIcon.style.display = 'none';
                eyeOffIcon.style.display = 'block';
                this.style.color = 'var(--primary)';
            }
        });
    </script>
</body>
</html>
