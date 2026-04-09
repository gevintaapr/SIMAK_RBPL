<?php
session_start();
require_once __DIR__ . '/../../config/config.php';

// Check if user is logged in
if (!isset($_SESSION['siswa_logged_in']) || $_SESSION['siswa_logged_in'] !== true) {
    header("Location: ../../public/login/logCalonSiswa.php");
    exit();
}

$id_pendaftaran = $_SESSION['siswa_id'];
$query = mysqli_query($conn, "SELECT * FROM pendaftaran WHERE id_pendaftaran = '$id_pendaftaran'");
$daftar = mysqli_fetch_assoc($query);

if (!$daftar) {
    session_destroy();
    header("Location: ../../public/login/logCalonSiswa.php");
    exit();
}

// Logic for alert boxes
$alert_bg = "#fff3cd"; $alert_text = "#856404"; $alert_border = "#ffeeba";
$alert_icon = "fa-info-circle";
$alert_title = "Verifikasi Dokumen Sedang Berlangsung";
$alert_desc = "Admin kami sedang memeriksa kelengkapan dokumen Anda. Proses ini biasanya memakan waktu 1-2 hari kerja.<br>Harap cek status secara berkala.";

$status_verifikasi = "Menunggu";
$status_badge_text = "Lengkap, Menunggu Verifikasi";

if (!empty($daftar['hasil_akhir']) && $daftar['hasil_akhir'] !== 'pending') {
    if ($daftar['hasil_akhir'] === 'lulus') {
        $alert_bg = "#d4edda"; $alert_text = "#155724"; $alert_border = "#c3e6cb";
        $alert_icon = "fa-check-circle";
        $alert_title = "Selamat! Anda Lolos Seleksi!";
        $alert_desc = "Anda telah resmi diterima sebagai siswa. Silakan periksa email untuk instruksi lebih lanjut.";
        $status_verifikasi = "Lolos Seleksi";
        $status_badge_text = "Lolos Seleksi";
    } else {
        $alert_bg = "#f8d7da"; $alert_text = "#721c24"; $alert_border = "#f5c6cb";
        $alert_icon = "fa-times-circle";
        $alert_title = "Mohon Maaf, Anda Tidak Lolos Seleksi";
        $alert_desc = "Tetap semangat dan jangan menyerah. Terima kasih atas partisipasi Anda.";
        $status_verifikasi = "Tidak Lulus";
        $status_badge_text = "Gagal Seleksi";
    }
} elseif (!empty($daftar['jadwal_wawancara'])) {
    $alert_bg = "#d1ecf1"; $alert_text = "#0c5460"; $alert_border = "#bee5eb";
    $alert_icon = "fa-calendar-alt"; $alert_title = "Jadwal Wawancara Anda Telah Ditetapkan";
    $alert_desc = "Wawancara akan dilaksanakan pada: <strong>" . date("d M Y H:i", strtotime($daftar['jadwal_wawancara'])) . "</strong>.<br>Hadir tepat waktu dan persiapkan diri Anda.";
    $status_verifikasi = "Menunggu Wawancara";
    $status_badge_text = "Jadwal Wawancara";
} elseif ($daftar['status_approval'] === 'disetujui') {
    $alert_bg = "#d1ecf1"; $alert_text = "#0c5460"; $alert_border = "#bee5eb";
    $alert_icon = "fa-clock"; $alert_title = "Selamat! Berkas Disetujui Pimpinan";
    $alert_desc = "Menunggu admin untuk menetapkan jadwal wawancara Anda. Silakan cek berkala.";
    $status_verifikasi = "Menunggu Jadwal Wawancara";
    $status_badge_text = "Disetujui Pimpinan";
} elseif ($daftar['status_approval'] === 'ditolak') {
    $alert_bg = "#f8d7da"; $alert_text = "#721c24"; $alert_border = "#f5c6cb";
    $alert_icon = "fa-times-circle"; $alert_title = "Pendaftaran Ditolak Pimpinan";
    $alert_desc = "Mohon maaf, pendaftaran Anda tidak dapat dilanjutkan ke tahap berikutnya.";
    $status_verifikasi = "Ditolak Pimpinan";
    $status_badge_text = "Ditolak";
} elseif ($daftar['status_berkas'] === 'valid') {
    $alert_bg = "#fff3cd"; $alert_text = "#856404"; $alert_border = "#ffeeba";
    $alert_icon = "fa-info-circle"; $alert_title = "Dokumen sudah berhasil terverifikasi!";
    $alert_desc = "Silakan perhatikan jadwal wawancara di bawah ini untuk melanjutkan proses pendaftaran Program HCTS. Hubungi kontak yang tersedia untuk informasi lebih lanjut.";
    $status_verifikasi = "Tahap Wawancara";
    $status_badge_text = "Verifikasi Berhasil";
} elseif ($daftar['status_berkas'] === 'tidak_valid') {
    $alert_bg = "#f8d7da"; $alert_text = "#721c24"; $alert_border = "#f5c6cb";
    $alert_icon = "fa-times-circle"; $alert_title = "Berkas Tidak Valid";
    $alert_desc = "Mohon maaf, berkas Anda dinyatakan tidak valid oleh admin.";
    $status_verifikasi = "Berkas Ditolak";
    $status_badge_text = "Berkas Tidak Valid";
}

// Logic for Timeline steps
$s3 = ""; $s4 = ""; $s5 = ""; $s6 = ""; 
$progress_width = "20%"; // Step 1 and 2 complete

if ($daftar['status_berkas'] === 'valid') {
    $s3 = "completed";
    $s4 = "active";
    $progress_width = "54%";
} elseif ($daftar['status_approval'] === 'disetujui') {
    $s3 = "completed";
    $s4 = "active";
    $progress_width = "54%";
}
if (!empty($daftar['jadwal_wawancara'])) {
    $s3 = "completed";
    $s4 = "active";
    $progress_width = "54%";
}
if (!empty($daftar['hasil_akhir']) && $daftar['hasil_akhir'] !== 'pending') {
    $s4 = "completed";
    $s5 = "completed";
    $progress_width = "82%";
    if ($daftar['hasil_akhir'] === 'lulus') {
        $s6 = "active";
        $progress_width = "100%";
    }
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
                <h2>Selamat Datang, <?= htmlspecialchars(explode(" ", $daftar['nama_cs'])[0]) ?></h2>
                <p>No. Reg: <?= htmlspecialchars($daftar['id_pendaftaran']) ?></p>
            </div>
            <div class="status-badge">Calon Siswa</div>
        </div>

        <!-- Warning Info -->
        <div class="alert-warning" style="background-color: <?= $alert_bg ?>; color: <?= $alert_text ?>; border-left: 4px solid <?= $alert_border ?>; padding: 15px 20px; border-radius: 8px; display: flex; align-items: flex-start; gap: 15px; margin-bottom: 30px;">
            <i class="fas <?= $alert_icon ?>" style="font-size: 1.5rem; margin-top: 3px;"></i>
            <div class="alert-content">
                <h4 style="margin: 0 0 5px 0; font-family: 'Poppins', sans-serif; font-size: 1.1rem; color: <?= $alert_text ?>;"><?= $alert_title ?></h4>
                <p style="margin: 0; font-size: 0.95rem; line-height: 1.5; color: <?= $alert_text ?>;"><?= $alert_desc ?></p>
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
                <div class="step-circle"><?= $s3 == 'completed' ? '<i class="fas fa-check"></i>' : '3' ?></div>
                <div class="step-label">Verifikasi Admin</div>
            </div>
            <div class="timeline-step <?= $s4 ?>">
                <div class="step-circle"><?= $s4 == 'completed' ? '<i class="fas fa-check"></i>' : ( ($s4 == 'active') ? '4' : '4' ) ?></div>
                <div class="step-label">Wawancara</div>
            </div>
            <div class="timeline-step <?= $s5 ?>">
                <div class="step-circle"><?= $s5 == 'completed' ? '<i class="fas fa-check"></i>' : '5' ?></div>
                <div class="step-label">Pengumuman</div>
            </div>
            <div class="timeline-step <?= $s6 ?>">
                <div class="step-circle">6</div>
                <div class="step-label">Mulai Belajar</div>
            </div>
        </div>

        <!-- Grid Cards -->
        <div class="dashboard-grid">
            <?php if (!empty($daftar['hasil_akhir']) && $daftar['hasil_akhir'] === 'lulus'): ?>
            <div class="content-card" style="border-color: #c2e2af; background: #fdfdfd;">
                <h3>Informasi Akademik</h3>
                <div class="doc-status" style="background:#f0faeb; border: 1px solid #c2e2af; border-radius: 12px; padding: 2.5rem 1rem;">
                    <div class="icon-folder" style="background:none; color:#4a9e22; margin-bottom: 0;">
                        <i class="fas fa-graduation-cap" style="font-size:3rem;"></i>
                    </div>
                    <h4 style="color: #2b6110; margin-bottom: 0.5rem;">Selamat! Anda Telah Lulus</h4>
                    <p style="color:#3d7522; margin-bottom: 1.5rem;">Berikut adalah informasi akun belajar HCTS Anda.</p>
                    <div style="background:white; padding: 20px; border-radius: 8px; margin: 0 15px; text-align: left; border: 1px solid #d4ebc6;">
                        <strong style="color: #333; font-size:0.9rem;">Email Belajar:</strong><br>
                        <?php
                            $parts = explode('-', $daftar['id_pendaftaran']); 
                            $tahun = substr($parts[1] ?? '2026', -2); 
                            $nodaftar = $parts[2] ?? '0000'; 
                            $nama_parts = !empty($daftar['nama_cs']) ? explode(' ', strtolower(trim($daftar['nama_cs']))) : ['siswa'];
                            $nama_awal = preg_replace('/[^a-z]/', '', $nama_parts[0]);
                            $dummy_email = $nama_awal . $nodaftar . '.' . $tahun . '@hcts.ac.id';
                        ?>
                        <span style="color:#0369a1; font-weight:700; font-size: 1.2rem;"><?= htmlspecialchars($daftar['email_belajar'] ?? $dummy_email) ?></span>
                        <br><br>
                        <strong style="color: #333; font-size:0.9rem;">Password Awal:</strong><br>
                        <span style="color:#555; font-family: monospace; font-size: 1.1rem; background: #f1f5f9; padding: 4px 8px; border-radius: 4px;">HCTS<?= date('Y', strtotime($daftar['created_at'] ?? 'now')) ?></span>
                    </div>
                    <a href="../../public/login/logSiswa.php" style="display:inline-block; margin-top:20px; background:#4a9e22; color:white; padding: 12px 25px; border-radius: 8px; text-decoration:none; font-weight:600; box-shadow: 0 4px 6px rgba(74, 158, 34, 0.2);">Login ke Portal Belajar</a>
                </div>
            </div>
            <?php elseif ($daftar['status_berkas'] === 'valid'): ?>
            <div class="content-card">
                <h3>Status Dokumen</h3>
                <div class="doc-status" style="text-align: center;">
                    <div class="icon-folder" style="color: #0d6efd; background: #e7f1ff; width: 80px; height: 80px; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                        <i class="fas fa-check-circle" style="font-size: 2.5rem;"></i>
                    </div>
                    <h4 style="color: #003B73; font-weight: 700; font-size: 1.1rem; margin-bottom: 15px;">Jadwal Wawancara Program HCTS:</h4>
                    <div style="text-align: left; font-size: 0.9rem; color: #003B73; display: inline-block; margin-bottom: 20px;">
                        <p style="margin-bottom: 5px;"><i class="fas fa-circle" style="font-size: 0.5rem; vertical-align: middle; margin-right: 10px;"></i> Hari / Tanggal : <?= date('l, d F Y', strtotime($daftar['jadwal_wawancara'] ?? '+7 days')) ?></p>
                        <p style="margin-bottom: 5px;"><i class="fas fa-circle" style="font-size: 0.5rem; vertical-align: middle; margin-right: 10px;"></i> Waktu : <?= date('H.i', strtotime($daftar['jadwal_wawancara'] ?? '09:00')) ?> - 11.00 WIB</p>
                        <p style="margin-bottom: 5px;"><i class="fas fa-circle" style="font-size: 0.5rem; vertical-align: middle; margin-right: 10px;"></i> Metode : Online (Zoom Meeting)</p>
                        <p style="margin-bottom: 5px;"><i class="fas fa-circle" style="font-size: 0.5rem; vertical-align: middle; margin-right: 10px;"></i> Link Wawancara : Akan dikirim melalui WhatsApp / Email</p>
                    </div>
                    <p style="font-size: 0.8rem; font-style: italic; color: #666; border-top: 1px solid #eee; padding-top: 10px;">Keterangan : Peserta diharapkan hadir 10 menit sebelum jadwal dimulai</p>
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
                    <p>Mencakup: KTP, Ijazah, Pas Foto, Bukti Pendaftaran, dan Surat Pernyataan</p>
                    <div class="status-pill"><?= $status_verifikasi ?></div>
                </div>
            </div>
            <?php endif; ?>
            <div class="content-card">
                <h3>Ringkasan Data</h3>
                <table class="data-summary">
                    <tr>
                        <td>Nama Lengkap</td>
                        <td><?= htmlspecialchars($daftar['nama_cs']) ?></td>
                    </tr>
                    <tr>
                        <td>Program Pilihan</td>
                        <td><?= htmlspecialchars($daftar['program']) ?></td>
                    </tr>
                    <tr>
                        <td>No HP/WA</td>
                        <td><?= htmlspecialchars($daftar['no_wa'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td>Tanggal Daftar</td>
                        <td><?= isset($daftar['tanggal']) ? date("d M Y", strtotime($daftar['tanggal'])) : (isset($daftar['created_at']) ? date("d M Y", strtotime($daftar['created_at'])) : '-') ?></td>
                    </tr>
                    <tr>
                        <td>Status Akhir</td>
                        <td class="highlight-text" style="color: #f59e0b; font-weight: 700;"><?= htmlspecialchars($status_verifikasi) ?></td>
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
