session_start();
require_once '../../config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 3) {
    header("Location: ../../public/login/logPengajar.php");
    exit;
}
// Dummy data
$students = [
    ['id' => '124240110', 'nama' => 'Budi Santoso', 'jurusan' => 'F&B Service', 'angkatan' => '1/2025', 'status' => 'Aktif'],
    ['id' => '124240111', 'nama' => 'Ragi Danuarta', 'jurusan' => 'Housekeeping', 'angkatan' => '1/2025', 'status' => 'Aktif'],
    ['id' => '124240112', 'nama' => 'Jamil Punu Hitagir', 'jurusan' => 'Front Office', 'angkatan' => '2/2025', 'status' => 'Aktif'],
    ['id' => '124240113', 'nama' => 'Santisio Rombawa', 'jurusan' => 'Kitchen', 'angkatan' => '2/2025', 'status' => 'Aktif'],
    ['id' => '124240114', 'nama' => 'Alexander Wibowo', 'jurusan' => 'F&B Service', 'angkatan' => '1/2025', 'status' => 'Aktif'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa - Pengajar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;800&family=Poppins:wght@400;500;600;800&display=swap" rel="stylesheet">
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

            <!-- Hero Banner -->
            <section class="ds-banner">
                <h1 class="ds-title">Data Siswa</h1>
                <div class="sem-pill">Semester Ganjil 2025</div>
            </section>

            <!-- Content Area -->
            <section class="ds-content-container">
                <div class="ds-card">
                    <h2 class="ds-card-title">Direktori Siswa</h2>
                    
                    <div class="ds-search-bar">
                        <input type="text" id="searchInput" placeholder="Cari nama siswa..." class="ds-search-input">
                    </div>

                    <div class="ds-table-responsive">
                        <table class="ds-table">
                            <thead>
                                <tr>
                                    <th>No. Induk</th>
                                    <th>Nama Lengkap</th>
                                    <th>Jurusan</th>
                                    <th>Angkatan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="studentTableBody">
                                <?php foreach($students as $s): ?>
                                <tr>
                                    <td><?= htmlspecialchars($s['id']) ?></td>
                                    <td><?= htmlspecialchars($s['nama']) ?></td>
                                    <td><?= htmlspecialchars($s['jurusan']) ?></td>
                                    <td><?= htmlspecialchars($s['angkatan']) ?></td>
                                    <td><span class="ds-badge-aktif"><?= htmlspecialchars($s['status']) ?></span></td>
                                    <td>
                                        <a href="detail_siswa.php?id=<?= $s['id'] ?>" class="btn-detail">Detail</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
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
                        <a href="#" class="social-btn"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="social-btn"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="social-btn"><i class="fa-brands fa-linkedin-in"></i></a>
                        <a href="#" class="social-btn"><i class="fa-brands fa-twitter"></i></a>
                    </div>
                </div>
                <div class="footer-col">
                    <h4 class="footer-heading">Aksi Cepat</h4>
                    <ul class="footer-links">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Our Programs</a></li>
                        <li><a href="#">Admission Process</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4 class="footer-heading">Program Kami</h4>
                    <ul class="footer-links">
                        <li><a href="#">Hotel Management</a></li>
                        <li><a href="#">Cruise Ship Operations</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4 class="footer-heading">Kontak Kami</h4>
                    <ul class="footer-contact">
                        <li><i class="fa-solid fa-location-dot"></i> <span>123 Maritime Avenue</span></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p class="footer-copy">&copy; 2025 HCTS International.</p>
            </div>
        </footer>

    </div>

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
        const sidebar = document.getElementById('sidebar');
        const mainWrapper = document.getElementById('mainWrapper');
        const toggleBtn = document.getElementById('sidebarToggle');

        if(toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed');
                mainWrapper.classList.toggle('sidebar-collapsed');
            });
        }

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

        // --- Search Feature ---
        const searchInput = document.getElementById('searchInput');
        const tableBody = document.getElementById('studentTableBody');
        
        if(searchInput && tableBody) {
            searchInput.addEventListener('keyup', function() {
                const filter = searchInput.value.toLowerCase();
                const rows = tableBody.getElementsByTagName('tr');

                for (let i = 0; i < rows.length; i++) {
                    const textContent = rows[i].textContent || rows[i].innerText;
                    if (textContent.toLowerCase().indexOf(filter) > -1) {
                        rows[i].style.display = '';
                    } else {
                        rows[i].style.display = 'none';
                    }
                }
            });
        }
    </script>
</body>
</html>
