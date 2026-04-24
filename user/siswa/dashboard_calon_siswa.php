<?php
session_start();
require_once __DIR__ . '/../../config/config.php';

// Check if user is logged in
if (!isset($_SESSION['siswa_logged_in']) || $_SESSION['siswa_logged_in'] !== true || $_SESSION['role'] != 2) {
    header("Location: ../../public/login/logCalonSiswa.php?error=" . urlencode("Sesi berakhir atau akses ditolak."));
    exit();
}

$id_pendaftaran = $_SESSION['siswa_id'];
$query = mysqli_query($conn, "SELECT p.*, pr.nama_program FROM pendaftaran p LEFT JOIN program pr ON p.id_program = pr.id_program WHERE p.id_pendaftaran = '$id_pendaftaran'");
$daftar = mysqli_fetch_assoc($query);

if (!$daftar) {
    session_destroy();
    header("Location: ../../public/login/logCalonSiswa.php");
    exit();
}

// Pengecekan Masa Berlaku Dashboard (2 Hari setelah Approve)
if ($daftar['status_approval'] === 'disetujui' || $daftar['status_approval'] === '1') {
    if (!empty($daftar['token_expired'])) {
        $now = new DateTime();
        $expired = new DateTime($daftar['token_expired']);
        
        if ($now > $expired) {
            session_destroy();
            header("Location: ../../public/login/logSiswa.php?error=" . urlencode("Masa berlaku dashboard calon siswa telah habis. Silakan gunakan akun Siswa resmi Anda."));
            exit();
        }
    }
}

// Logic for alert boxes and status
$alert_class = "alert-warning";
$alert_icon = "fa-info-circle";
$alert_title = "Pendaftaran Sedang Berlangsung";
$alert_desc = "Harap pantau terus dashboard Anda untuk perkembangan status pendaftaran.";
$status_verifikasi = "Diproses";
$status_badge_text = "Menunggu";

// Timeline Progress
$s3 = ""; $s4 = ""; $s5 = ""; $s6 = ""; 
$progress_width = "20%"; // Step 1 and 2 complete (Pendaftaran, Upload)

if ($daftar['status_approval'] === 'disetujui' || $daftar['status_approval'] === '1') {
    $alert_class = "alert-success";
    $alert_icon = "fa-check-circle";
    $alert_title = "Selamat! Anda Lolos Seleksi!";
    $alert_desc = "Anda telah resmi diterima sebagai siswa Program HCTS. Silakan lakukan proses administrasi selanjutnya.";
    $status_verifikasi = "Lolos Seleksi";
    $status_badge_text = "Lolos Seleksi";
    $s3 = "completed"; $s4 = "completed"; $s5 = "completed"; $s6 = "active";
    $progress_width = "100%";
} elseif ($daftar['status_approval'] === 'ditolak') {
    $alert_class = "alert-danger";
    $alert_icon = "fa-times-circle";
    $alert_title = "Hasil Seleksi: Tidak Lulus";
    $alert_desc = "Mohon maaf, Anda belum dapat melanjutkan ke tahap berikutnya. Tetap semangat!";
    $status_verifikasi = "Tidak Lulus";
    $status_badge_text = "Gagal Seleksi";
    $s3 = "completed"; $s4 = "completed"; $s5 = "completed";
    $progress_width = "82%";
} elseif ($daftar['status_approval'] === 'menunggu_pimpinan') {
    $alert_class = "alert-secondary";
    $alert_icon = "fa-hourglass-half";
    $alert_title = "Tahap Wawancara Selesai";
    $alert_desc = "Wawancara telah selesai dilaksanakan. Saat ini sedang menunggu keputusan akhir dari Pimpinan HCTS.";
    $status_verifikasi = "Menunggu Keputusan";
    $status_badge_text = "Menunggu Approval";
    $s3 = "completed"; $s4 = "completed"; $s5 = "active";
    $progress_width = "82%";
} elseif (!empty($daftar['jadwal_wawancara'])) {
    $alert_class = "alert-info";
    $alert_icon = "fa-calendar-alt";
    $alert_title = "Jadwal Wawancara Telah Ditetapkan";
    $alert_desc = "Silakan hadir pada wawancara tanggal: <strong>" . date("d M Y H:i", strtotime($daftar['jadwal_wawancara'])) . " WIB</strong>. Persiapkan dokumen pendukung Anda.";
    $status_verifikasi = "Menunggu Wawancara";
    $status_badge_text = "Wawancara Terjadwal";
    $s3 = "completed"; $s4 = "active";
    $progress_width = "67%";
} elseif ($daftar['status_berkas'] === 'valid') {
    $alert_class = "alert-success";
    $alert_icon = "fa-user-check";
    $alert_title = "Berkas Telah Terverifikasi!";
    $alert_desc = "Dokumen Anda sudah divalidasi oleh Admin. Mohon tunggu penetapan jadwal wawancara dari tim kami.";
    $status_verifikasi = "Berkas Valid";
    $status_badge_text = "Terverifikasi";
    $s3 = "completed"; $s4 = "active";
    $progress_width = "50%";
} else {
    // Default: Waiting verification
    $alert_class = "alert-warning";
    $alert_icon = "fa-info-circle";
    $alert_title = "Menunggu Verifikasi Berkas";
    $alert_desc = "Admin sedang memeriksa kelengkapan dokumen pendaftaran Anda. Mohon tunggu 1-2 hari kerja.";
    $status_verifikasi = "Pending";
    $status_badge_text = "Daftar Tunggu";
    $s3 = "active";
    $progress_width = "33%";
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Calon Siswa</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../style/dashboard_calon_siswa.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../style/dashboard_siswa.css">
    <link rel="stylesheet" href="../../style/popup_logout.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-brand">HCTS</div>
        <ul class="nav-menu">
            <li><a href="dashboard_calon_siswa.php" class="active">Home</a></li>
            <li><a href="#">Evaluasi</a></li>
            <li><a href="#">Keuangan</a></li>
            <li><a href="#">Magang</a></li>
        </ul>
        <div class="nav-action">
            <a href="#" class="nav-bell"><i class="far fa-bell"></i></a>
            <a href="#" onclick="showLogoutPopup(event)" class="btn-logout">Logout</a>
        </div>
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
                <h2>Selamat Datang, <?= htmlspecialchars(explode(" ", $daftar['nama_cs'])[0]) ?></h2>
                <p>No. Reg: <?= htmlspecialchars($daftar['token_masuk']) ?></p>
            </div>
            <div class="status-badge">Calon Siswa</div>
        </div>

        <!-- Dynamic Alert Box -->
        <div class="alert-box <?= $alert_class ?>">
            <i class="fas <?= $alert_icon ?> alert-icon"></i>
            <div class="alert-content">
                <h4><?= $alert_title ?></h4>
                <p><?= $alert_desc ?></p>
            </div>
        </div>

        <!-- Timeline -->
        <div class="timeline">
            <div class="timeline-progress-bar" style="width: <?= $progress_width ?>;"></div>
            <div class="timeline-step completed">
                <div class="step-circle"><i class="fas fa-check"></i></div>
                <div class="step-label">Pendaftaran</div>
            </div>
            <div class="timeline-step completed">
                <div class="step-circle"><i class="fas fa-check"></i></div>
                <div class="step-label">Upload Dokumen</div>
            </div>
            <div class="timeline-step <?= $s3 ?>">
                <div class="step-circle"><?= ($s3 == 'completed') ? '<i class="fas fa-check"></i>' : '3' ?></div>
                <div class="step-label">Verifikasi Admin</div>
            </div>
            <div class="timeline-step <?= $s4 ?>">
                <div class="step-circle"><?= ($s4 == 'completed') ? '<i class="fas fa-check"></i>' : '4' ?></div>
                <div class="step-label">Wawancara</div>
            </div>
            <div class="timeline-step <?= $s5 ?>">
                <div class="step-circle"><?= ($s5 == 'completed') ? '<i class="fas fa-check"></i>' : '5' ?></div>
                <div class="step-label">Pengumuman</div>
            </div>
            <div class="timeline-step <?= $s6 ?>">
                <div class="step-circle"><?= ($s6 == 'completed') ? '<i class="fas fa-check"></i>' : '6' ?></div>
                <div class="step-label">Mulai Belajar</div>
            </div>
        </div>


        <div class="dashboard-grid">
            <div class="main-column">
                <!-- Info Status -->
                <div class="card status-container">
                    <div class="card-header-flex">
                        <h3>Status Berkas & Administrasi</h3>
                        <span>Diperbarui: <?= date('d M Y') ?></span>
                    </div>
                    <?php if ($daftar['status_approval'] === 'disetujui' || $daftar['status_approval'] === '1'): ?>
                    <div class="content-card success-card">
                        <h3>Informasi Akademik</h3>
                        <div class="doc-status card-pass">
                            <div class="icon-block icon-pass">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <h4>Selamat! Anda Telah Lulus</h4>
                            <p>Berikut adalah informasi akun belajar HCTS Anda.</p>
                            <div class="info-box-pass">
                                <strong>Email Belajar:</strong><br>
                                <?php
                                    $nodaftar = $daftar['id_pendaftaran'];
                                    $nama_parts = explode(' ', strtolower(trim($daftar['nama_cs'])));
                                    $nama_awal = preg_replace('/[^a-z]/', '', $nama_parts[0]);
                                    $dummy_email = $nama_awal . substr($nodaftar, -4) . '.26@hcts.ac.id';
                                ?>
                                <span class="email-text"><?= htmlspecialchars($daftar['email_belajar'] ?? $dummy_email) ?></span>
                                <br><br>
                                <strong>Password Awal:</strong><br>
                                <span class="password-code">HCTS2026</span>
                            </div>
                            <a href="../../public/login/logSiswa.php" class="btn-login-pass">Login ke Portal Belajar</a>
                        </div>
                    </div>
                    <?php elseif ($daftar['status_approval'] === 'menunggu_pimpinan'): ?>
                    <div class="content-card">
                        <h3>Informasi Wawancara</h3>
                        <div class="doc-status centered-status">
                            <div class="icon-block rounded-icon check-double">
                                <i class="fas fa-check-double"></i>
                            </div>
                            <h4>Wawancara Telah Selesai!</h4>
                            <p>
                                Selamat! Anda telah menyelesaikan seluruh rangkaian tahap wawancara.<br>
                                Hasil seleksi akan segera diumumkan setelah mendapatkan persetujuan dari Pimpinan.<br>
                                <strong>Pantau terus dashboard Anda secara berkala.</strong>
                            </p>
                        </div>
                    </div>
                    <?php elseif (!empty($daftar['jadwal_wawancara'])): ?>
                    <div class="content-card">
                        <h3>Informasi Wawancara</h3>
                        <div class="doc-status centered-status">
                            <div class="icon-block rounded-icon interview-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <h4>Jadwal Wawancara Anda:</h4>
                            <div class="interview-box">
                                <p><i class="fas fa-clock"></i> <strong>Waktu:</strong> <?= date('l, d F Y, H:i', strtotime($daftar['jadwal_wawancara'])) ?> WIB</p>
                                <p><i class="fas fa-video"></i> <strong>Metode:</strong> Online via Zoom Meeting</p>
                                <p><i class="fas fa-link"></i> <strong>Link:</strong> Akan dikirim via WhatsApp</p>
                            </div>
                            <p class="italic-text">Harap persiapkan koneksi internet yang stabil.</p>
                        </div>
                    </div>
                    <?php elseif ($daftar['status_berkas'] === 'valid'): ?>
                    <div class="content-card">
                        <h3>Status Dokumen</h3>
                        <div class="doc-status centered-status file-valid">
                            <div class="icon-block rounded-icon check-double">
                                <i class="fas fa-file-circle-check"></i>
                            </div>
                            <h4>Berkas Terverifikasi</h4>
                            <p>Dokumen Anda telah divalidasi oleh Admin. Mohon tunggu penetapan jadwal wawancara.</p>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="content-card">
                        <h3>Status Dokumen</h3>
                        <div class="doc-status">
                            <div class="icon-folder">
                                <i class="fas fa-folder"></i>
                            </div>
                            <h4><?= $status_badge_text ?></h4>
                            <p>Status pendaftaran saat ini: <?= $status_verifikasi ?></p>
                            <div class="status-pill <?= $alert_class ?>"><?= $status_verifikasi ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Download Card -->
                <div class="card premium-card">
                    <div class="premium-card-text">
                        <h3>Surat Pernyataan Kebenaran Dokumen</h3>
                        <p>Unduh, lengkapi, dan simpan dokumen ini sebagai syarat verifikasi akhir pendaftaran Anda.</p>
                    </div>
                    <a href="download_template.php?code=surat_pernyataan" target="_blank" class="btn-download">
                        <i class="fas fa-file-download"></i> Unduh Template
                    </a>
                </div>
            </div>

            <div class="side-column">
                <div class="card quick-actions">
                    <h3>Aksi Cepat</h3>
                    <div class="action-links-container">
                        <a href="kontak_admin.php" class="action-link">
                            <i class="fas fa-headset"></i> Hubungi Admin
                        </a>
                        <a href="#" class="action-link">
                            <i class="fas fa-book"></i> Panduan Siswa
                        </a>
                    </div>
                </div>
                <div class="content-card" style="margin-top: 25px;">
                    <h3>Ringkasan Data</h3>
                    <table class="data-summary">
                        <tr>
                            <td>Nama Lengkap</td>
                            <td><?= htmlspecialchars($data['nama_cs'] ?? $daftar['nama_cs']) ?></td>
                        </tr>
                        <tr>
                            <td>Program Pilihan</td>
                            <td><?= htmlspecialchars($daftar['nama_program'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td>No HP/WA</td>
                            <td><?= htmlspecialchars($daftar['no_wa'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td>Status Alur</td>
                            <td class="highlight-text" style="color: <?= $status_color ?? '#f59e0b' ?>; font-weight: 700;"><?= $status_verifikasi ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <div style="height: 50px;"></div>
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
