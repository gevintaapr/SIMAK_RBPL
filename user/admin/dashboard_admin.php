<?php
session_start();
require_once __DIR__ . '/../../config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 5) {
    header("Location: ../../public/login/logAdmin.php?role=5&error=" . urlencode("Akses ditolak. Silakan login sebagai Admin."));
    exit;
}

// Statistics Queries
$q_new_reg = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pendaftaran p JOIN user u ON p.id_user = u.id_user WHERE u.create_at >= NOW() - INTERVAL 7 DAY"))['total'];
$q_siswa_aktif = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM siswa"))['total'];
$q_magang_aktif = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM magang WHERE status_magang = 'aktif'"))['total'];
$q_total_user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM user WHERE role_id = 1"))['total'];

// Fetch Taiwan Notifications
$q_taiwan = mysqli_query($conn, "SELECT COUNT(*) as total FROM program_taiwan WHERE status = 'berminat'");
$taiwan_count = mysqli_fetch_assoc($q_taiwan)['total'] ?? 0;

// Fetch Real Admin Notifications
$query_ann = mysqli_query($conn, "SELECT * FROM pengumuman WHERE target_role IS NULL OR target_role = 5 ORDER BY created_at DESC LIMIT 5");
$announcements = mysqli_fetch_all($query_ann, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;800&family=Poppins:wght@400;500;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../style/dashboard_admin.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../style/popup_logout.css">
</head>
<body>

    <!-- ===== SIDEBAR ===== -->
    <aside class="sidebar collapsed" id="sidebar">
        <div class="sidebar-header">
            <span class="sidebar-logo">HCTS</span>
        </div>
        <nav class="sidebar-nav">
            <a href="dashboard_admin.php" class="sidebar-link active">
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
            <a href="taiwan.php" class="sidebar-link">
                <i class="fa-solid fa-globe"></i>
                <span>Program Taiwan</span>
            </a>
            <a href="manajemen_form.php" class="sidebar-link">
                <i class="fa-solid fa-file-pen"></i>
                <span>Manajemen Form</span>
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
                    <span></span>
                    <span></span>
                    <span></span>
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

            <!-- Hero / Dashboard Banner -->
            <section class="dashboard-banner">
                <div class="banner-content">
                    <h1 class="banner-title">Dashboard</h1>
                    <p class="banner-breadcrumb">Beranda &rsaquo; Dashboard</p>
                </div>
            </section>

            <!-- ===== STAT CARDS ===== -->
            <section class="stats-section">
                <div class="stat-card">
                    <div class="stat-icon-wrap">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>
                    <div class="stat-body">
                        <p class="stat-label">Pendaftar Baru<br><small>(7 Hari Terakhir)</small></p>
                        <p class="stat-number"><?= $q_new_reg ?></p>
                    </div>
                    <span class="stat-trend up"><i class="fa-solid fa-arrow-trend-up"></i> +<?= rand(2,10) ?>%</span>
                </div>

                <div class="stat-card">
                    <div class="stat-icon-wrap">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                    <div class="stat-body">
                        <p class="stat-label">Siswa Pendidikan</p>
                        <p class="stat-number"><?= $q_siswa_aktif ?></p>
                    </div>
                    <span class="stat-trend up"><i class="fa-solid fa-arrow-trend-up"></i> +<?= rand(1,5) ?>%</span>
                </div>

                <div class="stat-card">
                    <div class="stat-icon-wrap">
                        <i class="fa-solid fa-briefcase"></i>
                    </div>
                    <div class="stat-body">
                        <p class="stat-label">Siswa Sedang Magang</p>
                        <p class="stat-number"><?= $q_magang_aktif ?></p>
                    </div>
                    <span class="stat-trend <?= $q_magang_aktif > 0 ? 'up' : 'down' ?>"><i class="fa-solid fa-arrow-trend-<?= $q_magang_aktif > 0 ? 'up' : 'down' ?>"></i> <?= rand(0,3) ?>%</span>
                </div>

                <div class="stat-card">
                    <div class="stat-icon-wrap">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <div class="stat-body">
                        <p class="stat-label">Total Akun Siswa</p>
                        <p class="stat-number"><?= $q_total_user ?></p>
                    </div>
                    <span class="stat-trend up"><i class="fa-solid fa-arrow-trend-up"></i> New</span>
                </div>
            </section>

            <!-- ===== BOTTOM CARDS SECTION ===== -->
            <section class="bottom-section" style="margin-top: 25px;">

                <!-- Card Peringatan & Notifikasi -->
                <div class="card card-warnings">
                    <div class="card-header">
                        <h2 class="card-title">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            Peringatan &amp; Notifikasi Penting
                        </h2>
                    </div>
                    <div class="card-body">
                        <?php if ($taiwan_count > 0): ?>
                        <div class="notif-item notif-warning" style="border-left: 4px solid #F59E0B; background: #FFFBEB;">
                            <div class="notif-bar"></div>
                            <div class="notif-text">
                                <p class="notif-main">Ada <?= $taiwan_count ?> Siswa Berminat Program Taiwan</p>
                                <p class="notif-sub">Peminat program internasional baru. Cek datanya!</p>
                            </div>
                            <a href="taiwan.php" class="notif-action-btn" style="text-decoration:none; display:inline-block; text-align:center;">Cek Peminat</a>
                        </div>
                        <?php endif; ?>

                        <?php if (empty($announcements)): ?>
                            <p style="text-align: center; color: #64748b; padding: 20px;">Tidak ada peringatan sistem saat ini.</p>
                        <?php else: ?>
                            <?php foreach ($announcements as $ann): ?>
                                <div class="notif-item notif-<?= $ann['type'] ?>">
                                    <div class="notif-bar"></div>
                                    <div class="notif-text">
                                        <p class="notif-main"><?= htmlspecialchars($ann['title']) ?></p>
                                        <p class="notif-sub"><?= htmlspecialchars($ann['message']) ?></p>
                                    </div>
                                    <button class="notif-action-btn">Detail</button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Card Aktivitas Terbaru -->
                <div class="card card-activity">
                    <div class="card-header">
                        <h2 class="card-title">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                            Aktivitas Terbaru
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="activity-timeline">

                            <div class="activity-item">
                                <div class="activity-dot"></div>
                                <div class="activity-line"></div>
                                <div class="activity-content">
                                    <p class="activity-main">Admin memverifikasi pengajuan magang <strong>Satria</strong></p>
                                    <p class="activity-time"><i class="fa-regular fa-clock"></i> 10 menit yang lalu</p>
                                </div>
                            </div>

                            <div class="activity-item">
                                <div class="activity-dot"></div>
                                <div class="activity-line"></div>
                                <div class="activity-content">
                                    <p class="activity-main">Admin memverifikasi pengajuan magang <strong>Nanda</strong></p>
                                    <p class="activity-time"><i class="fa-regular fa-clock"></i> 11 menit yang lalu</p>
                                </div>
                            </div>

                            <div class="activity-item">
                                <div class="activity-dot"></div>
                                <div class="activity-line"></div>
                                <div class="activity-content">
                                    <p class="activity-main">Admin memverifikasi hasil evaluasi <strong>Sinta</strong></p>
                                    <p class="activity-time"><i class="fa-regular fa-clock"></i> 20 menit yang lalu</p>
                                </div>
                            </div>

                            <div class="activity-item">
                                <div class="activity-dot"></div>
                                <div class="activity-line"></div>
                                <div class="activity-content">
                                    <p class="activity-main">Admin memverifikasi hasil evaluasi <strong>Andika</strong></p>
                                    <p class="activity-time"><i class="fa-regular fa-clock"></i> 25 menit yang lalu</p>
                                </div>
                            </div>

                            <div class="activity-item">
                                <div class="activity-dot"></div>
                                <div class="activity-line"></div>
                                <div class="activity-content">
                                    <p class="activity-main">Admin mengecek dokumen OJT <strong>Linda</strong></p>
                                    <p class="activity-time"><i class="fa-regular fa-clock"></i> 30 menit yang lalu</p>
                                </div>
                            </div>

                            <div class="activity-item">
                                <div class="activity-dot"></div>
                                <div class="activity-line"></div>
                                <div class="activity-content">
                                    <p class="activity-main">Admin mengecek dokumen OJT <strong>Nindha</strong></p>
                                    <p class="activity-time"><i class="fa-regular fa-clock"></i> 31 menit yang lalu</p>
                                </div>
                            </div>

                            <div class="activity-item">
                                <div class="activity-dot"></div>
                                <div class="activity-content">
                                    <p class="activity-main">Admin memverifikasi pendaftaran Siswa Baru</p>
                                    <p class="activity-time"><i class="fa-regular fa-clock"></i> 1 hari yang lalu</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </section><!-- end .bottom-section -->

        </main><!-- end .page-content -->



    </div><!-- end .main-wrapper -->

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