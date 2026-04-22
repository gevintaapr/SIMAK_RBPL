<?php
session_start();
require_once __DIR__ . '/../../config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../../public/login/logSiswa.php?role=1&error=" . urlencode("Akses ditolak. Silakan login menggunakan akun Siswa Anda."));
    exit;
}

$id_user = $_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT * FROM siswa WHERE id_user = $id_user");
$siswa = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../style/dashboard_siswa.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../style/popup_logout.css">
    <style>
        /* Lock Overlay Styles */
        .dashboard-lock-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(8px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .lock-card {
            background: #fff;
            border-radius: 12px;
            max-width: 600px;
            width: 100%;
            padding: 0;
            text-align: left;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            animation: slideUp 0.5s ease-out;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .lock-header {
            background: #003B73;
            color: #EBC372;
            padding: 20px 30px;
            display: flex;
            align-items: center;
            gap: 15px;
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            font-weight: 700;
            border-bottom: 4px solid #EBC372;
        }

        .lock-body {
            padding: 30px;
        }

        .lock-title {
            font-size: 22px;
            color: #0F172A;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .lock-desc {
            color: #475569;
            line-height: 1.7;
            margin-bottom: 25px;
            font-size: 15px;
        }

        .lock-info-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .lock-info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .lock-info-item:last-child { margin-bottom: 0; }

        .lock-info-label { color: #64748B; }
        .lock-info-value { color: #003B73; font-weight: 700; }

        .btn-pay-now {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: #003B73;
            color: white;
            padding: 16px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            width: 100%;
            border: none;
            cursor: pointer;
        }

        .btn-pay-now:hover {
            background: #002D5A;
            transform: translateY(-2px);
        }

        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .nav-disabled {
            pointer-events: none !important;
            filter: blur(2px) grayscale(0.5);
        }
    </style>
</head>
<body>
    <!-- ===== DASHBOARD LOCK OVERLAY ===== -->
    <?php if ($siswa['status_pembayaran'] !== 'lunas_dp'): ?>
    <div class="dashboard-lock-overlay">
        <div class="lock-card">
            <div class="lock-header">
                <i class="fas fa-bullhorn"></i>
                PENGUMUMAN PENTING
            </div>
            <div class="lock-body">
                <?php if ($siswa['status_pembayaran'] === 'pending'): ?>
                    <h2 class="lock-title">Verifikasi Pembayaran Sedang Diproses</h2>
                    <p class="lock-desc">Halo <?= htmlspecialchars(explode(' ', $siswa['nama_lengkap'])[0]) ?>, Bukti pembayaran DP Anda telah kami terima. Saat ini tim keuangan HCTS sedang melakukan validasi. Dashboard akan aktif sepenuhnya segera setelah verifikasi selesai.</p>
                    <div class="lock-info-box">
                        <div class="lock-info-item">
                            <span class="lock-info-label">Status Saat Ini:</span>
                            <span class="lock-info-value" style="color: #D97706;">Siswa Menunggu Konfirmasi</span>
                        </div>
                    </div>
                <?php else: ?>
                    <h2 class="lock-title">Selesaikan Administrasi Anda</h2>
                    <p class="lock-desc">Selamat atas bergabungnya Anda di HCTS (Hotel & Cruise Ship Selection)! Untuk mulai mengakses materi, jadwal, dan fitur akademik, silakan lakukan pembayaran DP terlebih dahulu sesuai ketentuan Lembaga.</p>
                    <div class="lock-info-box">
                        <div class="lock-info-item">
                            <span class="lock-info-label">Jenis Tagihan:</span>
                            <span class="lock-info-value">Down Payment (DP) Pertama</span>
                        </div>
                        <div class="lock-info-item">
                            <span class="lock-info-label">Nominal:</span>
                            <span class="lock-info-value">Rp 5.000.000</span>
                        </div>
                    </div>
                    <a href="pembayaranSiswa.php?view=upload" class="btn-pay-now">
                        <i class="fas fa-wallet"></i> Lakukan Pembayaran DP Sekarang
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Navbar -->
    <nav class="navbar <?= ($siswa['status_pembayaran'] !== 'lunas_dp') ? 'nav-disabled' : '' ?>">
        <div class="nav-brand">HCTS</div>
        <ul class="nav-menu">
            <li><a href="dashboard_siswa.php" class="active">Home</a></li>
            <li><a href="evaluasi.php">Evaluasi</a></li>
            <li><a href="pembayaranSiswa.php">Keuangan</a></li>
            <li><a href="magang_siswa.php">Magang</a></li>
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
            <h1>Selamat datang, <?= htmlspecialchars(explode(' ', $siswa['nama_lengkap'])[0]) ?>!</h1>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-container <?= ($siswa['status_pembayaran'] !== 'lunas_dp') ? 'nav-disabled' : '' ?>">
        <!-- Success Notification -->
        <?php if ($siswa['status_pembayaran'] === 'lunas_dp'): ?>
            <div style="background: #d4edda; color: #155724; padding: 15px 20px; border-radius: 12px; margin-bottom: 25px; display: flex; align-items: center; gap: 15px; border: 1px solid #c3e6cb;">
                <i class="fas fa-check-circle" style="font-size: 20px;"></i>
                <div>
                    <strong>Pembayaran DP Berhasil!</strong> Dashboard Anda kini telah aktif sepenuhnya. Selamat belajar!
                </div>
            </div>
        <?php endif; ?>

        <!-- Status Card -->
        <section class="card status-card">
            <div class="status-left">
                <h2 class="status-title">Status: <span class="badge-success">Siswa Aktif</span></h2>
                <p class="status-desc">Program <?= htmlspecialchars($siswa['program_pembelajaran'] ?? 'Hotel & Cruise Ship') ?> | NIM: <?= htmlspecialchars($siswa['nim_siswa'] ?? '-') ?></p>
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
                        <a href="pembayaranSiswa.php" class="qa-item">
                            <div class="qa-icon"><i class="fas fa-money-bill-wave"></i></div>
                            <span>Pembayaran</span>
                        </a>
                        <a href="evaluasi.php" class="qa-item">
                            <div class="qa-icon"><i class="fas fa-clipboard-list"></i></div>
                            <span>Nilai</span>
                        </a>
                        <a href="magang_siswa.php" class="qa-item">
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