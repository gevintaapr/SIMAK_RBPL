<?php
session_start();
require_once '../../config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 3) {
    header("Location: ../../public/login/logPengajar.php");
    exit;
}

// Ambil daftar pengajuan remedial yang pending, dikelompokkan per siswa
$query = mysqli_query($conn, "
    SELECT 
        s.id_siswa,
        s.nama_lengkap, 
        s.nim_siswa, 
        p.nama_program,
        COUNT(r.id_remedial) as jumlah_mapel,
        GROUP_CONCAT(r.alasan SEPARATOR ' | ') as gabungan_alasan,
        r.status_remedial,
        r.id_evaluasi
    FROM pengajuan_remedial r
    JOIN siswa s ON r.id_siswa = s.id_siswa
    JOIN program p ON s.id_program = p.id_program
    WHERE r.status_remedial != 'selesai'
    GROUP BY s.id_siswa
    ORDER BY r.tanggal_pengajuan DESC
");

// Siapkan data detail untuk setiap siswa (untuk modal JS nanti)
$details_data = [];
$query_details = mysqli_query($conn, "SELECT * FROM pengajuan_remedial WHERE status_remedial != 'selesai'");
while($row_det = mysqli_fetch_assoc($query_details)) {
    $details_data[$row_det['id_siswa']][] = $row_det;
}

// Nama Mapel Helper
$mapel_names = [
    'DUI1' => 'English for Hospitality',
    'DUI2' => 'Hotel & Cruise Ship Overview',
    'DUI3' => 'Food & Beverage Service Foundation',
    'DUI4' => 'Kitchen & Food Production Basics',
    'DUI5' => 'Housekeeping & Laundry Fundamentals',
    'DUI6' => 'Front Office & Guest Interaction',
    'DUI7' => 'Basic Safety Training (BST) & STCW',
    'DUI8' => 'Grooming & Professional Conduct',
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Remedial - HCTS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../style/dashboard_pengajar.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../style/popup_logout.css">
    <style>
        .rem-card { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); margin-bottom: 20px; border: 1px solid #edf2f7; }
        .rem-table { width: 100%; border-collapse: collapse; }
        .rem-table th { text-align: left; padding: 12px; border-bottom: 2px solid #edf2f7; color: #4a5568; font-weight: 600; font-size: 14px; }
        .rem-table td { padding: 15px 12px; border-bottom: 1px solid #edf2f7; vertical-align: middle; font-size: 14px; }
        .btn-input { background: #003B73; color: white; padding: 10px 18px; border-radius: 8px; border: none; cursor: pointer; font-size: 13px; font-weight: 500; transition: 0.3s; box-shadow: 0 2px 4px rgba(0,59,115,0.2); }
        .btn-input:hover { background: #002D5A; transform: translateY(-1px); }
        .modal { display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; backdrop-filter: blur(4px); }
        .modal-content { background: white; padding: 40px; border-radius: 20px; width: 480px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; color: #1E293B; }
        .form-control { width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px; font-family: inherit; font-size: 14px; transition: 0.3s; }
        .form-control:focus { border-color: #003B73; outline: none; box-shadow: 0 0 0 3px rgba(0,59,115,0.1); }
    </style>
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
            <a href="data_siswa.php" class="sidebar-link">
                <i class="fa-solid fa-users"></i>
                <span>Data Siswa</span>
            </a>
            <div class="sidebar-dropdown">
                <a href="#" class="sidebar-link dropdown-toggle open" id="evaluasiToggle">
                    <i class="fa-solid fa-clipboard-list"></i>
                    <span>Evaluasi</span>
                    <i class="fa-solid fa-chevron-down chevron-icon"></i>
                </a>
                <div class="dropdown-menu show" id="evaluasiMenu">
                    <a href="input_penilaian.php" class="dropdown-link">Input Penilaian</a>
                    <a href="riwayat_evaluasi.php" class="dropdown-link">Riwayat Evaluasi</a>
                    <a href="remedial_siswa.php" class="dropdown-link active">Kelola Remedial</a>
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
                    <span></span><span></span><span></span>
                </button>
                <span class="navbar-brand">HCTS Instructor Center</span>
            </div>
            <div class="navbar-right">
                <div class="admin-profile">
                    <div class="admin-avatar">FN</div>
                    <div class="admin-info">
                        <span class="admin-name">Pengajar</span>
                        <span class="admin-role">Instruktur</span>
                    </div>
                </div>
            </div>
        </header>

        <main class="page-content">
            <!-- Banner -->
            <section class="dashboard-banner">
                <div class="banner-content">
                    <h1 class="banner-title">Kelola Remedial</h1>
                    <div class="semester-pill">Antrean Ujian Perbaikan</div>
                </div>
            </section>

            <section class="full-width" style="margin-top: -30px; position: relative; z-index: 10;">
                <div class="rem-card">
                    <table class="rem-table">
                        <thead>
                            <tr>
                                <th>Nama Siswa</th>
                                <th style="text-align: center;">Jumlah Mata Pelajaran</th>
                                <th>Alasan (Singkat)</th>
                                <th>Status</th>
                                <th style="text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($query)): 
                                $siswa_id = $row['id_siswa'];
                                $list_mapel = $details_data[$siswa_id] ?? [];
                                $json_details = htmlspecialchars(json_encode($list_mapel), ENT_QUOTES, 'UTF-8');
                            ?>
                            <tr>
                                <td>
                                    <div style="font-weight: 700; color: #003B73;"><?= htmlspecialchars($row['nama_lengkap']) ?></div>
                                    <div style="font-size: 11px; color: #64748b;"><?= $row['nim_siswa'] ?> | <?= $row['nama_program'] ?></div>
                                </td>
                                <td style="text-align: center;">
                                    <span style="background: #E0F2FE; color: #0369A1; padding: 4px 12px; border-radius: 20px; font-weight: 700;">
                                        <?= $row['jumlah_mapel'] ?> Mapel
                                    </span>
                                </td>
                                <td style="font-size: 13px; max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #475569;">
                                    <?= htmlspecialchars($row['gabungan_alasan']) ?>
                                </td>
                                <td>
                                    <span class="badge" style="background: #FEF3C7; color: #92400E; padding: 5px 12px; border-radius: 15px; font-weight: 600; font-size: 11px;">
                                        <?= strtoupper($row['status_remedial']) ?>
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <button class="btn-input" onclick="openDetailModal('<?= $row['nama_lengkap'] ?>', <?= $json_details ?>)">
                                        <i class="fas fa-list-check"></i> Detail & Input
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php if(mysqli_num_rows($query) == 0): ?>
                            <tr><td colspan="6" style="text-align: center; color: #94a3b8; padding: 50px;">Tidak ada antrean pengajuan remedial.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <!-- Modal Detail Remedial -->
    <div id="detailModal" class="modal">
        <div class="modal-content" style="width: 650px;">
            <div style="text-align: center; margin-bottom: 20px;">
                <h3 style="color: #003B73; font-size: 22px;" id="detailSiswaName">Detail Remedial Siswa</h3>
                <p style="color: #64748b; font-size: 14px;">Masukkan nilai ujian perbaikan untuk setiap mata pelajaran.</p>
            </div>
            
            <div id="detailListContainer" style="max-height: 400px; overflow-y: auto; padding-right: 10px;">
                <!-- Populated via JS -->
            </div>
            
            <div style="margin-top: 25px; text-align: right;">
                <button type="button" onclick="closeDetailModal()" style="padding: 10px 25px; background: #F1F5F9; color: #64748B; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Tutup</button>
            </div>
        </div>
    </div>
    
    <style>
        .input-group-remedial { display: flex; align-items: center; gap: 15px; background: #F8FAFC; padding: 15px; border-radius: 12px; margin-bottom: 15px; border: 1px solid #E2E8F0; }
        .input-group-remedial .mapel-info { flex: 1; }
        .input-group-remedial .mapel-name { font-weight: 700; color: #1E293B; display: block; margin-bottom: 4px; }
        .input-group-remedial .mapel-score-old { font-size: 12px; color: #B91C1C; }
        .input-group-remedial .input-wrap { width: 100px; }
        .btn-save-mini { background: #003B73; color: white; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer; font-size: 12px; }
    </style>

    <!-- JS Handling -->
    <script>
        // Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const mainWrapper = document.getElementById('mainWrapper');
        const toggleBtn = document.getElementById('sidebarToggle');
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            mainWrapper.classList.toggle('sidebar-collapsed');
        });

        // Dropdown Toggle
        const evaluasiToggle = document.getElementById('evaluasiToggle');
        const evaluasiMenu = document.getElementById('evaluasiMenu');
        evaluasiToggle.addEventListener('click', (e) => {
            e.preventDefault();
            evaluasiToggle.classList.toggle('open');
            evaluasiMenu.classList.toggle('show');
        });

        const mapelNames = <?= json_encode($mapel_names) ?>;

        function openDetailModal(nama, details) {
            document.getElementById('detailSiswaName').textContent = `Remedial: ${nama}`;
            const container = document.getElementById('detailListContainer');
            container.innerHTML = '';

            details.forEach(item => {
                const mapelName = mapelNames[item.mapel_kode] || item.mapel_kode;
                const html = `
                    <div class="input-group-remedial" id="group_rem_${item.id_remedial}">
                        <div class="mapel-info">
                            <span class="mapel-name">${mapelName}</span>
                            <span class="mapel-score-old">Nilai Awal: <strong>${item.nilai_lama}</strong></span>
                        </div>
                        <div class="input-wrap">
                            <input type="number" id="input_val_${item.id_remedial}" class="form-control" placeholder="Nilai. . ." min="0" max="100" style="padding: 6px; font-size: 13px;">
                        </div>
                        <button class="btn-save-mini" onclick="submitSingleRemedial(${item.id_remedial})">
                            <i class="fas fa-check"></i>
                        </button>
                    </div>
                `;
                container.innerHTML += html;
            });

            document.getElementById('detailModal').style.display = 'flex';
        }

        function closeDetailModal() {
            document.getElementById('detailModal').style.display = 'none';
            // Refresh to update the main table grouping counts
            location.reload();
        }

        function submitSingleRemedial(id_rem) {
            const val = document.getElementById('input_val_' + id_rem).value;
            if(!val) { alert('Harap isi nilai baru!'); return; }

            const fd = new FormData();
            fd.append('action', 'update_nilai_remedial');
            fd.append('id_remedial', id_rem);
            fd.append('nilai_baru', val);

            fetch('../../backend/remedial_end.php', { method: 'POST', body: fd })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    // Berhasil, sembunyikan baris tersebut di modal
                    const row = document.getElementById('group_rem_' + id_rem);
                    row.style.opacity = '0.5';
                    row.style.pointerEvents = 'none';
                    row.style.background = '#F1F5F9';
                } else {
                    alert('Error: ' + data.message);
                }
            });
        }
    </script>
</body>
</html>

</body>
</html>
