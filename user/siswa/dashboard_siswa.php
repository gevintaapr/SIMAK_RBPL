<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../style/dashboard_siswa.css">
    <link rel="stylesheet" href="../../style/popup_logout.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-brand">HCTS</div>
        <ul class="nav-menu">
            <li><a href="#" class="active">Home</a></li>
            <li><a href="#">Pages</a></li>
            <li><a href="#">Programs</a></li>
            <li><a href="#">Admission</a></li>
        </ul>
        <div class="nav-action">
            <a href="#" class="nav-bell"><i class="far fa-bell"></i></a>
            <a href="#" onclick="showLogoutPopup(event)" class="btn-logout">Logout</a>
        </div>
    </nav>

    <!-- Hero Section (Welcome) -->
    <header class="hero-section" style="background-image: url('../assets/Hero.png');">
        <div class="hero-overlay"></div>
        
        <div class="hero-content">
            <div class="breadcrumb">Beranda &gt; Dashboard</div>
            <h1>Selamat datang, Alexander!</h1>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-container">
        <!-- Status Card -->
        <section class="card status-card">
            <div class="status-left">
                <h2 class="status-title">Status: <span class="badge-success">Siswa Aktif</span></h2>
                <p class="status-desc">Program Hotel & Cruise Ship - Batch 2025</p>
            </div>
            <div class="status-right">
                <span class="status-label">Kelengkapan Berkas</span>
                <span class="status-value-success">100% Terverifikasi</span>
            </div>
        </section>

        <!-- Main Grid Container -->
        <section class="main-grid">
            <!-- Schedule Card (Left) -->
            <div class="card schedule-card">
                <div class="card-header">
                    <h2>Jadwal Terdekat</h2>
                    <a href="#" class="view-all">Lihat Semua &rarr;</a>
                </div>
                <div class="card-body">
                    <div class="schedule-item">
                        <div class="schedule-icon"><i class="fas fa-book"></i></div>
                        <div class="schedule-details">
                            <h3>English for Hospitality</h3>
                            <p>Senin, 09:00 - 11:00 | Ruang A2</p>
                        </div>
                    </div>
                    <div class="schedule-item">
                        <div class="schedule-icon"><i class="fas fa-utensils"></i></div>
                        <div class="schedule-details">
                            <h3>F&B Service Practice</h3>
                            <p>Selasa, 12:30 - 14:00 | Lab Restaurant</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Access Card (Right) -->
            <div class="card quick-access-card">
                <div class="card-header">
                    <h2>Akses Cepat</h2>
                </div>
                <div class="card-body">
                    <div class="quick-access-grid">
                        <a href="#" class="qa-item">
                            <div class="qa-icon"><i class="fas fa-money-bill-wave"></i></div>
                            <span>Pembayaran</span>
                        </a>
                        <a href="#" class="qa-item">
                            <div class="qa-icon"><i class="fas fa-clipboard-list"></i></div>
                            <span>Nilai</span>
                        </a>
                        <a href="#" class="qa-item">
                            <div class="qa-icon"><i class="fas fa-user-tie"></i></div>
                            <span>Magang</span>
                        </a>
                        <a href="#" class="qa-item">
                            <div class="qa-icon"><i class="fas fa-plane"></i></div>
                            <span>Taiwan</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Announcements Card -->
        <section class="card announcement-card">
            <h2 class="announcement-title">Papan Pengumuman</h2>
            <div class="card-body announcement-list">
                <div class="announcement-item">
                    <div class="announcement-main">
                        <h3>Pembayaran SPP Bulan November telah berhasil diverifikasi.</h3>
                        <p>Batas akhir pengumpulan laporan magang tahap 1 adalah tanggal 30 Oktober.</p>
                    </div>
                    <div class="announcement-date">2 Jam yang lalu</div>
                </div>
                <div class="announcement-item">
                    <div class="announcement-main">
                        <h3>Pembayaran Terverifikasi</h3>
                        <p>Pembayaran SPP Bulan November telah berhasil diverifikasi.</p>
                    </div>
                    <div class="announcement-date">Kemarin</div>
                </div>
                <div class="announcement-item">
                    <div class="announcement-main">
                        <h3>Kegiatan Table Manner Bulan Desember</h3>
                        <p>Batas akhir pengumpulan laporan magang tahap 1 adalah tanggal 30 Oktober.</p>
                    </div>
                    <div class="announcement-date">Kemarin</div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-col brand-col">
                <h3 class="footer-brand">HCTS</h3>
                <p>Sekolah pelatihan internasional terkemuka untuk karier di bidang perhotelan dan kapal pesiar.</p>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
            <div class="footer-col">
                <h4 class="footer-title">Aksi Cepat</h4>
                <ul>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Our Programs</a></li>
                    <li><a href="#">Admission Process</a></li>
                    <li><a href="#">Career Opportunities</a></li>
                    <li><a href="#">Student Stories</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4 class="footer-title">Program Kami</h4>
                <ul>
                    <li><a href="#">Hotel Management</a></li>
                    <li><a href="#">Cruise Ship Operations</a></li>
                    <li><a href="#">Culinary Arts</a></li>
                    <li><a href="#">Hospitality Services</a></li>
                    <li><a href="#">Tourism Management</a></li>
                </ul>
            </div>
            <div class="footer-col contact-col">
                <h4 class="footer-title">Kontak Kami</h4>
                <ul>
                    <li><i class="fas fa-map-marker-alt"></i> <span>123 Maritime Avenue,<br>Harbor District, HD 12345</span></li>
                    <li><i class="fas fa-phone-alt"></i> <span>+1 (555) 123-4567</span></li>
                    <li><i class="fas fa-envelope"></i> <span>info@hcts.edu</span></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="footer-copy">&copy; 2025 HCTS International. All rights reserved.</div>
            <div class="footer-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Cookie Policy</a>
            </div>
        </div>
    </footer>

    <!-- Logout Popup -->
    <div id="logoutPopup" class="popup-overlay" style="display: none;">
        <div class="popup-wrapper">
            <div class="popup-content">
                <button class="btn-close-popup" onclick="closeLogoutPopup()">&times;</button>
                <div class="popup-body">
                    <h3>Apakah Anda Yakin Ingin Keluar<br>dari Sistem?</h3>
                    <hr class="popup-divider">
                </div>
                <div class="popup-footer">
                    <a href="../../app/logout.php" class="btn-yakin">Yakin</a>
                    <button class="btn-tidak" onclick="closeLogoutPopup()">Tidak</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showLogoutPopup(e) {
            if(e) e.preventDefault();
            document.getElementById('logoutPopup').style.display = 'flex';
        }
        function closeLogoutPopup() {
            document.getElementById('logoutPopup').style.display = 'none';
        }
    </script>
</body>
</html>