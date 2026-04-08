<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Calon Siswa</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../style/dashboard_calon_siswa.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-brand">HCTS</div>
        <ul class="nav-menu">
            <li><a href="#">Home</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Programs</a></li>
            <li><a href="#">Admission</a></li>
        </ul>
        <a href="../../app/logout.php" class="btn-login">Logout</a>
    </nav>

    <!-- Hero Section -->
    <header class="hero-section">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div class="breadcrumb">Beranda &gt; Pendaftaran</div>
            <h1>Dashboard Calon Siswa</h1>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-container">
        <!-- Header Section -->
        <div class="dashboard-header">
            <div class="welcome-text">
                <h2>Selamat Datang, Alex</h2>
                <p>No. Reg: REG-2025-3921</p>
            </div>
            <div class="status-badge">Calon Siswa</div>
        </div>

        <!-- Warning Info -->
        <div class="alert-warning">
            <i class="fas fa-info-circle"></i>
            <div class="alert-content">
                <h4>Verifikasi Dokumen Sedang Berlangsung</h4>
                <p>Admin kami sedang memeriksa kelengkapan dokumen Anda. Proses ini biasanya memakan waktu 1-2 hari kerja.<br>Harap cek status secara berkala.</p>
            </div>
        </div>

        <!-- Timeline -->
        <div class="timeline">
            <div class="timeline-step completed">
                <div class="step-circle"><i class="fas fa-check"></i></div>
                <div class="step-label">Pendaftaran</div>
            </div>
            <div class="timeline-step completed">
                <div class="step-circle"><i class="fas fa-check"></i></div>
                <div class="step-label">Upload Dokumen</div>
            </div>
            <div class="timeline-step active">
                <div class="step-circle">3</div>
                <div class="step-label">Verifikasi Admin</div>
            </div>
            <div class="timeline-step">
                <div class="step-circle">4</div>
                <div class="step-label">Wawancara</div>
            </div>
            <div class="timeline-step">
                <div class="step-circle">5</div>
                <div class="step-label">Pengumuman</div>
            </div>
            <div class="timeline-step">
                <div class="step-circle">6</div>
                <div class="step-label">Mulai Belajar</div>
            </div>
        </div>

        <!-- Grid Cards -->
        <div class="dashboard-grid">
            <div class="content-card">
                <h3>Status Dokumen</h3>
                <div class="doc-status">
                    <div class="icon-folder">
                        <i class="fas fa-folder"></i>
                    </div>
                    <h4>Berkas Pendaftaran Lengkap</h4>
                    <p>Mencakup: KTP, Ijazah, Pas Foto, Bukti Pembayaran dan Surat Pernyataan</p>
                    <div class="status-pill">Menunggu Verifikasi Admin</div>
                </div>
            </div>
            
            <div class="content-card">
                <h3>Ringkasan Data</h3>
                <table class="data-summary">
                    <tr>
                        <td>Nama Lengkap</td>
                        <td>Alex Wibowo</td>
                    </tr>
                    <tr>
                        <td>Program Pilihan</td>
                        <td>House Keeping</td>
                    </tr>
                    <tr>
                        <td>Posisi</td>
                        <td>Hotel</td>
                    </tr>
                    <tr>
                        <td>Tanggal Daftar</td>
                        <td>24 Okt 2025</td>
                    </tr>
                    <tr>
                        <td>Status Akhir</td>
                        <td class="highlight-text">Proses Verifikasi</td>
                    </tr>
                </table>
            </div>
        </div>
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
</body>
</html>
