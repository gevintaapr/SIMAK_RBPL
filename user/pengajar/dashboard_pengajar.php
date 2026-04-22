<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pengajar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;800&family=Poppins:wght@400;500;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../style/dashboard_pengajar.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../style/popup_logout.css">
</head>
<body>

    <!-- ===== SIDEBAR ===== -->
    <aside class="sidebar collapsed" id="sidebar">
        <div class="sidebar-header">
            <span class="sidebar-logo">HCTS</span>
        </div>
        <nav class="sidebar-nav">
            <a href="dashboard_pengajar.php" class="sidebar-link active">
                <i class="fa-solid fa-gauge-high"></i>
                <span>Dashboard</span>
            </a>
            <a href="data_siswa.php" class="sidebar-link">
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
                    <a href="remedial_siswa.php" class="dropdown-link">Kelola Remedial</a>
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
                <span class="navbar-brand">HCTS Instructor Center</span>
            </div>
            <div class="navbar-right">
                <button class="notif-btn" id="notifBtn" aria-label="Notifikasi">
                    <i class="fa-regular fa-bell"></i>
                    <span class="notif-badge">3</span>
                </button>
                <div class="admin-profile">
                    <div class="admin-avatar">FN</div>
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

            <!-- Hero / Dashboard Banner -->
            <section class="dashboard-banner">
                <div class="banner-content">
                    <h1 class="banner-title">Dashboard Pengajaran</h1>
                    <div class="semester-pill">Semester Ganjil 2025</div>
                </div>
            </section>

            <!-- ===== STAT CARDS ===== -->
            <section class="stats-section">
                <div class="stat-card">
                    <div class="stat-text-wrap">
                        <p class="stat-label">Total Siswa</p>
                        <p class="stat-number">45</p>
                        <p class="stat-trend positive">+ 5 Siswa Baru</p>
                    </div>
                    <div class="stat-icon-wrap">
                        <i class="fa-solid fa-user-group"></i>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-text-wrap">
                        <p class="stat-label">Perlu Dievaluasi</p>
                        <p class="stat-number alert">12</p>
                        <p class="stat-trend warning">Pending</p>
                    </div>
                    <div class="stat-icon-wrap">
                        <i class="fa-solid fa-book-open-reader"></i>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-text-wrap">
                        <p class="stat-label">Rata-rata Kelas</p>
                        <p class="stat-number success">82.5</p>
                        <p class="stat-trend positive">Baik</p>
                    </div>
                    <div class="stat-icon-wrap">
                        <i class="fa-solid fa-clipboard-check"></i>
                    </div>
                </div>
            </section>

            <!-- ===== BOTTOM DATA SECTION ===== -->
            <section class="bottom-section full-width">
                <!-- Siswa Terbaru Masuk -->
                <div class="card card-table">
                    <div class="card-header border-none">
                        <h2 class="card-title">Siswa Terbaru Masuk</h2>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Nama Siswa</th>
                                        <th>Departemen</th>
                                        <th>Tanggal Masuk</th>
                                        <th>Status Evaluasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Andi Pratama</td>
                                        <td>Housekeeping</td>
                                        <td>10 Desember 2025</td>
                                        <td><span class="badge badge-warning-light">Belum Dinilai</span></td>
                                    </tr>
                                    <tr>
                                        <td>Alexander Wibowo</td>
                                        <td>F&amp;B Service</td>
                                        <td>11 Desember 2025</td>
                                        <td><span class="badge badge-warning-light">Belum Dinilai</span></td>
                                    </tr>
                                    <tr>
                                        <td>Siti Jumairah</td>
                                        <td>Kitchen</td>
                                        <td>12 Desember 2025</td>
                                        <td><span class="badge badge-success-light">Selesai</span></td>
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
                    <p class="footer-desc">Sekolah pelatihan internasional terkemuka untuk karier di bidang perhotelan dan kapal pesiar.</p>
                    <div class="footer-socials">
                        <a href="#" class="social-btn" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="social-btn" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="social-btn" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                        <a href="#" class="social-btn" aria-label="Twitter"><i class="fa-brands fa-twitter"></i></a>
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

    <script>
        const sidebar = document.getElementById('sidebar');
        const mainWrapper = document.getElementById('mainWrapper');
        const toggleBtn = document.getElementById('sidebarToggle');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            mainWrapper.classList.toggle('sidebar-collapsed');
        });

        const evaluasiToggle = document.getElementById('evaluasiToggle');
        const evaluasiMenu = document.getElementById('evaluasiMenu');

        if (evaluasiToggle) {
            evaluasiToggle.addEventListener('click', (e) => {
                e.preventDefault();
                evaluasiToggle.classList.toggle('open');
                evaluasiMenu.classList.toggle('show');
            });
        }

        function showLogoutPopup(e) {
            if(e) e.preventDefault();
            document.getElementById('logoutPopup').style.display = 'flex';
        }
        function closeLogoutPopup() {
            document.getElementById('logoutPopup').style.display = 'none';
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
