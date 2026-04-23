<?php
session_start();
require_once __DIR__ . '/../../config/config.php';

// Auth: hanya admin (role_id = 5)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 5) {
    header("Location: ../../public/login/logAdmin.php?role=5&error=" . urlencode("Akses ditolak. Silakan login sebagai Admin."));
    exit;
}

// Fetch Applicants
$query = mysqli_query($conn, "
    SELECT pt.*, s.nama_lengkap, s.nim_siswa, p.tanggal_lahir, pr.nama_program,
           m.no_sertifikat, e.rata_rata as nilai_akhir
    FROM program_taiwan pt
    JOIN siswa s ON pt.id_siswa = s.id_siswa
    JOIN pendaftaran p ON s.id_pendaftaran = p.id_pendaftaran
    LEFT JOIN program pr ON p.id_program = pr.id_program
    LEFT JOIN magang m ON s.id_siswa = m.id_siswa AND m.no_sertifikat IS NOT NULL
    LEFT JOIN evaluasi e ON s.id_siswa = e.id_siswa
    GROUP BY pt.id_taiwan
    ORDER BY pt.create_at DESC
");
$applicants = mysqli_fetch_all($query, MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Program Taiwan - HCTS Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;800&family=Poppins:wght@400;500;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../style/sertifikat_admin.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../style/popup_logout.css">
    <style>
        .badge-status-mitra { background-color: #e0f2fe; color: #0369a1; }
        .badge-status-lolos { background-color: #d1fae5; color: #059669; }
        .badge-status-ditolak { background-color: #fee2e2; color: #991b1b; }
        .action-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        .btn-export { background: #059669; color: white; padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 14px; transition: 0.3s; display: flex; align-items: center; gap: 10px; }
        .btn-export:hover { background: #047857; }
        .checkbox-cell { width: 40px; text-align: center; }
        input[type="checkbox"] { width: 18px; height: 18px; cursor: pointer; }
    </style>
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
            <a href="manajemen_form.php" class="sidebar-link">
                <i class="fa-solid fa-file-pen"></i>
                <span>Manajemen Form</span>
            </a>
            <a href="manage_user.php" class="sidebar-link">
                <i class="fa-solid fa-users-gear"></i>
                <span>Manajemen Pengguna</span>
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
        <header class="navbar">
            <div class="navbar-left">
                <button class="sidebar-toggle" id="sidebarToggle"><span></span><span></span><span></span></button>
                <span class="navbar-brand">HCTS Admin Center</span>
            </div>
            <div class="navbar-right">
                <a href="dashboard_admin.php" style="margin-right: 15px; color: #003B73; text-decoration: none; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
                <div class="admin-profile">
                    <div class="admin-avatar">AD</div>
                    <div class="admin-info"><span class="admin-name">Admin</span><span class="admin-role">Super Admin</span></div>
                </div>
            </div>
        </header>

        <main class="page-content">
            <section class="dashboard-banner">
                <div class="banner-content"><h1 class="banner-title">Manajemen Program Taiwan</h1></div>
            </section>

            <section class="pendaftaran-list-section">
                <div class="pendaftaran-list-card">
                    <div class="action-bar">
                        <h2 class="table-title">Daftar Peminat Program</h2>
                        <button class="btn-export" onclick="exportSelected()">
                            <i class="fa-solid fa-file-pdf"></i> Ekspor PDF Terpilih
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="checkbox-cell"><input type="checkbox" id="selectAll"></th>
                                    <th>NAMA SISWA</th>
                                    <th>PROGRAM</th>
                                    <th>STATUS</th>
                                    <th>TGL DAFTAR</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($applicants)): ?>
                                    <tr><td colspan="6" style="text-align:center;">Belum ada pendaftar program Taiwan.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($applicants as $row): ?>
                                    <tr>
                                        <td class="checkbox-cell"><input type="checkbox" class="student-check" value="<?= $row['id_taiwan'] ?>"></td>
                                        <td>
                                            <div style="font-weight:600;"><?= htmlspecialchars($row['nama_lengkap']) ?></div>
                                            <div style="font-size:11px; color:#666;">NIM: <?= htmlspecialchars($row['nim_siswa'] ?? '-') ?></div>
                                        </td>
                                        <td><?= htmlspecialchars($row['nama_program'] ?? '-') ?></td>
                                        <td>
                                            <?php
                                            $st = $row['status'];
                                            $badges = [
                                                'berminat' => ['Berminat', 'badge-status-blue'],
                                                'diajukan_mitra' => ['Diajukan ke Mitra', 'badge-status-mitra'],
                                                'lolos' => ['Lolos', 'badge-status-lolos'],
                                                'ditolak' => ['Ditolak', 'badge-status-ditolak']
                                            ];
                                            echo '<span class="badge '.$badges[$st][1].'">'.$badges[$st][0].'</span>';
                                            ?>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($row['create_at'])) ?></td>
                                        <td><button class="btn-detail" onclick='openModal(<?= json_encode($row) ?>)'>Detail</button></td>
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

    <!-- Modal Update Status -->
    <div class="modal-overlay" id="taiwanModal">
        <div class="modal-content" style="width: 500px;">
            <div class="modal-header">
                <h2 class="modal-title">Detail & Update Status</h2>
                <button class="modal-close" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-divider"></div>
            <div class="modal-body">
                <div class="detail-card">
                    <h3 id="mNama" style="margin-bottom:15px; color:#003B73;">Nama Siswa</h3>
                    <div class="input-group">
                        <label>Update Status Program</label>
                        <select id="mStatus" class="filter-select" style="width:100%;">
                            <option value="berminat">Berminat (Menunggu Konfirmasi Admin)</option>
                            <option value="diajukan_mitra">Diajukan ke Mitra (Menunggu Konfirmasi Taiwan)</option>
                            <option value="lolos">Lolos / Diterima</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                    </div>
                    <button class="btn-primary-yellow" onclick="updateStatus()">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('sidebarToggle').addEventListener('click', () => {
            document.getElementById('sidebar').classList.toggle('collapsed');
            document.getElementById('mainWrapper').classList.toggle('sidebar-collapsed');
        });

        document.getElementById('selectAll').addEventListener('change', function() {
            document.querySelectorAll('.student-check').forEach(cb => cb.checked = this.checked);
        });

        let currentId = null;
        function openModal(data) {
            currentId = data.id_taiwan;
            document.getElementById('mNama').innerText = data.nama_lengkap;
            document.getElementById('mStatus').value = data.status;
            document.getElementById('taiwanModal').classList.add('show');
        }
        function closeModal() { document.getElementById('taiwanModal').classList.remove('show'); }

        function updateStatus() {
            const status = document.getElementById('mStatus').value;
            fetch('../../backend/updateStatusTaiwan.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id_taiwan=${currentId}&status=${status}`
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if(data.status === 'success') location.reload();
            });
        }

        function exportSelected() {
            const selected = Array.from(document.querySelectorAll('.student-check:checked')).map(cb => cb.value);
            if(selected.length === 0) {
                alert('Pilih siswa yang ingin diekspor terlebih dahulu.');
                return;
            }
            window.open(`export_taiwan.php?ids=${selected.join(',')}`, '_blank');
        }

        function showLogoutPopup(e) { e.preventDefault(); document.getElementById('logoutPopup').style.display = 'flex'; }
        function closeLogoutPopup() { document.getElementById('logoutPopup').style.display = 'none'; }
    </script>

    <!-- Logout Popup -->
    <div id="logoutPopup" class="popup-overlay" style="display: none;">
        <div class="popup-wrapper">
            <div class="popup-content">
                <button class="btn-close-popup" onclick="closeLogoutPopup()">&times;</button>
                <div class="popup-body"><h3>Apakah Anda Yakin Ingin Keluar?</h3></div>
                <div class="popup-footer">
                    <a href="../../app/logout.php" class="btn-yakin">Yakin</a>
                    <button class="btn-tidak" onclick="closeLogoutPopup()">Tidak</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>