<?php
$role = $_GET['role'] ?? null;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Calon Siswa - HCTS</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../style/StyleLoginRole.css?v=<?= time() ?>">
</head>
<body>
    <?php if (isset($_GET['error'])): ?>
    <div id="toast-error" class="toast-notification">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        <?= htmlspecialchars($_GET['error']) ?>
    </div>
    <script>
        setTimeout(() => {
            const toast = document.getElementById('toast-error');
            if(toast) {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 500);
            }
        }, 4000); // Masa aktif kurang lebih 4 detik
    </script>
    <?php endif; ?>

    <!-- NAVBAR -->
    <header class="navbar">
        <div class="nav-container">
            <div class="logo">HCTS</div>
            <nav>
                <ul class="nav-links">
                    <li><a href="#">Home</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Programs</a></li>
                    <li><a href="#">Admission</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main class="status-section">
        <div class="status-card">
            <div class="status-icon">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </div>
            
            <h2 class="status-title">Cek Status</h2>
                <p class="status-desc">Masukkan No. Pendaftaran & Token yang Anda dapatkan saat registrasi.</p>

                <div class="status-form">
                <form action="../../app/proses_login.php" method="POST">
                    <div class="form-group">
                        <input type="hidden" name="role" value="2">
                        <label for="no-daftar">Nomor Pendaftaran</label>
                        <input type="text" id="no-daftar" 
                        name= "login_input"
                        class="form-input" placeholder="Masukkan nomor pendaftaran">
                    </div>
                    
                    <div class="form-group">
                        <label for="token">Token Akses</label>
                        <input type="password" 
                        name="password"
                        id="token" class="form-input" placeholder="Masukkan token akses">
                    </div>

                    <button type="submit" class="btn-orange">Cek Status Pendaftaran</button>
                    <a href="../MainLogin.php" class="btn-outline-dark">Ganti Role</a>
                </form>
            </div>
        </div>
    </main>
</body>
</html>