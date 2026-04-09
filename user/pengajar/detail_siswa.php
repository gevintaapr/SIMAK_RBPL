<?php
session_start();
$nama_siswa = "Alexander Wibowo";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Siswa - Pengajar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../style/dashboard_pengajar.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../style/data_siswa_style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../style/popup_logout.css">
</head>
<body>

    <!-- ===== SIDEBAR ===== -->
    <aside class="sidebar collapsed" id="sidebar">
        <div class="sidebar-header">
            <span class="sidebar-logo">HCTS</span>
        </div>
        <nav class="sidebar-nav">
            <a href="dashboard_pengajar.php" class="sidebar-link">
                <i class="fa-solid fa-gauge-high"></i>
                <span>Dashboard</span>
            </a>
            <a href="data_siswa.php" class="sidebar-link active">
                <i class="fa-solid fa-users"></i>
                <span>Data Siswa</span>
            </a>
            <div class="sidebar-dropdown">
                <a href="#" class="sidebar-link dropdown-toggle" id="evaluasiToggle">
                    <i class="fa-solid fa-clipboard-list"></i>
                    <span>Evaluasi</span>
                    <i class="fa-solid fa-chevron-down chevron-icon"></i>
                </a>
                <div class="dropdown-menu" id="evaluasiMenu">
                    <a href="input_penilaian.php" class="dropdown-link">Input Penilaian</a>
                    <a href="riwayat_evaluasi.php" class="dropdown-link">Riwayat Evaluasi</a>
                </div>
            </div>
        </nav>
        <div class="sidebar-footer">
            <a href="#" onclick="showLogoutPopup(event)" class="sidebar-link sidebar-logout">
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
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <span class="navbar-brand">HCTS Admin Center</span>
            </div>
            <div class="navbar-right">
                <button class="notif-btn" id="notifBtn" aria-label="Notifikasi">
                    <i class="fa-regular fa-bell"></i>
                    <span class="notif-badge">3</span>
                </button>
                <div class="admin-profile">
                    <div class="admin-avatar" style="background:#113A71; color:white;">AD</div>
                    <div class="admin-info">
                        <span class="admin-name">Pengajar</span>
                        <span class="admin-role">Instruktur</span>
                    </div>
                    <i class="fa-solid fa-chevron-down admin-chevron"></i>
                </div>
            </div>
        </header>

        <!-- ===== PAGE CONTENT ===== -->
        <main class="page-content">

            <!-- Hero Banner -->
            <section class="ds-banner detail-banner">
                <p class="breadcrumb">Akademik &gt; Detail Siswa</p>
                <h1 class="ds-title">Detail Siswa: <?= htmlspecialchars($nama_siswa) ?></h1>
            </section>

            <!-- Content Area -->
            <section class="ds-content-container ds-detail-container">
                
                <!-- CARD 1: Informasi Pribadi -->
                <div class="ds-card p-4">
                    <h2 class="ds-card-title">Informasi Pribadi</h2>
                    <hr class="ds-divider">
                    
                    <div class="info-pribadi-grid">
                        <div class="info-col">
                            <div class="info-item">
                                <label>Nama Lengkap</label>
                                <strong>Alexander Wibowo</strong>
                            </div>
                            <div class="info-item">
                                <label>Nomor Whatsapp</label>
                                <strong>+62 813 2345 0987</strong>
                            </div>
                            <div class="info-item">
                                <label>Asal Sekolah</label>
                                <strong>SMK Pariwisata Bahari</strong>
                            </div>
                            <div class="info-item">
                                <label>Alamat Lengkap</label>
                                <strong>Jalan Jendral Sudirman, No. 3 Sragen, Solo, Indonesia</strong>
                            </div>
                        </div>
                        <div class="info-col">
                            <div class="info-item">
                                <label>Email</label>
                                <strong>ocean.098@gmail.com</strong>
                            </div>
                            <div class="info-item">
                                <label>Tanggal Lahir</label>
                                <strong>11 Maret 2002</strong>
                            </div>
                            <div class="info-item">
                                <label>Posisi-Program Pilihan</label>
                                <strong>Hotel - F&B Service</strong>
                            </div>
                        </div>
                        <div class="photo-col">
                            <div class="photo-wrapper">
                                <!-- Dummy silhouette or photo -->
                                <img src="../../assets/student_dummy.jpg" alt="Photo" onerror="this.src='https://via.placeholder.com/150x200?text=Photo'">
                            </div>
                            <div class="badge-magang">
                                <i class="fa-regular fa-calendar-check"></i> MAGANG
                            </div>
                            <p class="id-siswa">ID Siswa: HC124</p>
                        </div>
                    </div>
                </div>

                <!-- CARD 2: Jadwal Kelas -->
                <div class="ds-card p-4 mt-4">
                    <div class="jadwal-header">
                        <h2>Jadwal</h2>
                        <div class="jadwal-divider"></div>
                        <h2 class="regular-class-title">Kelas Reguler</h2>
                    </div>
                    <hr class="ds-divider">
                    
                    <div class="ds-table-responsive">
                        <table class="ds-table ds-table-jadwal">
                            <thead>
                                <tr>
                                    <th>HARI</th>
                                    <th>JAM</th>
                                    <th>MATERI</th>
                                    <th>PENGAJAR</th>
                                    <th>RUANGAN</th>
                                    <th>PROGRAM</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Senin</td>
                                    <td>09:00 - 11:00</td>
                                    <td>English for Hospitality</td>
                                    <td>Mr. Simon</td>
                                    <td>Ruang A2</td>
                                    <td>Bar</td>
                                </tr>
                                <tr class="bg-gray">
                                    <td>Senin</td>
                                    <td>09:00 - 11:00</td>
                                    <td>English for Hospitality</td>
                                    <td>Mr. Simon</td>
                                    <td>Ruang A2</td>
                                    <td>Bar</td>
                                </tr>
                                <tr>
                                    <td>Senin</td>
                                    <td>09:00 - 11:00</td>
                                    <td>English for Hospitality</td>
                                    <td>Mr. Simon</td>
                                    <td>Ruang A2</td>
                                    <td>Bar</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- CARD 3 & 4: Evaluasi dan Magang -->
                <div class="grid-2-col mt-4">
                    <!-- Evaluasi Akhir -->
                    <div class="ds-card p-4">
                        <h2 class="ds-card-title mb-3">Evaluasi Akhir</h2>
                        
                        <div class="eval-box">
                            <h3 class="eval-box-title">Laporan Evaluasi</h3>
                            <div class="eval-row">
                                <span>Tanggal Evaluasi :</span>
                                <strong>01/04/2025</strong>
                            </div>
                            <div class="eval-row">
                                <span>Penguji:</span>
                                <strong>Mr. Simon</strong>
                            </div>
                            <div class="eval-row">
                                <span>Status:</span>
                                <strong>Disetujui</strong>
                            </div>
                            
                            <div class="eval-actions">
                                <a href="#" class="btn-lihat"><i class="fa-regular fa-eye"></i> Lihat</a>
                                <button class="btn-validasi disabled">Validasi</button>
                            </div>
                        </div>
                    </div>

                    <!-- Magang -->
                    <div class="ds-card p-4">
                        <h2 class="ds-card-title mb-3">Magang</h2>
                        
                        <div class="eval-box mb-3">
                            <h3 class="eval-box-title" style="color: #0e4c92;">Pengajuan Magang</h3>
                            <div class="eval-row">
                                <span>Tanggal Evaluasi :</span>
                                <strong>15/06/2025</strong>
                            </div>
                            <div class="eval-row">
                                <span>Status:</span>
                                <strong>Disetujui</strong>
                            </div>
                            
                            <div class="eval-actions">
                                <a href="#" class="btn-lihat"><i class="fa-regular fa-eye"></i> Lihat</a>
                                <button class="btn-validasi disabled">Validasi</button>
                            </div>
                        </div>

                        <div class="eval-box">
                            <h3 class="eval-box-title" style="color: #0e4c92;">Laporan Akhir</h3>
                            <div class="eval-row">
                                <span>Tanggal Upload :</span>
                                <strong>15/12/2025</strong>
                            </div>
                            
                            <div class="eval-actions">
                                <a href="#" class="btn-lihat"><i class="fa-regular fa-eye"></i> Lihat</a>
                                <button class="btn-validasi disabled">Validasi</button>
                            </div>
                        </div>
                    </div>
                </div>

            </section>

        </main>

        <!-- ===== FOOTER ===== -->
        <footer class="footer">
            <div class="footer-top">
                <div class="footer-col footer-brand-col">
                    <h3 class="footer-brand">HCTS</h3>
                    <p class="footer-desc">Sekolah pelatihan internasional terkemuka untuk karier di bidang perhotelan dan kapal pesiar.</p>
                </div>
                <!-- simplified footer since it's just ui mockup -->
            </div>
            <div class="footer-bottom">
                <p class="footer-copy">&copy; 2025 HCTS International. All rights reserved.</p>
            </div>
        </footer>

    </div>

    <!-- Logout Popup ... ->
    <script>...</script>
    -->
</body>
</html>
