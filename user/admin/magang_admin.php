<?php
require_once __DIR__ . '/../../config/config.php';

// Auth: hanya admin (role_id = 5)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 5) {
    header("Location: ../../public/login/logAdmin.php?role=5&error=" . urlencode("Akses ditolak. Silakan login sebagai Admin."));
    exit;
}

// Fetch semua data magang + nama siswa dari tabel siswa via user_id
$query = mysqli_query($conn, "
    SELECT m.*, s.nama_lengkap, s.nim_siswa, u.email 
    FROM magang m 
    LEFT JOIN siswa s ON m.user_id = s.id_user 
    LEFT JOIN user u ON m.user_id = u.id_user 
    ORDER BY m.created_at DESC
");
$magang_list = [];
if ($query) {
    while ($row = mysqli_fetch_assoc($query)) {
        $magang_list[] = $row;
    }
}

// Stats
$stats = [
    'pengajuan'   => 0,
    'berlangsung' => 0,
    'selesai'     => 0
];
foreach ($magang_list as $m) {
    if ($m['status_magang'] === 'belum_mulai') $stats['pengajuan']++;
    elseif ($m['status_magang'] === 'berlangsung') $stats['berlangsung']++;
    elseif ($m['status_magang'] === 'selesai') $stats['selesai']++;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magang - HCTS Admin Center</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../style/magang_admin.css?v=<?= time() ?>">
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
            <a href="magang_admin.php" class="sidebar-link active">
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

            <!-- Hero / Dashboard Banner -->
            <section class="dashboard-banner">
                <div class="banner-content">
                    <h1 class="banner-title">Magang</h1>
                </div>
            </section>

            <!-- Stats Section -->
            <section class="stats-section">
                <div class="stats-container">
                    <div class="stat-card">
                        <div class="stat-info">
                            <span class="stat-value"><?= $stats['pengajuan'] ?></span>
                            <span class="stat-label">Pengajuan Magang Masuk</span>
                        </div>
                        <div class="stat-icon">
                            <i class="fa-solid fa-user-group"></i>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-info">
                            <span class="stat-value"><?= $stats['berlangsung'] ?></span>
                            <span class="stat-label">Magang Berjalan</span>
                        </div>
                        <div class="stat-icon">
                            <i class="fa-solid fa-book-open-reader"></i>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-info">
                            <span class="stat-value"><?= $stats['selesai'] ?></span>
                            <span class="stat-label">Magang Selesai</span>
                        </div>
                        <div class="stat-icon">
                            <i class="fa-solid fa-briefcase"></i>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Magang List Section -->
            <section class="magang-list-section">
                <div class="magang-list-card">
                    <div class="table-header">
                        <h2 class="table-title">Data Magang Siswa</h2>
                    </div>
                    <div class="filter-controls">
                        <select class="filter-select">
                            <option>Status</option>
                            <option>Pengajuan</option>
                            <option>Berjalan</option>
                            <option>Selesai</option>
                        </select>
                        <div class="search-box">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" placeholder="Cari Nama Siswa">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>NAMA</th>
                                    <th>NIM</th>
                                    <th>TEMPAT</th>
                                    <th>STATUS MAGANG</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($magang_list)): ?>
                                    <tr><td colspan="5" style="text-align:center;">Tidak ada data magang.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($magang_list as $magang): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($magang['nama_lengkap'] ?? 'Unknown') ?></td>
                                            <td><?= htmlspecialchars($magang['nim_siswa'] ?? '-') ?></td>
                                            <td><?= htmlspecialchars($magang['nama_tempat']) ?></td>
                                            <td>
                                                <?php 
                                                    $status_class = 'status-' . str_replace('_', '-', $magang['status_magang']);
                                                    echo "<span class='$status_class'>" . ucfirst(str_replace('_', ' ', $magang['status_magang'])) . "</span>";
                                                ?>
                                            </td>
                                            <td><button class="btn-detail" onclick='openModal(<?= json_encode($magang) ?>)'>Detail</button></td>
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

    <!-- MODAL DETAIL MAGANG -->
    <div class="modal-overlay" id="detailModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Detail Magang</h2>
                <button class="modal-close" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-divider"></div>
            
            <div class="modal-body">
                
                <!-- Identitas Siswa Full Width -->
                <div class="detail-card">
                    <div class="detail-card-header">
                        <h3 class="detail-card-title">Identitas Siswa</h3>
                    </div>
                    <div class="identitas-grid">
                        <div class="identitas-col">
                            <div class="detail-text-row">
                                <span class="detail-text-label">Nama Lengkap:</span>
                                <span class="detail-text-val" id="modalNama">-</span>
                            </div>
                            <div class="detail-text-row" style="margin-top: 14px;">
                                <span class="detail-text-label">Email:</span>
                                <span class="detail-text-val" id="modalEmail">-</span>
                            </div>
                        </div>
                        <div class="identitas-col">
                            <div class="detail-text-row">
                                <span class="detail-text-label">NIM:</span>
                                <span class="detail-text-val" id="modalNim">-</span>
                            </div>
                            <div class="detail-text-row" style="margin-top: 14px;">
                                <span class="detail-text-label">ID Magang:</span>
                                <span class="detail-text-val" id="modalId">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-grid-bottom">
                    <!-- Kolom Kiri -->
                    <div class="modal-col-left">
                        <!-- Detail Pengajuan -->
                        <div class="detail-card" style="padding-bottom: 24px;">
                            <div class="detail-card-header">
                                <h3 class="detail-card-title">Detail Pengajuan</h3>
                                <span class="badge-light-green" id="modalStatusPengajuan">-</span>
                            </div>
                            <div style="margin-top: 16px;">
                                <div class="detail-text-row">
                                    <span class="detail-text-label">Nama Perusahaan/Hotel:</span>
                                    <span class="detail-text-val" id="modalTempat">-</span>
                                </div>
                                <div class="detail-text-row" style="margin-top: 14px;">
                                    <span class="detail-text-label">Alamat:</span>
                                    <span class="detail-text-val" id="modalAlamat">-</span>
                                </div>
                                <div class="detail-text-row" style="margin-top: 14px;">
                                    <span class="detail-text-label">Status Verifikasi Admin:</span>
                                    <span class="detail-text-val" id="modalStatusVerifikasi">-</span>
                                </div>
                            </div>
                            
                            <div id="verifikasiAdminSection" style="margin-top: 24px; padding-top: 16px; border-top: 1px solid #eee;">
                                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Catatan (Wajib jika menolak):</label>
                                <textarea id="adminCatatan" style="width: 100%; border: 1px solid #ddd; border-radius: 4px; padding: 8px; margin-bottom: 12px;"></textarea>
                                <div style="display: flex; gap: 10px; justify-content: center;">
                                    <button onclick="prosesVerifikasi('ditolak')" class="btn-detail" style="background: #ef4444; color: white;">Tolak</button>
                                    <button onclick="prosesVerifikasi('diterima')" class="btn-primary-yellow">Terima & Mulai</button>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Selesai -->
                        <div id="selesaiSection" style="display:none; margin-top: 20px;">
                            <button onclick="prosesSelesai()" class="btn-primary-yellow" style="width: 100%; height: 50px; font-size: 16px;">Selesaikan Program Magang</button>
                        </div>
                    </div>

                    <!-- Kolom Kanan (Info Tambahan) -->
                    <div class="modal-col-right">
                        <div class="detail-card" style="height: 100%;">
                            <div class="detail-card-header">
                                <h3 class="detail-card-title">Informasi Tambahan</h3>
                            </div>
                            <div id="infoTambahan" style="margin-top: 16px;">
                                <div class="detail-text-row">
                                    <span class="detail-text-label">Status Magang:</span>
                                    <span class="detail-text-val" id="modalStatusMagang">-</span>
                                </div>
                                <div class="detail-text-row" style="margin-top: 14px;">
                                    <span class="detail-text-label">Tanggal Pengajuan:</span>
                                    <span class="detail-text-val" id="modalTanggal">-</span>
                                </div>
                                <div class="detail-text-row" style="margin-top: 14px;">
                                    <span class="detail-text-label">Catatan Pimpinan:</span>
                                    <span class="detail-text-val" id="modalCatatanPimpinan">-</span>
                                </div>
                                <div class="detail-text-row" style="margin-top: 14px;">
                                    <span class="detail-text-label">Catatan Admin:</span>
                                    <span class="detail-text-val" id="modalCatatanAdmin">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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
        // Sidebar Toggle
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

        // Modal Logic
        const modal = document.getElementById('detailModal');
        let currentMagang = null;

        function openModal(data) {
            currentMagang = data;
            
            document.getElementById('modalNama').innerText = data.nama_lengkap || 'Unknown';
            document.getElementById('modalEmail').innerText = data.email || '-';
            document.getElementById('modalNim').innerText = data.nim_siswa || '-';
            document.getElementById('modalId').innerText = 'IDM-' + data.id;
            document.getElementById('modalStatusPengajuan').innerText = (data.status_pengajuan || '').replace(/_/g, ' ').toUpperCase();
            document.getElementById('modalTempat').innerText = data.nama_tempat || '-';
            document.getElementById('modalAlamat').innerText = data.alamat_tempat || '-';
            document.getElementById('modalStatusVerifikasi').innerText = (data.status_verifikasi || '').toUpperCase();
            document.getElementById('modalStatusMagang').innerText = (data.status_magang || '').replace(/_/g, ' ').toUpperCase();
            document.getElementById('modalTanggal').innerText = data.created_at || '-';
            document.getElementById('modalCatatanPimpinan').innerText = data.catatan_pimpinan || 'Tidak ada';
            document.getElementById('modalCatatanAdmin').innerText = data.catatan_admin || 'Tidak ada';

            // Sections visibility
            const verifSection = document.getElementById('verifikasiAdminSection');
            const selesaiSection = document.getElementById('selesaiSection');

            if (data.status_pengajuan === 'disetujui_pimpinan' && data.status_verifikasi === 'pending') {
                verifSection.style.display = 'block';
            } else {
                verifSection.style.display = 'none';
            }

            if (data.status_magang === 'berlangsung') {
                selesaiSection.style.display = 'block';
            } else {
                selesaiSection.style.display = 'none';
            }

            modal.classList.add('show');
        }

        function prosesVerifikasi(status) {
            const catatan = document.getElementById('adminCatatan').value;
            if (status === 'ditolak' && !catatan) {
                alert('Catatan wajib diisi jika menolak.');
                return;
            }

            const formData = new FormData();
            formData.append('action', 'verifikasi');
            formData.append('magang_id', currentMagang.id);
            formData.append('status', status);
            formData.append('catatan', catatan);

            fetch('../../backend/magangAdmin_end.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if (data.status === 'success') location.reload();
            });
        }

        function prosesSelesai() {
            if (!confirm('Apakah Anda yakin ingin menyelesaikan program magang ini?')) return;

            const formData = new FormData();
            formData.append('action', 'selesai');
            formData.append('magang_id', currentMagang.id);

            fetch('../../backend/magangAdmin_end.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if (data.status === 'success') location.reload();
            });
        }

        function closeModal() {
            modal.classList.remove('show');
        }

        // Close on outside click
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal();
            }
        });
    </script>
</body>
</html>