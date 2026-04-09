<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pimpinan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;800&family=Poppins:wght@400;500;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../style/dashboard_pimpinan.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../style/popup_logout.css">
    <link rel="stylesheet" href="../../style/popup_pimpinan.css?v=<?= time() ?>">
</head>
<body>

    <!-- ===== SIDEBAR ===== -->
    <aside class="sidebar collapsed" id="sidebar">
        <div class="sidebar-header">
            <span class="sidebar-logo">HCTS</span>
        </div>
        <nav class="sidebar-nav">
            <a href="dashboard_pimpinan.php" class="sidebar-link active">
                <i class="fa-solid fa-gauge-high"></i>
                <span>Dashboard</span>
            </a>
            <a href="#" class="sidebar-link">
                <i class="fa-solid fa-users"></i>
                <span>Daftar Siswa</span>
            </a>
            <a href="approval.php" class="sidebar-link">
                <i class="fa-solid fa-check-double"></i>
                <span>Approval</span>
            </a>
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
                <span class="navbar-brand">HCTS Executive</span>
            </div>
            <div class="navbar-right">
                <button class="notif-btn" id="notifBtn" aria-label="Notifikasi">
                    <i class="fa-regular fa-bell"></i>
                    <span class="notif-badge">3</span>
                </button>
                <div class="admin-profile">
                    <div class="admin-avatar">GN</div>
                    <div class="admin-info">
                        <span class="admin-name">Pimpinan</span>
                        <span class="admin-role">Direktur Utama</span>
                    </div>
                    <i class="fa-solid fa-chevron-down admin-chevron"></i>
                </div>
            </div>
        </header>

        <!-- ===== PAGE CONTENT ===== -->
        <main class="page-content">

            <!-- Hero / Dashboard Banner -->
            <section class="dashboard-banner">
                <div class="banner-content">
                    <h1 class="banner-title">Dashboard</h1>
                </div>
            </section>

            <!-- ===== STAT CARDS ===== -->
            <section class="stats-section pimpinan-stats">
                <div class="stat-card">
                    <div class="stat-icon-wrap">
                        <i class="fa-regular fa-user"></i>
                    </div>
                    <div class="stat-body">
                        <p class="stat-number">12</p>
                        <p class="stat-label">Pendaftaran Menunggu<br>Approval</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon-wrap">
                        <i class="fa-solid fa-book-open-reader"></i>
                    </div>
                    <div class="stat-body">
                        <p class="stat-number">3</p>
                        <p class="stat-label">Evaluasi Menunggu<br>Persetujuan</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon-wrap">
                        <i class="fa-solid fa-briefcase"></i>
                    </div>
                    <div class="stat-body">
                        <p class="stat-number">5</p>
                        <p class="stat-label">Pengajuan Magang<br>Menunggu Persetujuan</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon-wrap">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <div class="stat-body">
                        <p class="stat-number">720</p>
                        <p class="stat-label">Total Siswa Aktif</p>
                    </div>
                </div>
            </section>

            <!-- ===== BOTTOM DATA SECTION ===== -->
            <section class="bottom-section full-width">
                <!-- Ringkasan Data Akademik -->
                <div class="card card-table">
                    <div class="card-header border-none">
                        <h2 class="card-title">Ringkasan Data Akademik</h2>
                        <div class="table-controls">
                            <div class="filter-group">
                                <select class="filter-select" aria-label="Filter Program">
                                    <option value="">Program</option>
                                    <option value="hotel">Hotel F&B Service</option>
                                    <option value="cruise_kadet">Cruise Ship Desk Kadet</option>
                                    <option value="cruise_culinary">Cruise Ship Culinary</option>
                                </select>
                                <select class="filter-select" aria-label="Filter Status">
                                    <option value="">Status</option>
                                    <option value="disetujui">Disetujui</option>
                                    <option value="menunggu">Menunggu</option>
                                    <option value="ditolak">Ditolak</option>
                                </select>
                            </div>
                            <div class="search-box">
                                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                                <input type="text" placeholder="Cari Nama Siswa" class="search-input">
                                <button class="clear-search"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>NAMA</th>
                                        <th>PROGRAM</th>
                                        <th>JENIS APPROVAL</th>
                                        <th>STATUS</th>
                                        <th>AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Alexander Wibowo</td>
                                        <td>Cruise Ship Desk Kadet</td>
                                        <td>Pendaftaran</td>
                                        <td><span class="badge badge-success">Disetujui</span></td>
                                        <td><a href="approval_detail_pendaftaran.php" class="btn-detail">Detail</a></td>
                                    </tr>
                                    <tr>
                                        <td>Jessica Tan</td>
                                        <td>Hotel F&B Service</td>
                                        <td>Magang</td>
                                        <td><span class="badge badge-warning">Menunggu</span></td>
                                        <td><a href="#" class="btn-detail" onclick="openPopupMagang(event)">Detail</a></td>
                                    </tr>
                                    <tr>
                                        <td>Maria Gomez</td>
                                        <td>Cruise Ship Culinary</td>
                                        <td>Evaluasi</td>
                                        <td><span class="badge badge-danger">Ditolak</span></td>
                                        <td><a href="#" class="btn-detail" onclick="openPopupEvaluasi(event)">Detail</a></td>
                                    </tr>
                                </tbody>
                            </table>
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
                    <p class="footer-desc">Leading international training school for hospital, cruise ship, hotel &amp; culinary arts.</p>
                    <div class="footer-socials">
                        <a href="#" class="social-btn" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="social-btn" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="social-btn" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
                        <a href="#" class="social-btn" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                    </div>
                </div>

                <div class="footer-col">
                    <h4 class="footer-heading">Aksi Cepat</h4>
                    <ul class="footer-links">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Our Programs</a></li>
                        <li><a href="#">Admission Process</a></li>
                        <li><a href="#">Career Opportunities</a></li>
                        <li><a href="#">Student Stories</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4 class="footer-heading">Program Kami</h4>
                    <ul class="footer-links">
                        <li><a href="#">Hotel Management</a></li>
                        <li><a href="#">Cruise Ship Operations</a></li>
                        <li><a href="#">Culinary Arts</a></li>
                        <li><a href="#">Hospitality Services</a></li>
                        <li><a href="#">Tourism Management</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4 class="footer-heading">Kontak Kami</h4>
                    <ul class="footer-contact">
                        <li>
                            <i class="fa-solid fa-location-dot"></i>
                            <span>123 Maritime Avenue, Harbor District, HD 12345</span>
                        </li>
                        <li>
                            <i class="fa-solid fa-phone"></i>
                            <span>+1 (555) 123-4567</span>
                        </li>
                        <li>
                            <i class="fa-regular fa-envelope"></i>
                            <span>info@hcts.edu</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p class="footer-copy">&copy; 2025 HCTS International. All rights reserved.</p>
                <div class="footer-legal">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                    <a href="#">Cookie Policy</a>
                </div>
            </div>
        </footer>

    </div>

    <!-- Popup Detail Evaluasi -->
    <div id="popupEvaluasi" class="popup-overlay" style="display: none; align-items: center; justify-content: center;">
        <div class="popup-pimpinan">
            <div class="popup-header-pimpinan">
                <h2 class="popup-title-pimpinan">Detail Evaluasi</h2>
                <button class="btn-close-box" onclick="closePopupEvaluasi()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <div class="popup-section-pimpinan">
                <h3 class="section-title">Identitas Siswa</h3>
                <div class="grid-identitas">
                    <div class="identitas-col">
                        <div class="identitas-row"><span>ID Siswa</span> <strong>HC123</strong></div>
                        <div class="identitas-row"><span>Nama Lengkap:</span> <strong>Alexander Wibowo</strong></div>
                    </div>
                    <div class="identitas-col">
                        <div class="identitas-row"><span>Program:</span> <strong>F&amp;B Service</strong></div>
                        <div class="identitas-row"><span>Periode:</span> <strong>1-2025</strong></div>
                    </div>
                </div>
            </div>

            <div class="popup-section-pimpinan">
                <h3 class="section-title">Hasil Evaluasi <span class="section-badge">Menunggu Persetujuan</span></h3>
                <table class="table-evaluasi">
                    <thead>
                        <tr>
                            <th>Mata Pelajaran (Subject)</th>
                            <th style="text-align:center;">Nilai (0-100)</th>
                            <th style="text-align:center;">Grade</th>
                            <th>Evaluasi Pengajar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>English Basic</td>
                            <td align="center"><input type="text" value="88" class="eval-input eval-box-small" readonly></td>
                            <td align="center"><strong>A</strong></td>
                            <td><input type="text" value="Memiliki kemampuan dasar bahasa In..." class="eval-input" readonly></td>
                        </tr>
                        <tr>
                            <td>Food &amp; Beverage Service</td>
                            <td align="center"><input type="text" value="89" class="eval-input eval-box-small" readonly></td>
                            <td align="center"><strong>A</strong></td>
                            <td><input type="text" value="Menguasai standar pelayanan F&amp;B den..." class="eval-input" readonly></td>
                        </tr>
                        <tr>
                            <td>Housekeeping</td>
                            <td align="center"><input type="text" value="82" class="eval-input eval-box-small" readonly></td>
                            <td align="center"><strong>B</strong></td>
                            <td><input type="text" value="Memahami prosedur dasar housekeepi..." class="eval-input" readonly></td>
                        </tr>
                    </tbody>
                </table>
                <p style="font-size: 0.85rem; color: #666; margin-top:0.5rem;">*scroll to see full list</p>
            </div>

            <button class="btn-setuju-besar" onclick="closePopupEvaluasi()">Setujui Hasil Evaluasi</button>
        </div>
    </div>

    <!-- Popup Detail Magang -->
    <div id="popupMagang" class="popup-overlay" style="display: none; align-items: center; justify-content: center;">
        <div class="popup-pimpinan">
            <div class="popup-header-pimpinan">
                <h2 class="popup-title-pimpinan">Detail Magang</h2>
                <button class="btn-close-box" onclick="closePopupMagang()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <div class="popup-section-pimpinan">
                <h3 class="section-title">Identitas Siswa</h3>
                <div class="grid-identitas">
                    <div class="identitas-col">
                        <div class="identitas-row"><span>ID Siswa</span> <strong>HC123</strong></div>
                        <div class="identitas-row"><span>Nama Lengkap:</span> <strong>Alexander Wibowo</strong></div>
                    </div>
                    <div class="identitas-col">
                        <div class="identitas-row"><span>Program:</span> <strong>F&amp;B Service</strong></div>
                        <div class="identitas-row"><span>Periode:</span> <strong>1-2025</strong></div>
                    </div>
                </div>
            </div>

            <div class="magang-grid">
                <div class="magang-left">
                    <div class="popup-section-pimpinan">
                        <h3 class="section-title">Detail Pengajuan <span class="section-badge">Menunggu Persetujuan</span></h3>
                        <div class="detail-pengajuan-row"><span>Nama Perusahaan/Hotel:</span> <strong>Msc Cruises</strong></div>
                        <div class="detail-pengajuan-row"><span>Posisi/Departemen:</span> <strong>Front Office - Kapal Pesiar</strong></div>
                        <div class="detail-pengajuan-row"><span>Lokasi:</span> <strong>Italy</strong></div>
                        <div class="detail-pengajuan-row"><span>Periode Pelaksanaan:</span> <strong>Juli 2025 - Desember 2025</strong></div>
                        
                        <div class="pengajuan-actions">
                            <button class="btn-tolak" onclick="closePopupMagang()">Tolak Pengajuan Magang</button>
                            <button class="btn-setuju" onclick="closePopupMagang()">Setujui Pengajuan Magang</button>
                        </div>
                    </div>

                    <div class="popup-section-pimpinan">
                        <h3 class="section-title">Laporan Kegiatan Harian</h3>
                        <p style="color: #9CA3AF; font-size: 1.1rem; font-weight: 600; margin: 0;">Belum Di Unggah</p>
                    </div>
                </div>

                <div class="magang-right">
                    <div class="popup-section-pimpinan" style="height: 100%; box-sizing: border-box; display: flex; flex-direction: column;">
                        <h3 class="section-title">Input Nilai Magang</h3>
                        
                        <div class="input-group-magang">
                            <label>Nilai Disiplin &amp; Kehadiran</label>
                            <input type="text" placeholder="E.g., 90">
                        </div>
                        <div class="input-group-magang">
                            <label>Nilai Kinerja Teknis</label>
                            <input type="text" placeholder="E.g., 90">
                        </div>
                        <div class="input-group-magang">
                            <label>Nilai Laporan Kegiatan Harian</label>
                            <input type="text" placeholder="E.g., 90">
                        </div>
                        
                        <button class="btn-disabled">Simpan Nilai Magang</button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const mainWrapper = document.getElementById('mainWrapper');
        const toggleBtn = document.getElementById('sidebarToggle');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            mainWrapper.classList.toggle('sidebar-collapsed');
        });

        function showLogoutPopup(e) {
            if(e) e.preventDefault();
            document.getElementById('logoutPopup').style.display = 'flex';
        }
        function closeLogoutPopup() {
            document.getElementById('logoutPopup').style.display = 'none';
        }

        // Popups
        function openPopupEvaluasi(e) {
            if (e) e.preventDefault();
            document.getElementById('popupEvaluasi').style.display = 'flex';
        }
        function closePopupEvaluasi() {
            document.getElementById('popupEvaluasi').style.display = 'none';
        }

        function openPopupMagang(e) {
            if (e) e.preventDefault();
            document.getElementById('popupMagang').style.display = 'flex';
        }
        function closePopupMagang() {
            document.getElementById('popupMagang').style.display = 'none';
        }
    </script>

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
</body>
</html>