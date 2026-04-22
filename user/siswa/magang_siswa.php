<?php
require_once __DIR__ . '/../../config/config.php';

// Auth: hanya siswa (role_id = 1)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../../public/login/logSiswa.php?role=1&error=" . urlencode("Akses ditolak. Silakan login menggunakan akun Siswa Anda."));
    exit;
}

$id_user = $_SESSION['user_id'];

// Ambil data siswa
$query_siswa = mysqli_query($conn, "SELECT * FROM siswa WHERE id_user = $id_user");
$siswa = mysqli_fetch_assoc($query_siswa);
$nama_siswa = $siswa['nama_lengkap'] ?? 'Siswa';

// Fetch latest internship data
$query_magang = mysqli_query($conn, "SELECT * FROM magang WHERE user_id = $id_user ORDER BY created_at DESC LIMIT 1");
$magang = mysqli_fetch_assoc($query_magang);

$current_step = 1;
if ($magang) {
    if ($magang['status_pengajuan'] === 'pending') {
        $current_step = 2; // Menunggu persetujuan pimpinan
    } elseif ($magang['status_pengajuan'] === 'disetujui_pimpinan' && $magang['status_verifikasi'] === 'pending') {
        $current_step = 2; // Menunggu verifikasi admin
    } elseif ($magang['status_verifikasi'] === 'diterima' && $magang['status_magang'] === 'berlangsung') {
        $current_step = 3; // Magang aktif
    } elseif ($magang['status_magang'] === 'selesai') {
        $current_step = 6; // Selesai
    } elseif ($magang['status_pengajuan'] === 'ditolak_pimpinan' || $magang['status_verifikasi'] === 'ditolak') {
        $current_step = 1; // Bisa ajukan ulang
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proses Magang - HCTS</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../style/magang_siswa.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../style/popup_logout.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-brand">HCTS</div>
        <ul class="nav-menu">
            <li><a href="dashboard_siswa.php">Home</a></li>
            <li><a href="pembayaranSiswa.php">Pembayaran</a></li>
            <li><a href="magang_siswa.php" class="active">Magang</a></li>
            <li><a href="#">Program Taiwan</a></li>
        </ul>
        <div class="nav-action">
            <a href="#" class="nav-bell"><i class="far fa-bell"></i></a>
            <a href="#" onclick="showLogoutPopup(event)" class="btn-logout">Logout</a>
        </div>
    </nav>

    <main class="container">
        <h1 class="page-title">Proses Magang</h1>

        <!-- Progress Stepper -->
        <section class="stepper-section card">
            <div class="stepper">
                <div class="step <?= $current_step >= 1 ? 'active' : '' ?> <?= $current_step > 1 ? 'completed' : '' ?>">
                    <div class="step-icon"><?= $current_step > 1 ? '<i class="fas fa-check"></i>' : '1' ?></div>
                    <span class="step-label">Pengajuan Terkirim</span>
                </div>
                <div class="step-line"></div>
                <div class="step <?= $current_step >= 2 ? 'active' : '' ?> <?= $current_step > 2 ? 'completed' : '' ?>">
                    <div class="step-icon"><?= $current_step > 2 ? '<i class="fas fa-check"></i>' : '2' ?></div>
                    <span class="step-label">Verifikasi Akademik</span>
                </div>
                <div class="step-line"></div>
                <div class="step <?= $current_step >= 3 ? 'active' : '' ?> <?= $current_step > 4 ? 'completed' : '' ?>">
                    <div class="step-icon"><?= $current_step > 4 ? '<i class="fas fa-check"></i>' : '3' ?></div>
                    <span class="step-label">Hasil & Proses</span>
                </div>
                <div class="step-line"></div>
                <div class="step <?= $current_step >= 6 ? 'active completed' : '' ?>">
                    <div class="step-icon"><?= $current_step >= 6 ? '<i class="fas fa-check"></i>' : '4' ?></div>
                    <span class="step-label">Selesai Magang</span>
                </div>
            </div>
        </section>

        <!-- Dynamic Content Based on Step -->
        <?php if ($current_step == 1): ?>
            <!-- STATE 1: PENGAJUAN -->
            <section class="card content-card">
                <div class="card-header-main">
                    <h2>Pengajuan Magang (On-the-Job Training)</h2>
                </div>
                
                <div class="requirements-grid">
                    <div class="info-box">
                        <h3><i class="fas fa-id-card"></i> Syarat Pengajuan</h3>
                        <ul>
                            <li><i class="fas fa-check"></i> Status siswa aktif & administrasi lunas.</li>
                            <li><i class="fas fa-check"></i> Lulus Evaluasi Akademik (Min. Nilai B).</li>
                            <li><i class="fas fa-check"></i> Sehat jasmani (MCU jika diperlukan).</li>
                        </ul>
                    </div>
                    <div class="info-box">
                        <h3><i class="fas fa-book-reader"></i> Alur Pelaksanaan</h3>
                        <ol>
                            <li><span>1</span> Pengajuan Data Industri</li>
                            <li><span>2</span> Verifikasi Akademik & Interview</li>
                            <li><span>3</span> Penerbitan Surat Pengantar</li>
                        </ol>
                    </div>
                </div>

                <div class="alert alert-success">
                    <div class="alert-icon"><i class="fas fa-check-circle"></i></div>
                    <div class="alert-text">
                        <strong>Anda Memenuhi Syarat Magang</strong>
                        <p>Status Evaluasi Akademik Anda dinyatakan LULUS. Silakan lengkapi formulir di bawah ini.</p>
                    </div>
                </div>
            </section>

            <section class="card content-card">
                <div class="card-header-main">
                    <h2>Formulir Rencana Magang</h2>
                </div>
                <?php if ($magang && $magang['catatan_pimpinan'] && $magang['status_pengajuan'] === 'ditolak_pimpinan'): ?>
                    <div class="alert alert-danger" style="margin-bottom: 20px;">
                        <strong>Ditolak Pimpinan:</strong> <?= htmlspecialchars($magang['catatan_pimpinan']) ?>
                    </div>
                <?php endif; ?>
                <?php if ($magang && $magang['catatan_admin'] && $magang['status_verifikasi'] === 'ditolak'): ?>
                    <div class="alert alert-danger" style="margin-bottom: 20px;">
                        <strong>Ditolak Admin:</strong> <?= htmlspecialchars($magang['catatan_admin']) ?>
                    </div>
                <?php endif; ?>

                <form class="internship-form" id="formAjukan">
                    <input type="hidden" name="action" value="ajukan">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nama Perusahaan / Hotel</label>
                            <input type="text" name="nama_tempat" placeholder="Masukkan nama hotel" required>
                            <small>Pastikan nama perusahaan sesuai ejaan resmi</small>
                        </div>
                        <div class="form-group">
                            <label>Alamat Perusahaan</label>
                            <input type="text" name="alamat_tempat" placeholder="Masukkan alamat lengkap" required>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Ajukan Permohonan</button>
                    </div>
                </form>
            </section>

            <script>
                document.getElementById('formAjukan')?.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    fetch('../../backend/magangSiswa_end.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        alert(data.message);
                        if (data.status === 'success') location.reload();
                    });
                });
            </script>

        <?php elseif ($current_step == 2): ?>
            <!-- STATE 2: MENUNGGU VERIFIKASI -->
            <section class="card content-card text-center">
                <div class="card-header-main">
                    <h2>Pengajuan Magang (On-the-Job Training)</h2>
                </div>
                <div class="alert alert-warning-large">
                    <div class="large-icon"><i class="fas fa-hourglass-half"></i></div>
                    <div class="alert-content">
                        <h3>Menunggu Verifikasi</h3>
                        <p>Pengajuan Anda sedang diproses oleh admin akademik. Mohon menunggu 1-3 hari kerja.</p>
                    </div>
                </div>
                <div class="detail-grid" style="margin-top: 20px;">
                    <div class="detail-info">
                        <h3>Detail Pengajuan Anda</h3>
                        <div class="info-row">
                            <span class="label">Tempat Magang</span>
                            <span class="value"><?= htmlspecialchars($magang['nama_tempat'] ?? '-') ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label">Alamat</span>
                            <span class="value"><?= htmlspecialchars($magang['alamat_tempat'] ?? '-') ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label">Status Pengajuan</span>
                            <span class="value" style="text-transform: capitalize; color: #d97706; font-weight: 600;"><?= ucfirst(str_replace('_', ' ', $magang['status_pengajuan'])) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label">Status Verifikasi</span>
                            <span class="value" style="text-transform: capitalize; color: #d97706; font-weight: 600;"><?= ucfirst($magang['status_verifikasi']) ?></span>
                        </div>
                    </div>
                </div>
            </section>

        <?php elseif ($current_step == 3): ?>
            <!-- STATE 3: MAGANG AKTIF -->
            <section class="card content-card">
                <div class="card-header-main">
                    <h2>Magang Aktif</h2>
                </div>
                <div class="alert alert-info-large">
                    <div class="large-icon"><i class="fas fa-briefcase"></i></div>
                    <div class="alert-content">
                        <h3>Magang Sedang Berlangsung</h3>
                        <p>Selamat melaksanakan magang! Pastikan Anda mematuhi semua aturan di tempat magang.</p>
                    </div>
                </div>

                <div class="detail-grid">
                    <div class="detail-info">
                        <h3>Detail Magang</h3>
                        <div class="info-row">
                            <span class="label">Industri/Hotel</span>
                            <span class="value"><?= htmlspecialchars($magang['nama_tempat']) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label">Alamat</span>
                            <span class="value"><?= htmlspecialchars($magang['alamat_tempat']) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label">Status</span>
                            <span class="value" style="text-transform: capitalize; color: #10b981; font-weight: 600;">Berlangsung</span>
                        </div>
                    </div>
                    <div class="detail-docs">
                        <h3>Informasi</h3>
                        <div class="doc-box">
                            <p style="font-size: 14px; color: #64748b; line-height: 1.6;">
                                <i class="fas fa-info-circle" style="color: #3b82f6;"></i>
                                Jika ada kendala selama magang, segera hubungi admin akademik untuk mendapatkan bantuan.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

        <?php elseif ($current_step >= 6): ?>
            <!-- STATE 6: SELESAI -->
            <section class="card certificate-card">
                <div class="certificate-header">
                    <div class="trophy-icon"><i class="fas fa-star"></i></div>
                    <h2>Program Magang Selesai!</h2>
                    <p>Selamat! Anda telah berhasil menyelesaikan program magang di <strong><?= htmlspecialchars($magang['nama_tempat'] ?? '-') ?></strong>.</p>
                </div>

                <div class="detail-grid" style="margin-top: 20px;">
                    <div class="detail-info">
                        <h3>Ringkasan Magang</h3>
                        <div class="info-row">
                            <span class="label">Tempat Magang</span>
                            <span class="value"><?= htmlspecialchars($magang['nama_tempat'] ?? '-') ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label">Alamat</span>
                            <span class="value"><?= htmlspecialchars($magang['alamat_tempat'] ?? '-') ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label">Status</span>
                            <span class="value" style="text-transform: capitalize; color: #10b981; font-weight: 600;">Selesai</span>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    </main>



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
