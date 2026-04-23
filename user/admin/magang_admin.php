<?php
require_once __DIR__ . '/../../config/config.php';

// Auth: hanya admin (role_id = 5)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 5) {
    header("Location: ../../public/login/logAdmin.php?role=5&error=" . urlencode("Akses ditolak. Silakan login sebagai Admin."));
    exit;
}

// Fetch semua data magang + nama siswa
$query = mysqli_query($conn, "
    SELECT m.*, s.nama_lengkap, s.nim_siswa, pr.nama_program, u.email 
    FROM magang m 
    LEFT JOIN siswa s ON m.id_siswa = s.id_siswa 
    LEFT JOIN user u ON s.id_user = u.id_user 
    LEFT JOIN program pr ON s.id_program = pr.id_program
    ORDER BY m.tanggal_pengajuan DESC
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
    if ($m['status_magang'] === 'pending' || $m['status_magang'] === 'disetujui') $stats['pengajuan']++;
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
            <a href="sertifikat_admin.php" class="sidebar-link">
                <i class="fa-solid fa-certificate"></i>
                <span>Sertifikat</span>
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
                                            <td><?= htmlspecialchars($magang['nama_perusahaan']) ?></td>
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

    <!-- MODAL DETAIL MAGANG (Redesigned to match Pimpinan) -->
    <div class="modal-overlay" id="detailModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Detail Magang</h2>
                <button class="modal-close" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-divider"></div>
            
            <div class="modal-body">
                
                <!-- Identitas Siswa -->
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
                                <span class="detail-text-label">NIM:</span>
                                <span class="detail-text-val" id="modalNim">-</span>
                            </div>
                            <div class="detail-text-row" style="margin-top: 14px;">
                                <span class="detail-text-label">Program:</span>
                                <span class="detail-text-val" id="modalProgram">-</span>
                            </div>
                        </div>
                        <div class="identitas-col">
                            <div class="detail-text-row">
                                <span class="detail-text-label">Status Magang:</span>
                                <span class="detail-text-val" id="modalStatusMagang">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-grid-bottom">
                    <!-- Kolom Kiri -->
                    <div class="modal-col-left">
                        <!-- Detail Pengajuan -->
                        <div class="detail-card">
                            <div class="detail-card-header">
                                <h3 class="detail-card-title">Detail Pengajuan</h3>
                                <span class="badge-light-green" id="modalStatusAdmin">-</span>
                            </div>
                            <div style="margin-top: 16px;">
                                <div class="detail-text-row">
                                    <span class="detail-text-label">Industri/Hotel:</span>
                                    <span class="detail-text-val" id="modalTempat">-</span>
                                </div>
                                <div class="detail-text-row" style="margin-top: 14px;">
                                    <span class="detail-text-label">Posisi:</span>
                                    <span class="detail-text-val" id="modalPosisi">-</span>
                                </div>
                                <div class="detail-text-row" style="margin-top: 14px;">
                                    <span class="detail-text-label">Periode:</span>
                                    <span class="detail-text-val" id="modalPeriode">-</span>
                                </div>
                            </div>

                            <!-- Action: Verifikasi Akhir (Muncul jika sudah disetujui pimpinan) -->
                            <div id="verifAkhirSection" style="display:none; margin-top: 24px; padding-top: 16px; border-top: 1px solid #eee;">
                                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Catatan Verifikasi:</label>
                                <textarea id="adminCatatan" style="width: 100%; border: 1px solid #ddd; border-radius: 8px; padding: 12px; margin-bottom: 12px; min-height: 80px;"></textarea>
                                <div style="display: flex; gap: 10px; justify-content: center;">
                                    <button onclick="prosesVerifikasi('ditolak')" class="btn-detail" style="background: #ef4444; color: white;">Tolak</button>
                                    <button onclick="prosesVerifikasi('diterima')" class="btn-primary-yellow" style="margin-top:0;">Verifikasi & Aktifkan</button>
                                </div>
                            </div>
                        </div>

                        <!-- Laporan Kegiatan Harian -->
                        <div class="detail-card">
                            <div class="detail-card-header">
                                <h3 class="detail-card-title">Laporan & Dokumen Harian</h3>
                            </div>
                            <div id="laporanSection" style="margin-top: 16px;">
                                <div class="laporan-box">
                                    <i class="fa-solid fa-file-pdf laporan-icon"></i>
                                    <span class="laporan-title">Dokumen_Harian.pdf</span>
                                    <button class="btn-lihat" id="btnLihatLaporan"><i class="fa-solid fa-eye"></i> Lihat</button>
                                </div>
                                <div id="evaluasiLaporanBlock" style="margin-top: 20px;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                        <label id="labelEvaluasi" style="margin-bottom: 0; font-weight: 600; font-size: 14px;">Evaluasi Dokumen Harian:</label>
                                        <span id="statusLaporanBadge" class="badge-status-blue" style="font-size: 11px; padding: 4px 10px; display: none;">-</span>
                                    </div>
                                    <textarea id="evaluasiLaporan" class="form-input" style="width:100%; border:1px solid #ddd; border-radius:8px; padding:10px; min-height:60px;" placeholder="Berikan evaluasi terkait dokumen yang diunggah..."></textarea>
                                </div>
                                <div id="verifLaporanButtons" style="margin-top: 15px; display: flex; gap: 10px; justify-content: flex-end;">
                                    <button onclick="verifLaporan('ditolak')" class="btn-detail" style="background: #ef4444; color: white;">Tolak</button>
                                    <button onclick="verifLaporan('disetujui')" class="btn-primary-yellow" style="margin-top: 0; width: auto; padding: 8px 20px;">Verifikasi</button>
                                </div>
                            </div>
                            <div id="noLaporan" style="display:none; text-align:center; padding: 20px; color: #94A1B2;">
                                <i class="fa-solid fa-cloud-arrow-up" style="font-size: 32px; margin-bottom: 10px;"></i>
                                <p>Siswa belum mengunggah laporan.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan (Nilai) -->
                    <div class="modal-col-right">
                        <div class="detail-card" style="height: 100%;">
                            <div class="detail-card-header">
                                <h3 class="detail-card-title">Hasil Nilai Magang</h3>
                            </div>
                            <div class="nilai-magang-inputs" style="margin-top: 20px;">
                                <div id="gradeLockNotice" style="background: #FFF9C4; padding: 12px; border-radius: 8px; font-size: 13px; color: #856404; margin-bottom: 15px;">
                                    <i class="fa-solid fa-lock"></i> Input nilai dapat diisi setelah Dokumen Harian disetujui/diverifikasi.
                                </div>
                                <div class="grade-inputs-grid" id="gradeInputsGrid">
                                    <div class="input-group">
                                        <label>Job Knowledge</label>
                                        <input type="number" id="job_knowledge" step="0.01" min="1" max="4" placeholder="1.00 - 4.00">
                                    </div>
                                    <div class="input-group">
                                        <label>Quantity of Work</label>
                                        <input type="number" id="quantity_of_work" step="0.01" min="1" max="4" placeholder="1.00 - 4.00">
                                    </div>
                                    <div class="input-group">
                                        <label>Quality of Work</label>
                                        <input type="number" id="quality_of_work" step="0.01" min="1" max="4" placeholder="1.00 - 4.00">
                                    </div>
                                    <div class="input-group">
                                        <label>Character</label>
                                        <input type="number" id="character_val" step="0.01" min="1" max="4" placeholder="1.00 - 4.00">
                                    </div>
                                    <div class="input-group">
                                        <label>Personality</label>
                                        <input type="number" id="personality" step="0.01" min="1" max="4" placeholder="1.00 - 4.00">
                                    </div>
                                    <div class="input-group">
                                        <label>Courtesy</label>
                                        <input type="number" id="courtesy" step="0.01" min="1" max="4" placeholder="1.00 - 4.00">
                                    </div>
                                    <div class="input-group">
                                        <label>Personal Appearance</label>
                                        <input type="number" id="personal_appearance" step="0.01" min="1" max="4" placeholder="1.00 - 4.00">
                                    </div>
                                    <div class="input-group">
                                        <label>Attendance</label>
                                        <input type="number" id="attendance" step="0.01" min="1" max="4" placeholder="1.00 - 4.00">
                                    </div>
                                </div>

                                <button onclick="simpanNilai()" id="btnSimpanNilai" class="btn-primary-yellow" style="margin-top: 20px; width: 100%;">
                                    Simpan Nilai & Evaluasi
                                </button>
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
            document.getElementById('modalNim').innerText = data.nim_siswa || '-';
            document.getElementById('modalProgram').innerText = data.nama_program || '-';
            document.getElementById('modalStatusMagang').innerText = (data.status_magang || '').replace(/_/g, ' ').toUpperCase();
            
            document.getElementById('modalStatusAdmin').innerText = (data.status_admin || 'PENDING').toUpperCase();
            document.getElementById('modalTempat').innerText = data.nama_perusahaan || '-';
            document.getElementById('modalPosisi').innerText = data.posisi || '-';
            
            let tglMulai = data.tanggal_mulai ? new Date(data.tanggal_mulai).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : '';
            let tglSelesai = data.tanggal_selesai ? new Date(data.tanggal_selesai).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : '';
            document.getElementById('modalPeriode').innerText = (tglMulai && tglSelesai) ? `${tglMulai} - ${tglSelesai}` : '-';

            // Sections visibility
            const verifSection = document.getElementById('verifAkhirSection');
            const laporanSection = document.getElementById('laporanSection');
            const noLaporan = document.getElementById('noLaporan');
            const gradeLockNotice = document.getElementById('gradeLockNotice');
            const gradeInputsGrid = document.getElementById('gradeInputsGrid');
            const btnSimpanNilai = document.getElementById('btnSimpanNilai');

            // Show verifikasi if status_magang is 'disetujui' (by pimpinan) and status_admin is still 'pending'
            if (data.status_magang === 'disetujui' && data.status_admin === 'pending') {
                verifSection.style.display = 'block';
            } else {
                verifSection.style.display = 'none';
            }

            // Check for report
            if (data.file_laporan) {
                laporanSection.style.display = 'block';
                noLaporan.style.display = 'none';
                document.getElementById('btnLihatLaporan').onclick = () => {
                    window.open('../../assets/uploads/laporan/' + data.file_laporan, '_blank');
                };
                // Show filename if needed
                const titleEl = laporanSection.querySelector('.laporan-title');
                if(titleEl) titleEl.innerText = data.file_laporan;
            } else {
                laporanSection.style.display = 'none';
                noLaporan.style.display = 'block';
            }

            // Populate Scores if they exist (need to fetch from nilai_magang)
            fetch('../../backend/getNilaiMagang.php?magang_id=' + data.id_magang)
            .then(res => res.json())
            .then(resData => {
                const statusLaporan = data.status_laporan || 'pending';
                const badgeLaporan = document.getElementById('statusLaporanBadge');
                const verifButtons = document.getElementById('verifLaporanButtons');
                const evaluasiArea = document.getElementById('evaluasiLaporan');

                badgeLaporan.style.display = 'inline-block';
                if (statusLaporan === 'disetujui') {
                    badgeLaporan.className = 'badge-status-green';
                    badgeLaporan.innerText = 'Disetujui';
                    verifButtons.style.display = 'none';
                    evaluasiArea.readOnly = true;
                } else if (statusLaporan === 'ditolak') {
                    badgeLaporan.className = 'badge-status-red';
                    badgeLaporan.innerText = 'Ditolak (Butuh Revisi)';
                    verifButtons.style.display = 'none'; // Hides buttons after rejection
                    evaluasiArea.readOnly = true;
                } else {
                    badgeLaporan.className = 'badge-status-blue';
                    badgeLaporan.innerText = 'Menunggu Verifikasi';
                    verifButtons.style.display = 'flex';
                    evaluasiArea.readOnly = false;
                }

                if (statusLaporan === 'disetujui') {
                    gradeLockNotice.style.display = 'none';
                    gradeInputsGrid.style.opacity = '1';
                    gradeInputsGrid.style.pointerEvents = 'auto';
                    btnSimpanNilai.disabled = false;
                    btnSimpanNilai.style.opacity = '1';
                } else {
                    gradeLockNotice.style.display = 'block';
                    gradeInputsGrid.style.opacity = '0.5';
                    gradeInputsGrid.style.pointerEvents = 'none';
                    btnSimpanNilai.disabled = true;
                    btnSimpanNilai.style.opacity = '0.5';
                }

                if(resData.status === 'success' && resData.data) {
                    const evalText = resData.data.evaluasi_laporan || '';
                    document.getElementById('evaluasiLaporan').value = evalText;
                    
                    // Sembunyikan textarea jika sudah disetujui dan tidak ada evaluasi
                    if (statusLaporan === 'disetujui' && !evalText) {
                        document.getElementById('evaluasiLaporan').style.display = 'none';
                        document.getElementById('labelEvaluasi').style.display = 'none';
                    } else {
                        document.getElementById('evaluasiLaporan').style.display = 'block';
                        document.getElementById('labelEvaluasi').style.display = 'block';
                    }

                    document.getElementById('job_knowledge').value = resData.data.job_knowledge || '';
                    document.getElementById('quantity_of_work').value = resData.data.quantity_of_work || '';
                    document.getElementById('quality_of_work').value = resData.data.quality_of_work || '';
                    document.getElementById('character_val').value = resData.data.character_val || '';
                    document.getElementById('personality').value = resData.data.personality || '';
                    document.getElementById('courtesy').value = resData.data.courtesy || '';
                    document.getElementById('personal_appearance').value = resData.data.personal_appearance || '';
                    document.getElementById('attendance').value = resData.data.attendance || '';
                    document.getElementById('evaluasiLaporan').value = resData.data.evaluasi_laporan || '';
                } else {
                    const fields = ['job_knowledge', 'quantity_of_work', 'quality_of_work', 'character_val', 'personality', 'courtesy', 'personal_appearance', 'attendance', 'evaluasiLaporan'];
                    fields.forEach(f => {
                        const el = document.getElementById(f);
                        if(el) el.value = '';
                    });
                    document.getElementById('evaluasiLaporan').style.display = 'block';
                    document.getElementById('labelEvaluasi').style.display = 'block';
                }
            });

            modal.classList.add('show');
        }

        function verifLaporan(status) {
            const evaluasi = document.getElementById('evaluasiLaporan').value;
            if (status === 'ditolak' && !evaluasi) {
                alert('Catatan evaluasi wajib diisi jika menolak laporan.');
                return;
            }

            if (!confirm('Anda yakin ingin ' + (status === 'disetujui' ? 'menyetujui' : 'menolak') + ' laporan ini?')) return;

            const formData = new FormData();
            formData.append('action', 'verifikasi_laporan');
            formData.append('magang_id', currentMagang.id_magang);
            formData.append('status', status);
            formData.append('evaluasi', evaluasi);

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

        function simpanNilai() {
            const fields = ['job_knowledge', 'quantity_of_work', 'quality_of_work', 'character_val', 'personality', 'courtesy', 'personal_appearance', 'attendance'];
            const values = {};
            let isValid = true;

            fields.forEach(f => {
                const val = document.getElementById(f).value;
                if (!val) isValid = false;
                values[f] = val;
            });

            const evaluasi = document.getElementById('evaluasiLaporan').value;

            if (!isValid) {
                alert('Semua nilai wajib diisi (1.00 - 4.00).');
                return;
            }

            const formData = new FormData();
            formData.append('action', 'simpan_nilai');
            formData.append('magang_id', currentMagang.id_magang);
            fields.forEach(f => formData.append(f, values[f]));
            formData.append('evaluasi_laporan', evaluasi);

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

        function prosesVerifikasi(status) {
            const catatan = document.getElementById('adminCatatan').value;
            if (status === 'ditolak' && !catatan) {
                alert('Catatan wajib diisi jika menolak.');
                return;
            }

            const formData = new FormData();
            formData.append('action', 'verifikasi');
            formData.append('magang_id', currentMagang.id_magang);
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