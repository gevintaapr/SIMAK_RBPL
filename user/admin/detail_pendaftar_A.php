<?php
require_once __DIR__ . '/../../config/config.php';

$id = $_GET['id'] ?? '';
$query = mysqli_query($conn, "SELECT * FROM pendaftaran WHERE id_pendaftaran = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: pendaftaran_admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pendaftar - HCTS Admin Center</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;800&family=Poppins:wght@400;500;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../style/detail_pendaftar_A.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../style/popup_admin.css?v=<?= time() ?>">
</head>
<body>

    <!-- ===== SIDEBAR ===== -->
    <aside class="sidebar collapsed" id="sidebar">
        <div class="sidebar-header">
            <span class="sidebar-logo">HCTS</span>
        </div>
        <nav class="sidebar-nav">
            <a href="dashboard_admin.php" class="sidebar-link">
                <i class="fa-solid fa-gauge-high"></i>
                <span>Dashboard</span>
            </a>
            <a href="pendaftaran_admin.php" class="sidebar-link active">
                <i class="fa-solid fa-file-signature"></i>
                <span>Pendaftaran</span>
            </a>
            <a href="#" class="sidebar-link">
                <i class="fa-solid fa-briefcase"></i>
                <span>Magang (OJT)</span>
            </a>
            <a href="akademik_admin.php" class="sidebar-link">
                <i class="fa-solid fa-book"></i>
                <span>Akademik</span>
            </a>
            <a href="#" class="sidebar-link">
                <i class="fa-solid fa-globe"></i>
                <span>Program Taiwan</span>
            </a>
            <a href="#" class="sidebar-link">
                <i class="fa-solid fa-users-gear"></i>
                <span>Manajemen Pengguna</span>
            </a>
            <a href="#" class="sidebar-link">
                <i class="fa-solid fa-gear"></i>
                <span>Pengaturan</span>
            </a>
        </nav>
        <div class="sidebar-footer">
            <a href="#" class="sidebar-link sidebar-logout">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Logout</span>
            </a>
        </div>
    </aside>

    <!-- ===== MAIN WRAPPER ===== -->
    <div class="main-wrapper sidebar-collapsed" id="mainWrapper">
        
        <!-- ===== NAVBAR ===== -->
        <header class="navbar">
            <div class="navbar-left">
                <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle Sidebar">
                    <span></span><span></span><span></span>
                </button>
                <span class="navbar-brand">HCTS Admin Center</span>
            </div>
            <div class="navbar-right">
                <button class="notif-btn" id="notifBtn" aria-label="Notifikasi">
                    <i class="fa-regular fa-bell"></i>
                    <span class="notif-badge">5</span>
                </button>
                <div class="admin-profile">
                    <div class="admin-avatar">AD</div>
                    <div class="admin-info">
                        <span class="admin-name">Admin</span>
                        <span class="admin-role">Super Admin</span>
                    </div>
                    <i class="fa-solid fa-chevron-down admin-chevron"></i>
                </div>
            </div>
        </header>

        <!-- ===== PAGE CONTENT ===== -->
        <main class="page-content">

            <!-- Hero / Banner -->
            <section class="dashboard-banner">
                <div class="banner-content">
                    <div class="banner-text-wrapper">
                        <p class="breadcrumb">Pendaftaran &gt; Detail Pendaftar</p>
                        <h1 class="banner-title">Detail Pendaftar: <?= htmlspecialchars($data['nama_cs']) ?></h1>
                    </div>
                </div>
            </section>

            <!-- Detail Section -->
            <section class="detail-section">
                <!-- Card 1: Informasi Pribadi -->
                <div class="detail-card">
                    <h2 class="card-title">Informasi Pribadi</h2>
                    <hr class="card-divider">
                    <div class="card-body">
                        <div class="info-content">
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="info-label">Nama Lengkap</span>
                                    <span class="info-value"><?= htmlspecialchars($data['nama_cs']) ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Email</span>
                                    <span class="info-value"><?= htmlspecialchars($data['email']) ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Nomor Whatsapp</span>
                                    <span class="info-value"><?= htmlspecialchars($data['no_wa']) ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Tanggal Lahir</span>
                                    <span class="info-value"><?= htmlspecialchars($data['tanggal_lahir']) ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Asal Sekolah</span>
                                    <span class="info-value"><?= htmlspecialchars($data['asal_sekolah'] ?? '-') ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Posisi-Program Pilihan</span>
                                    <span class="info-value"><?= htmlspecialchars($data['program']) ?></span>
                                </div>
                                <div class="info-item full-width">
                                    <span class="info-label">Alamat Lengkap</span>
                                    <span class="info-value"><?= htmlspecialchars($data['alamat']) ?></span>
                                </div>
                                <div class="info-item full-width mt-2">
                                    <span class="info-label">Status Pendaftaran:</span>
                                    <?php if ($data['status_approval'] == 1 || $data['status_approval'] === 'disetujui'): ?>
                                        <span class="badge badge-status-approved" style="background: #D1FAE5; color: #059669;">Disetujui Pimpinan</span>
                                    <?php elseif ($data['status_approval'] == 'ditolak'): ?>
                                        <span class="badge badge-status-rejected" style="background: #FEE2E2; color: #DC2626;">Ditolak</span>
                                    <?php else: ?>
                                        <span class="badge badge-status-pending" style="background: #DBEAFE; color: #2563EB;">Menunggu Verifikasi</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="photo-content">
                            <img src="../../<?= htmlspecialchars($data['foto_siswa']) ?>" alt="Foto Calon Siswa" class="student-photo" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($data['nama_cs']) ?>&background=0D8ABC&color=fff&size=200'">
                            <p class="photo-title">Foto Calon Siswa</p>
                            <p class="photo-date">Tanggal Upload: <?= isset($data['created_at']) ? date('d/m/Y', strtotime($data['created_at'])) : '-' ?></p>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Dokumen Pendaftaran -->
                <div class="detail-card">
                    <h2 class="card-title">Dokumen Pendaftaran</h2>
                    <hr class="card-divider">
                    <div class="doc-grid">
                        <!-- Surat Pernyataan -->
                        <div class="doc-card">
                            <div class="doc-icon"><i class="fa-regular fa-file-lines"></i></div>
                            <div class="doc-info">
                                <h3 class="doc-name">Surat Pernyataan</h3>
                                <p class="doc-filename"><?= basename($data['surat_pernyataan'] ?? 'file.pdf') ?></p>
                                <div class="doc-actions">
                                    <a href="../../<?= htmlspecialchars($data['surat_pernyataan']) ?>" target="_blank" class="btn-doc btn-view"><i class="fa-regular fa-eye"></i> Lihat</a>
                                </div>
                            </div>
                        </div>
                        <!-- KTP -->
                        <div class="doc-card">
                            <div class="doc-icon"><i class="fa-regular fa-id-card"></i></div>
                            <div class="doc-info">
                                <h3 class="doc-name">KTP</h3>
                                <p class="doc-filename"><?= basename($data['ktp'] ?? 'file.pdf') ?></p>
                                <div class="doc-actions">
                                    <a href="../../<?= htmlspecialchars($data['ktp']) ?>" target="_blank" class="btn-doc btn-view"><i class="fa-regular fa-eye"></i> Lihat</a>
                                </div>
                            </div>
                        </div>
                        <!-- Ijazah -->
                        <div class="doc-card">
                            <div class="doc-icon"><i class="fa-solid fa-graduation-cap"></i></div>
                            <div class="doc-info">
                                <h3 class="doc-name">Ijazah</h3>
                                <p class="doc-filename"><?= basename($data['ijazah'] ?? 'file.pdf') ?></p>
                                <div class="doc-actions">
                                    <a href="../../<?= htmlspecialchars($data['ijazah']) ?>" target="_blank" class="btn-doc btn-view"><i class="fa-regular fa-eye"></i> Lihat</a>
                                </div>
                            </div>
                        </div>
                        <!-- Bukti Pembayaran -->
                        <div class="doc-card">
                            <div class="doc-icon"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                            <div class="doc-info">
                                <h3 class="doc-name">Bukti Pembayaran</h3>
                                <p class="doc-filename"><?= basename($data['bukti_pendaftaran'] ?? 'file.pdf') ?></p>
                                <div class="doc-actions">
                                    <a href="../../<?= htmlspecialchars($data['bukti_pendaftaran']) ?>" target="_blank" class="btn-doc btn-view"><i class="fa-regular fa-eye"></i> Lihat</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="action-section">
                    <?php if (($data['status_berkas'] ?? '') === 'valid'): ?>
                        <button class="btn-verify" disabled style="background: #99A1AF; cursor: not-allowed; opacity: 0.7;">
                            <i class="fa-solid fa-check-double"></i> Terverifikasi
                        </button>
                    <?php else: ?>
                        <button class="btn-verify" onclick="openPopupAdminVerif()">Verifikasi</button>
                    <?php endif; ?>
                </div>
            </section>

        </main>.



    </div>

    <!-- Popup Verifikasi -->
    <div id="popupAdminVerif" class="popup-overlay" style="display: none;">
        <div class="popup-admin-box">
            <button class="popup-admin-close" onclick="closePopupAdminVerif()"><i class="fa-solid fa-xmark"></i></button>
            <h2 class="popup-admin-title">Verifikasi Berhasil!</h2>
            <hr class="popup-admin-divider">
            <div class="verif-list">
                <div class="verif-item">
                    <div class="verif-icon"><i class="fa-solid fa-check"></i></div>
                    <span>Status Pendaftaran Berhasil Diperbarui</span>
                </div>
                <div class="verif-item">
                    <div class="verif-icon"><i class="fa-solid fa-check"></i></div>
                    <span>Data Siswa Berhasil diKirim ke Pengajar</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openPopupAdminVerif() {
            const idPendaftaran = "<?= $data['id_pendaftaran'] ?>";
            
            // Kirim data ke backend untuk verifikasi
            const formData = new FormData();
            formData.append('action', 'verifikasi_berkas');
            formData.append('id_pendaftaran', idPendaftaran);
            formData.append('status_berkas', 'valid');

            fetch('../../backend/pendaftaranAdmin_end.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    document.getElementById('popupAdminVerif').style.display = 'flex';
                } else {
                    alert('Gagal melakukan verifikasi: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan sistem.');
            });
        }
        function closePopupAdminVerif() {
            document.getElementById('popupAdminVerif').style.display = 'none';
            window.location.href = 'pendaftaran_admin.php';
        }

        const sidebar = document.getElementById('sidebar');
        const mainWrapper = document.getElementById('mainWrapper');
        const toggleBtn = document.getElementById('sidebarToggle');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            mainWrapper.classList.toggle('sidebar-collapsed');
        });
    </script>
</body>
</html>