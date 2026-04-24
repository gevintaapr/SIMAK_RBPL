<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../style/pendaftaran.css">
    <link rel="stylesheet" href="../../style/dashboard_siswa.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-brand">HCTS</div>
        <ul class="nav-menu">
            <li><a href="../../public/index.php" class="active">Home</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Programs</a></li>
        </ul>
        <div class="nav-action">
            <a href="../../public/login/logSiswa.php" class="btn-logout">Login</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero-section">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div class="breadcrumb">Beranda &gt; Pendaftaran</div>
            <h1>Pendaftaran Calon Siswa</h1>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-container">
        <!-- Success Section -->
        <div class="content-section success-container">
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>
            <h2>Pendaftaran Berhasil!</h2>
            <p>Terima kasih telah mendaftar. Data Anda telah kami terima dan sedang dalam proses verifikasi.</p>
            
            <div class="data-box">
                <div class="data-item">
                    <label>NOMOR PENDAFTARAN</label>
                    <div class="data-value">
                        <span><?= htmlspecialchars($_SESSION['kode_akses'] ?? 'REG-XXXX-XXXX') ?></span>
                        <i class="far fa-copy"></i>
                    </div>
                </div>
                <div class="data-item">
                    <label>TOKEN AKSES</label>
                    <div class="data-value">
                        <span><?= htmlspecialchars($_SESSION['token'] ?? 'HCTS-XXXX') ?></span>
                        <i class="far fa-copy"></i>
                    </div>
                </div>
                
                <div class="warning-box">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Harap simpan/screenshot data ini. Anda akan membutuhkannya untuk Login dan mengecek status kelulusan.</span>
                </div>
            </div>
            
            <div class="action-buttons">
                <a href="../../public/login/logCalonSiswa.php" class="btn-blue">Login Sekarang</a>
                <a href="../../public/index.php" class="btn-outline">Kembali ke Beranda</a>
            </div>
        </div>
        
        <!-- Image Section -->
        <div class="image-section"></div>
    </main>

    <div style="height: 50px;"></div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const copyIcons = document.querySelectorAll('.data-value i.fa-copy');
        
        copyIcons.forEach(icon => {
            icon.style.cursor = 'pointer';
            icon.addEventListener('click', function() {
                const textToCopy = this.previousElementSibling.innerText;
                
                navigator.clipboard.writeText(textToCopy).then(() => {
                    // Beri feedback visual
                    const originalColor = this.style.color;
                    this.style.color = '#0056b3';
                    
                    const tooltip = document.createElement('span');
                    tooltip.innerText = 'Tersalin!';
                    tooltip.style.cssText = 'position:absolute; background:#333; color:#fff; padding:2px 8px; border-radius:4px; font-size:12px; margin-left:10px;';
                    this.parentElement.appendChild(tooltip);
                    
                    setTimeout(() => {
                        this.style.color = originalColor;
                        tooltip.remove();
                    }, 1500);
                });
            });
        });
    });
    </script>
</body>
</html>
