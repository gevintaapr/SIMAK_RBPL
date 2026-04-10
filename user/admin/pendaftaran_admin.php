<?php
require_once __DIR__ . '/../../config/config.php';

$query = mysqli_query($conn, "SELECT * FROM pendaftaran ORDER BY id_pendaftaran DESC");
$pendaftaran = mysqli_fetch_all($query, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran - HCTS Admin Center</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;800&family=Poppins:wght@400;500;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../style/pendaftaran_admin.css?v=<?= time() ?>">
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

            <!-- Hero / Dashboard Banner -->
            <section class="dashboard-banner">
                <div class="banner-content">
                    <h1 class="banner-title">Pendaftaran</h1>
                </div>

            </section>

            <!-- Pendaftaran List Section -->
            <section class="pendaftaran-list-section">
                <div class="pendaftaran-list-card">
                    <div class="table-header">
                        <h2 class="table-title">Daftar Pendaftar Terbaru</h2>
                    </div>
                    <div class="filter-controls">
                        <div class="search-box">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" placeholder="Cari Nama, Nomor Pendaftaran, atau Program...">
                        </div>
                        <select class="filter-select">
                            <option>Filter Program</option>
                            <option>Hotel Management</option>
                            <option>Cruise Ship Operations</option>
                            <option>Culinary Arts</option>
                        </select>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>NOMOR PENDAFTARAN</th>
                                    <th>NAMA</th>
                                    <th>PROGRAM</th>
                                    <th>STATUS PENDAFTARAN</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($pendaftaran)): ?>
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 30px; color: #999;">Belum ada data pendaftaran masuk.</td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach ($pendaftaran as $row): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['id_pendaftaran']) ?></td>
                                        <td><?= htmlspecialchars($row['nama_cs']) ?></td>
                                        <td><?= htmlspecialchars($row['program']) ?></td>
                                         <td>
                                            <?php if ($row['status_approval'] === 'disetujui' || $row['status_approval'] == 1): ?>
                                                <span class="badge badge-status-green" style="background: #D1FAE5; color: #059669;">Lulus Seleksi</span>
                                            <?php elseif ($row['status_approval'] === 'ditolak'): ?>
                                                <span class="badge badge-status-red" style="background: #FEE2E2; color: #DC2626;">Ditolak</span>
                                            <?php elseif ($row['status_approval'] === 'menunggu_pimpinan'): ?>
                                                <span class="badge badge-status-orange" style="background: #FEF3C7; color: #D97706;">Menunggu Pimpinan</span>
                                            <?php elseif ($row['jadwal_wawancara'] !== NULL): ?>
                                                <span class="badge badge-status-purple" style="background: #EDE9FE; color: #7C3AED;">Wawancara</span>
                                            <?php elseif ($row['status_berkas'] === 'valid'): ?>
                                                <span class="badge badge-status-green" style="background: #D1FAE5; color: #059669;">Terverifikasi</span>
                                            <?php else: ?>
                                                <span class="badge badge-status-blue">Menunggu Verifikasi</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button class="btn-detail" onclick="window.location.href='detail_pendaftar_A.php?id=<?= $row['id_pendaftaran'] ?>'">Detail</button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

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