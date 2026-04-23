<?php
session_start();
require_once __DIR__ . '/../../config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 3) {
    header("Location: ../../public/login/logPengajar.php");
    exit;
}

// Fetch Real Instructor Notifications
$query_ann = mysqli_query($conn, "SELECT * FROM pengumuman WHERE target_role IS NULL OR target_role = 3 ORDER BY created_at DESC LIMIT 3");
$announcements = mysqli_fetch_all($query_ann, MYSQLI_ASSOC);

// Stats for Instructor
$instructor_id = $_SESSION['user_id'];
$q_total_siswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT ks.id_siswa) as total FROM jadwal j JOIN kelas_siswa ks ON j.id_kelas = ks.id_kelas WHERE j.id_pengajar = $instructor_id"))['total'];
$q_avg_score = mysqli_fetch_assoc(mysqli_query($conn, "SELECT AVG(rata_rata) as avg FROM evaluasi WHERE id_pengajar = $instructor_id"))['avg'] ?? 0;
// Pending eval count is total students minus those already evaluated this period
$q_pending_eval = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT ks.id_siswa) as total FROM jadwal j JOIN kelas_siswa ks ON j.id_kelas = ks.id_kelas WHERE j.id_pengajar = $instructor_id AND ks.id_siswa NOT IN (SELECT id_siswa FROM evaluasi WHERE periode_semester = 'Semester Ganjil 2025')"))['total'];

// Fetch latest students in this instructor's classes
$query_latest_siswa = mysqli_query($conn, "
    SELECT DISTINCT s.*, p.nama_program
    FROM siswa s
    JOIN kelas_siswa ks ON s.id_siswa = ks.id_siswa
    JOIN jadwal j ON ks.id_kelas = j.id_kelas
    JOIN program p ON s.id_program = p.id_program
    WHERE j.id_pengajar = $instructor_id
    ORDER BY s.id_siswa DESC
    LIMIT 5
");
$latest_students = mysqli_fetch_all($query_latest_siswa, MYSQLI_ASSOC);

// Fetch Instructor Schedules
$query_jadwal = mysqli_query($conn, "
    SELECT j.*, m.nama_mapel, k.nama_kelas 
    FROM jadwal j
    JOIN kurikulum kur ON j.id_kurikulum = kur.id_kurikulum
    JOIN mata_pelajaran m ON kur.id_mapel = m.id_mapel
    JOIN kelas k ON j.id_kelas = k.id_kelas
    WHERE j.id_pengajar = $instructor_id
    ORDER BY FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'), jam_mulai ASC
");
$schedules = mysqli_fetch_all($query_jadwal, MYSQLI_ASSOC);
?>
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

            <section class="dashboard-banner">
                <div class="banner-content">
                    <h1 class="banner-title">Selamat Datang, Bapak/Ibu Pengajar</h1>
                    <p class="banner-text">Pantau progres evaluasi siswa dan jadwal mengajar Anda di sini.</p>
                </div>
            </section>

            <!-- Jadwal Section -->
            <section class="schedule-section" style="margin-top: -30px; position: relative; z-index: 10; margin-bottom: 40px;">
                <div class="card card-table">
                    <div class="card-header" style="background: white; border-bottom: 1px solid #f1f5f9; padding: 20px 24px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h2 class="card-title" style="margin: 0;"><i class="fa-solid fa-calendar-days"></i> Jadwal Mengajar Minggu Ini</h2>
                            <span class="badge-status-blue" style="padding: 5px 15px; border-radius: 20px; font-size: 11px; font-weight: 700;">Semester Ganjil 2024/2025</span>
                        </div>
                    </div>
                    <div class="card-body" style="padding: 0;">
                        <div class="table-responsive">
                            <table class="table">
                                <thead style="background: #F8FAFC;">
                                    <tr>
                                        <th>Hari</th>
                                        <th>Jam</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Kelas</th>
                                        <th>Ruang</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($schedules)): ?>
                                        <tr>
                                            <td colspan="5" style="text-align: center; padding: 30px; color: #64748b;">Belum ada jadwal mengajar yang terdaftar.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($schedules as $sch): ?>
                                            <tr>
                                                <td style="font-weight: 700; color: #003B73;"><?= $sch['hari'] ?></td>
                                                <td><i class="fa-regular fa-clock" style="margin-right: 5px; color: #E9C46A;"></i> <?= date('H:i', strtotime($sch['jam_mulai'])) ?> - <?= date('H:i', strtotime($sch['jam_selesai'])) ?></td>
                                                <td style="font-weight: 600;"><?= htmlspecialchars($sch['nama_mapel']) ?></td>
                                                <td><span class="badge badge-info-light" style="background: #E0F2FE; color: #0369A1; padding: 4px 10px; border-radius: 6px; font-size: 12px;"><?= htmlspecialchars($sch['nama_kelas']) ?></span></td>
                                                <td><i class="fa-solid fa-location-dot" style="margin-right: 5px; color: #EF4444;"></i> <?= htmlspecialchars($sch['ruang']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ===== STAT CARDS ===== -->
            <section class="stats-section">
                <div class="stat-card">
                    <div class="stat-text-wrap">
                        <p class="stat-label">Total Siswa Anda</p>
                        <p class="stat-number"><?= $q_total_siswa ?></p>
                        <p class="stat-trend positive">Aktif Semester Ini</p>
                    </div>
                    <div class="stat-icon-wrap">
                        <i class="fa-solid fa-user-group"></i>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-text-wrap">
                        <p class="stat-label">Perlu Dievaluasi</p>
                        <p class="stat-number alert"><?= $q_pending_eval ?></p>
                        <p class="stat-trend warning">Belum Dinilai</p>
                    </div>
                    <div class="stat-icon-wrap">
                        <i class="fa-solid fa-book-open-reader"></i>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-text-wrap">
                        <p class="stat-label">Rata-rata Kelas</p>
                        <p class="stat-number success"><?= number_format($q_avg_score, 1) ?></p>
                        <p class="stat-trend <?= $q_avg_score >= 80 ? 'positive' : 'warning' ?>"><?= $q_avg_score >= 80 ? 'Sangat Baik' : 'Cukup' ?></p>
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
                        <h2 class="card-title">Daftar Mahasiswa di Kelas Anda</h2>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Nama Siswa</th>
                                        <th>Departemen / Program</th>
                                        <th style="text-align: center;">NIM</th>
                                        <th style="text-align: center;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($latest_students as $s): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($s['nama_lengkap']) ?></strong></td>
                                        <td><?= htmlspecialchars($s['nama_program']) ?></td>
                                        <td style="text-align: center;"><?= htmlspecialchars($s['nim_siswa']) ?></td>
                                        <td style="text-align: center;"><a href="detail_siswa.php?id=<?= $s['id_siswa'] ?>" class="btn-detail" style="text-decoration: none; padding: 5px 15px; background: #003B73; color: white; border-radius: 5px; font-size: 11px;">Detail</a></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if(empty($latest_students)): ?>
                                        <tr><td colspan="4" style="text-align: center; padding: 30px; color: #64748b;">Belum ada siswa di kelas Anda.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Announcement Instructor -->
                <div class="card card-table" style="height: fit-content;">
                    <div class="card-header border-none">
                        <h2 class="card-title"><i class="fa-solid fa-bullhorn"></i> Pengumuman Akademik</h2>
                    </div>
                    <div class="card-body">
                        <?php if (empty($announcements)): ?>
                            <p style="text-align: center; color: #64748b; padding: 20px;">Tidak ada pengumuman terbaru.</p>
                        <?php else: ?>
                            <?php foreach ($announcements as $ann): ?>
                                <div style="border-bottom: 1px solid #eee; padding: 15px 0; display: flex; align-items: flex-start; gap: 15px;">
                                    <div style="background: #E0F2FE; color: #0284C7; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <i class="fa-solid fa-info-circle"></i>
                                    </div>
                                    <div>
                                        <h4 style="margin: 0; color: #003B73; font-size: 15px;"><?= htmlspecialchars($ann['title']) ?></h4>
                                        <p style="margin: 5px 0 0; color: #64748b; font-size: 13px; line-height: 1.5;"><?= htmlspecialchars($ann['message']) ?></p>
                                        <small style="color: #94a3b8; font-size: 11px;"><?= date('d M Y', strtotime($ann['created_at'])) ?></small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

        </main>

        <div style="height: 50px;"></div>

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
