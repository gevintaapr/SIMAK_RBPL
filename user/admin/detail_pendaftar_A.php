<?php
// PHP logic if needed
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
                        <h1 class="banner-title">Detail Pendaftar: Alexander Wibowo</h1>
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
                                    <span class="info-value">Alexander Wibowo</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Email</span>
                                    <span class="info-value">alex.bow23@gmail.com</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Nomor Whatsapp</span>
                                    <span class="info-value">+62 812 3456 7890</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Tanggal Lahir</span>
                                    <span class="info-value">22 Oktober 2004</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Asal Sekolah</span>
                                    <span class="info-value">SMA Pariwisata Jaya</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Posisi-Program Pilihan</span>
                                    <span class="info-value">Hotel-F&amp;B Service</span>
                                </div>
                                <div class="info-item full-width">
                                    <span class="info-label">Alamat Lengkap</span>
                                    <span class="info-value">Jl. Samudra Biru No. 45, Kuta, Bali, Indonesia</span>
                                </div>
                                <div class="info-item full-width mt-2">
                                    <span class="info-label">Status Pendaftaran:</span>
                                    <span class="badge badge-status-approved">Disetujui Pimpinan</span>
                                </div>
                            </div>
                        </div>
                        <div class="photo-content">
                            <img src="https://ui-avatars.com/api/?name=Alexander+Wibowo&background=0D8ABC&color=fff&size=200" alt="Foto Calon Siswa" class="student-photo">
                            <p class="photo-title">Foto Calon Siswa</p>
                            <p class="photo-date">Tanggal Upload: 10/10/2024</p>
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
                            <div class="doc-icon">
                                <i class="fa-regular fa-file-lines"></i>
                            </div>
                            <div class="doc-info">
                                <h3 class="doc-name">Surat Pernyataan</h3>
                                <p class="doc-filename">surat_pernyataan1234.pdf</p>
                                <div class="doc-actions">
                                    <button class="btn-doc btn-view"><i class="fa-regular fa-eye"></i> Lihat</button>
                                    <button class="btn-doc btn-download"><i class="fa-solid fa-download"></i> Unduh</button>
                                </div>
                            </div>
                        </div>
                        <!-- KTP -->
                        <div class="doc-card">
                            <div class="doc-icon">
                                <i class="fa-regular fa-id-card"></i>
                            </div>
                            <div class="doc-info">
                                <h3 class="doc-name">KTP</h3>
                                <p class="doc-filename">ktp_alex.pdf</p>
                                <div class="doc-actions">
                                    <button class="btn-doc btn-view"><i class="fa-regular fa-eye"></i> Lihat</button>
                                    <button class="btn-doc btn-download"><i class="fa-solid fa-download"></i> Unduh</button>
                                </div>
                            </div>
                        </div>
                        <!-- Ijazah -->
                        <div class="doc-card">
                            <div class="doc-icon">
                                <i class="fa-solid fa-graduation-cap"></i>
                            </div>
                            <div class="doc-info">
                                <h3 class="doc-name">Ijazah</h3>
                                <p class="doc-filename">ijazah_alex.pdf</p>
                                <div class="doc-actions">
                                    <button class="btn-doc btn-view"><i class="fa-regular fa-eye"></i> Lihat</button>
                                    <button class="btn-doc btn-download"><i class="fa-solid fa-download"></i> Unduh</button>
                                </div>
                            </div>
                        </div>
                        <!-- Bukti Pembayaran -->
                        <div class="doc-card">
                            <div class="doc-icon">
                                <i class="fa-solid fa-file-invoice-dollar"></i>
                            </div>
                            <div class="doc-info">
                                <h3 class="doc-name">Bukti Pembayaran</h3>
                                <p class="doc-filename">Pembayaran_Pendaftaran.pdf</p>
                                <div class="doc-actions">
                                    <button class="btn-doc btn-view"><i class="fa-regular fa-eye"></i> Lihat</button>
                                    <button class="btn-doc btn-download"><i class="fa-solid fa-download"></i> Unduh</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Verify Button -->
                <div class="action-section">
                    <button class="btn-verify" onclick="openPopupAdminVerif()">Verifikasi</button>
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
            document.getElementById('popupAdminVerif').style.display = 'flex';
        }
        function closePopupAdminVerif() {
            document.getElementById('popupAdminVerif').style.display = 'none';
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