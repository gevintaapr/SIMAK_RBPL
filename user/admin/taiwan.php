<?php
require_once __DIR__ . '/../../config/config.php';

// Auth: hanya admin (role_id = 5)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 5) {
    header("Location: ../../public/login/logAdmin.php?role=5&error=" . urlencode("Akses ditolak. Silakan login sebagai Admin."));
    exit;
}

// Dummy data for visualization as per image
$students = [
    ['id' => 'HC-123', 'nama' => 'Jessica Tan', 'program' => 'Hotel F&B Service', 'status' => 'Berminat'],
    ['id' => 'HC-124', 'nama' => 'Jeno Samudra', 'program' => 'Cruise Ship Deck Kadet', 'status' => 'Diajukan ke Mitra'],
    ['id' => 'HC-125', 'nama' => 'Maria Gomez', 'program' => 'Cruise Ship Culinary', 'status' => 'Lolos']
];

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Taiwan - HCTS Admin Center</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;800&family=Poppins:wght@400;500;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../style/taiwan.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../style/popup_logout.css">
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
            <a href="pendaftaran_admin.php" class="sidebar-link">
                <i class="fa-solid fa-file-signature"></i>
                <span>Pendaftaran</span>
            </a>
            <a href="magang_admin.php" class="sidebar-link">
                <i class="fa-solid fa-briefcase"></i>
                <span>Magang (OJT)</span>
            </a>
            <a href="akademik_admin.php" class="sidebar-link">
                <i class="fa-solid fa-book"></i>
                <span>Akademik</span>
            </a>
            <a href="sertifikat_admin.php" class="sidebar-link">
                <i class="fa-solid fa-certificate"></i>
                <span>Sertifikat</span>
            </a>
            <a href="taiwan.php" class="sidebar-link active">
                <i class="fa-solid fa-globe"></i>
                <span>Program Taiwan</span>
            </a>
            <a href="manage_user.php" class="sidebar-link">
                <i class="fa-solid fa-users-gear"></i>
                <span>Manajemen Pengguna</span>
            </a>
            <a href="#" class="sidebar-link">
                <i class="fa-solid fa-gear"></i>
                <span>Pengaturan</span>
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

            <!-- Hero Section -->
            <section class="dashboard-banner">
                <div class="banner-content">
                    <h1 class="banner-title">Program Taiwan</h1>
                </div>
            </section>

            <!-- Table Section -->
            <section class="taiwan-section">
                <div class="taiwan-card">
                    <div class="card-header-flex">
                        <h2 class="card-title">Program Taiwan</h2>
                    </div>

                    <div class="filter-controls">
                        <div class="dropdown-group">
                            <select class="filter-select">
                                <option>Program</option>
                            </select>
                            <select class="filter-select">
                                <option>Status</option>
                            </select>
                            <select class="filter-select">
                                <option>Periode</option>
                            </select>
                        </div>
                        <div class="search-box">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" id="studentSearch" placeholder="Cari Nama Siswa">
                            <i class="fa-solid fa-xmark clear-search" style="display:none;"></i>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID SISWA</th>
                                    <th>NAMA</th>
                                    <th>PROGRAM</th>
                                    <th>STATUS PENGAJUAN</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $student): ?>
                                <tr>
                                    <td><?= $student['id'] ?></td>
                                    <td><?= $student['nama'] ?></td>
                                    <td><?= $student['program'] ?></td>
                                    <td>
                                        <?php
                                            $badge_class = 'badge-status-gray';
                                            if ($student['status'] === 'Diajukan ke Mitra') $badge_class = 'badge-status-blue';
                                            elseif ($student['status'] === 'Lolos') $badge_class = 'badge-status-green';
                                        ?>
                                        <span class="badge <?= $badge_class ?>"><?= $student['status'] ?></span>
                                    </td>
                                    <td>
                                        <div class="action-cell">
                                            <button class="btn-detail" onclick="openModal('<?= $student['nama'] ?>', '<?= $student['status'] ?>')">Detail</button>
                                            <?php if ($student['status'] === 'Berminat'): ?>
                                                <input type="checkbox" class="student-checkbox" onchange="toggleDownloadButton()">
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Download PDF Button -->
                <button id="btnDownload" class="btn-download-pdf" style="display: none;">
                    <i class="fa-solid fa-download"></i> Unduh PDF
                </button>
            </section>

        </main>

    </div>

    <!-- Modal Detail -->
    <div id="detailModal" class="modal-overlay">
        <div class="modal-card">
            <button class="modal-close" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
            <h2 class="modal-title" id="modalTitle">Detail Siswa: Jessica Tan</h2>
            <hr class="modal-divider">
            
            <div class="modal-grid">
                <div class="data-diri-section">
                    <h3>Data Diri</h3>
                    <div class="info-row">
                        <span class="label">Program Keahlian:</span>
                        <span class="value">15/06/2025</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Progres Pendidikan:</span>
                        <span class="value">Tuntas</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Status Magang:</span>
                        <span class="value">Selesai</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Minat Taiwan:</span>
                        <span class="value">Aktif</span>
                    </div>
                </div>
                <div class="status-taiwan-section">
                    <div class="status-header">
                        <i class="fa-solid fa-chart-line"></i>
                        <span>Status Pengajuan Taiwan</span>
                    </div>
                    <h2 class="status-text" id="modalStatus">Berminat</h2>
                </div>
            </div>

            <div class="modal-footer">
                <span class="footer-label">Tindak Lanjut:</span>
                <button class="btn-change-status">
                    <i class="fa-regular fa-pen-to-square"></i> Ubah Status
                </button>
            </div>
        </div>
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

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            mainWrapper.classList.toggle('sidebar-collapsed');
        });

        // Logout Popup
        function showLogoutPopup(e) {
            if(e) e.preventDefault();
            document.getElementById('logoutPopup').style.display = 'flex';
        }
        function closeLogoutPopup() {
            document.getElementById('logoutPopup').style.display = 'none';
        }

        function toggleDownloadButton() {
            const checkboxes = document.querySelectorAll('.student-checkbox');
            const btnDownload = document.getElementById('btnDownload');
            let anyChecked = false;
            checkboxes.forEach(cb => {
                if (cb.checked) anyChecked = true;
            });
            btnDownload.style.display = anyChecked ? 'flex' : 'none';
        }

        function openModal(nama, status) {
            document.getElementById('modalTitle').innerText = 'Detail Siswa: ' + nama;
            document.getElementById('modalStatus').innerText = status;
            document.getElementById('detailModal').classList.add('active');
        }

        function closeModal() {
            document.getElementById('detailModal').classList.remove('active');
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById('detailModal')) {
                closeModal();
            }
        }
    </script>
</body>
</html>