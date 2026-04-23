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
            <li><a href="../../public/MainPage.php" class="active">Home</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Programs</a></li>
            <li><a href="#">Admission</a></li>
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
                <a href="../public/MainPage.php" class="btn-outline">Kembali ke Beranda</a>
            </div>
        </div>
        
        <!-- Image Section -->
        <div class="image-section"></div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-grid">
            <div class="footer-col brand-col">
                <h3 class="footer-logo">HCTS</h3>
                <p>Sekolah pelatihan internasional terkemuka untuk karier di bidang perhotelan dan kapal pesiar.</p>
                <div class="social-icons" style="margin-top: 1rem;">
                    <a href="#" style="color: #ccc; margin-right: 15px;"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" style="color: #ccc; margin-right: 15px;"><i class="fab fa-instagram"></i></a>
                    <a href="#" style="color: #ccc; margin-right: 15px;"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" style="color: #ccc;"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
            <div class="footer-col">
                <h4>Aksi Cepat</h4>
                <ul>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Our Programs</a></li>
                    <li><a href="#">Admission Process</a></li>
                    <li><a href="#">Career Opportunities</a></li>
                    <li><a href="#">Student Stories</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Program Kami</h4>
                <ul>
                    <li><a href="#">Hotel Management</a></li>
                    <li><a href="#">Cruise Ship Operations</a></li>
                    <li><a href="#">Culinary Arts</a></li>
                    <li><a href="#">Hospitality Services</a></li>
                    <li><a href="#">Tourism Management</a></li>
                </ul>
            </div>
            <div class="footer-col contact-col">
                <h4>Kontak Kami</h4>
                <ul>
                    <li><a href="#"><i class="fas fa-map-marker-alt" style="margin-right: 10px;"></i> 123 Maritime Avenue,<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Harbor District, HD 12345</a></li>
                    <li><a href="#"><i class="fas fa-phone-alt" style="margin-right: 10px;"></i> +1 (555) 123-4567</a></li>
                    <li><a href="#"><i class="fas fa-envelope" style="margin-right: 10px;"></i> info@hcts.edu</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="footer-copy">&copy; 2025 HCTS International. All rights reserved.</div>
            <div class="footer-legal">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Cookie Policy</a>
            </div>
        </div>
    </footer>
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
