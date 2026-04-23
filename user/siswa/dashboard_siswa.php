<?php
session_start();
require_once __DIR__ . '/../../config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../../public/login/logSiswa.php?role=1&error=" . urlencode("Akses ditolak. Silakan login menggunakan akun Siswa Anda."));
    exit;
}

$id_user = $_SESSION['user_id'];
$query = mysqli_query($conn, "
    SELECT s.*, pr.nama_program 
    FROM siswa s 
    LEFT JOIN program pr ON s.id_program = pr.id_program 
    WHERE s.id_user = $id_user
");
$siswa = mysqli_fetch_assoc($query);
$id_siswa = $siswa['id_siswa'];

// Cek Sertifikat
$query_cert = mysqli_query($conn, "SELECT no_sertifikat FROM magang WHERE id_siswa = $id_siswa AND no_sertifikat IS NOT NULL LIMIT 1");
$has_certificate = mysqli_num_rows($query_cert) > 0;

// Cek Status Taiwan
$query_taiwan = mysqli_query($conn, "SELECT status FROM program_taiwan WHERE id_siswa = $id_siswa");
$taiwan_status = mysqli_fetch_assoc($query_taiwan)['status'] ?? null;


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

        /* Floating Notification Taiwan */
        .taiwan-notif-bar {
            position: fixed;
            top: 85px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(254, 243, 199, 0.95);
            border: 1px solid #F59E0B;
            padding: 12px 25px;
            border-radius: 50px;
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 10px 25px -5px rgba(245, 158, 11, 0.3);
            border-left: 5px solid #F59E0B;
            animation: slideDown 0.5s ease-out;
            max-width: 90%;
            width: fit-content;
        }
        @keyframes slideDown {
            from { top: 50px; opacity: 0; }
            to { top: 85px; opacity: 1; }
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
    <nav class="navbar">
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

    <!-- Floating Notification Taiwan -->
    <?php if (!isset($_SESSION['taiwan_notif_shown'])): ?>
        <?php if ($has_certificate && !$taiwan_status): ?>
        <div class="taiwan-notif-bar">
            <i class="fas fa-plane-departure" style="color: #D97706; font-size: 1.2rem;"></i>
            <div style="font-size: 0.9rem; color: #92400E; font-weight: 600;">
                Kabar Gembira! Anda memenuhi syarat untuk mengikuti <span style="color: #B45309; text-decoration: underline;">Program Magang Taiwan</span>.
            </div>
            <a href="taiwan_siswa.php" style="background: #D97706; color: white; padding: 6px 15px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; text-decoration: none; transition: 0.3s;" onmouseover="this.style.background='#B45309'" onmouseout="this.style.background='#D97706'">Cek Program</a>
        </div>
        <?php $_SESSION['taiwan_notif_shown'] = true; ?>
        <?php elseif ($taiwan_status): 
            $status_texts = [
                'berminat' => 'Menunggu konfirmasi admin',
                'diajukan_mitra' => 'Menunggu konfirmasi pihak Taiwan',
                'lolos' => 'Selamat! Anda Lolos Program Taiwan',
                'ditolak' => 'Mohon maaf, Anda belum lolos'
            ];
            $status_text = $status_texts[$taiwan_status] ?? 'Sedang diproses';
        ?>
        <div class="taiwan-notif-bar" style="background: rgba(224, 242, 254, 0.95); border-color: #38BDF8; border-left-color: #0284C7;">
            <i class="fas fa-info-circle" style="color: #0284C7; font-size: 1.2rem;"></i>
            <div style="font-size: 0.9rem; color: #075985; font-weight: 600;">
                Status Program Taiwan: <span style="color: #0369A1;"><?= $status_text ?></span>
            </div>
            <a href="taiwan_siswa.php" style="background: #0284C7; color: white; padding: 6px 15px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; text-decoration: none;">Cek Detail</a>
        </div>
        <?php $_SESSION['taiwan_notif_shown'] = true; ?>
        <?php endif; ?>
    <?php endif; ?>

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
        <?php if ($siswa['status_pembayaran'] === 'lunas_dp' && $siswa['dp_notified'] == 0): ?>
            <div style="background: #d4edda; color: #155724; padding: 15px 20px; border-radius: 12px; margin-bottom: 25px; display: flex; align-items: center; gap: 15px; border: 1px solid #c3e6cb;">
                <i class="fas fa-check-circle" style="font-size: 20px;"></i>
                <div>
                    <strong>Pembayaran DP Berhasil!</strong> Dashboard Anda kini telah aktif sepenuhnya. Selamat belajar!
                </div>
            </div>
            <?php 
                // Set flag agar tidak muncul lagi
                $id_siswa = $siswa['id_siswa'];
                mysqli_query($conn, "UPDATE siswa SET dp_notified = 1 WHERE id_siswa = $id_siswa");
            ?>
        <?php endif; ?>



        <!-- Status Card -->
        <section class="card status-card">
            <div class="status-left">
                <h2 class="status-title">Status: <span class="badge-success">Siswa Aktif</span></h2>
                <p class="status-desc">Program <?= htmlspecialchars($siswa['nama_program'] ?? 'Hotel & Cruise Ship Selection') ?> | NIM: <?= htmlspecialchars($siswa['nim_siswa'] ?? '-') ?></p>
            </div>
            <div class="status-right">
                <span class="status-label">Kelengkapan Berkas</span>
                <span class="status-value-success">100% Terverifikasi</span>
            </div>
        </section>

        <!-- Main Grid Container -->
        <section class="main-grid">
            <!-- Announcements Card (Left) -->
            <div class="card announcement-card">
                <h2 class="announcement-title">Papan Pengumuman</h2>
                <div class="card-body announcement-list">
                    <?php if ($has_certificate && !$taiwan_status): ?>
                    <div class="announcement-item" style="border-left: 4px solid #F59E0B; background: #FFFBEB;">
                        <div class="announcement-main">
                            <h3 style="color: #92400E;">Penawaran Program Magang & Kuliah Taiwan</h3>
                            <p>Selamat! Berdasarkan hasil sertifikasi Anda, Anda berhak mendaftar program internship internasional ke Taiwan. Segera lengkapi minat Anda di menu Taiwan.</p>
                        </div>
                        <div class="announcement-date">Prioritas</div>
                    </div>
                    <?php endif; ?>

                    <?php if (empty($announcements)): ?>
                        <p style="text-align: center; color: #64748b; padding: 20px;">Belum ada pengumuman terbaru.</p>
                    <?php else: ?>
                        <?php foreach ($announcements as $ann): ?>
                            <div class="announcement-item">
                                <div class="announcement-main">
                                    <h3 class="text-<?= $ann['type'] ?>"><?= htmlspecialchars($ann['title']) ?></h3>
                                    <p><?= htmlspecialchars($ann['message']) ?></p>
                                </div>
                                <div class="announcement-date">
                                    <?php 
                                        $date = strtotime($ann['created_at']);
                                        echo date('d M', $date);
                                    ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
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
                        <a href="taiwan_siswa.php" class="qa-item">
                            <div class="qa-icon"><i class="fas fa-plane"></i></div>
                            <span>Taiwan</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <div style="height: 100px;"></div>

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