<?php
require_once __DIR__ . '/../../config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 5) {
    header("Location: ../../public/login/logAdmin.php?role=5&error=" . urlencode("Akses ditolak. Silakan login sebagai Admin."));
    exit;
}

$query = mysqli_query($conn, "SELECT * FROM siswa ORDER BY id_siswa DESC");
$siswa_list = mysqli_fetch_all($query, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akademik - HCTS Admin Center</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;800&family=Poppins:wght@400;500;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../style/dashboard_admin.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../style/akademik_admin.css?v=<?= time() ?>">
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
            <a href="akademik_admin.php" class="sidebar-link active">
                <i class="fa-solid fa-book"></i>
                <span>Akademik</span>
            </a>
            <a href="sertifikat_admin.php" class="sidebar-link">
                <i class="fa-solid fa-certificate"></i>
                <span>Sertifikat</span>
            </a>
            <a href="taiwan.php" class="sidebar-link">
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
                    <h1 class="banner-title">Akademik</h1>
                </div>
            </section>

            <!-- Nav Pills -->
            <div class="akademik-nav-group">
                <button class="akademik-nav-btn active"><i class="fa-solid fa-users"></i> Daftar Siswa</button>
                <button class="akademik-nav-btn" onclick="window.location.href='verifPembayaran_akademik.php'"><i class="fa-solid fa-wallet"></i> Verifikasi Pembayaran</button>
                <button class="akademik-nav-btn"><i class="fa-solid fa-calendar-check"></i> Atur Jadwal</button>
                <button class="akademik-nav-btn"><i class="fa-solid fa-paste"></i> Verifikasi Evaluasi</button>
            </div>

            <!-- Stats -->
            <div class="akademik-stats">
                <div class="ak-stat-card">
                    <div class="ak-stat-info">
                        <p class="ak-stat-num">1,250</p>
                        <p class="ak-stat-label">Total Siswa Aktif</p>
                    </div>
                    <div class="ak-stat-icon"><i class="fa-solid fa-users"></i></div>
                </div>
                <div class="ak-stat-card">
                    <div class="ak-stat-info">
                        <p class="ak-stat-num">800</p>
                        <p class="ak-stat-label">Siswa Kelas Reguler</p>
                    </div>
                    <div class="ak-stat-icon"><i class="fa-solid fa-book-open"></i></div>
                </div>
                <div class="ak-stat-card">
                    <div class="ak-stat-info">
                        <p class="ak-stat-num">350</p>
                        <p class="ak-stat-label">Siswa Magang</p>
                    </div>
                    <div class="ak-stat-icon"><i class="fa-solid fa-briefcase"></i></div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="akademik-card-section">
                <div class="ak-card">
                    <h2 class="ak-card-title">Daftar Siswa</h2>
                    
                    <div class="ak-controls">
                        <select class="ak-select">
                            <option value="">Program</option>
                            <option value="fb">Hotel F&amp;B Service</option>
                            <option value="deck">Cruise Ship Deck Kadet</option>
                            <option value="culinary">Cruise Ship Culinary</option>
                        </select>
                        <select class="ak-select">
                            <option value="">Status</option>
                            <option value="reguler">Reguler</option>
                            <option value="magang">Magang</option>
                        </select>
                        <div class="ak-search">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" placeholder="Cari Nama Siswa">
                            <button class="clear-search"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                    </div>

                    <table class="ak-table">
                        <thead>
                            <tr>
                                <th>NIM SISWA</th>
                                <th>NAMA</th>
                                <th>PROGRAM</th>
                                <th>STATUS</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($siswa_list)): ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 20px; color: #666;">Belum ada data siswa.</td>
                            </tr>
                            <?php else: ?>
                                <?php foreach ($siswa_list as $s): ?>
                                <tr>
                                    <td><?= htmlspecialchars($s['nim_siswa']) ?></td>
                                    <td><?= htmlspecialchars($s['nama_lengkap']) ?></td>
                                    <td><?= htmlspecialchars($s['program_pembelajaran']) ?></td>
                                    <td><span class="badge-ak-reguler">Reguler</span></td>
                                    <td><button class="btn-ak-detail">Detail</button></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>

    </div>

    <script>
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
