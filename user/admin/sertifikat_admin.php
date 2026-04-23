<?php
session_start();
require_once __DIR__ . '/../../config/config.php';

// Auth: hanya admin (role_id = 5)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 5) {
    header("Location: ../../public/login/logAdmin.php?role=5&error=" . urlencode("Akses ditolak. Silakan login sebagai Admin."));
    exit;
}

// Fetch Siswa Selesai Magang (status_magang = 'selesai' dan no_sertifikat IS NULL)
$query_selesai = mysqli_query($conn, "
    SELECT m.*, s.nama_lengkap, s.nim_siswa, p.tanggal_lahir, pr.nama_program, 
           n.job_knowledge, n.quantity_of_work, n.quality_of_work, n.character_val, 
           n.personality, n.courtesy, n.personal_appearance, n.attendance 
    FROM magang m 
    JOIN siswa s ON m.id_siswa = s.id_siswa 
    JOIN pendaftaran p ON s.id_pendaftaran = p.id_pendaftaran
    LEFT JOIN program pr ON p.id_program = pr.id_program
    LEFT JOIN nilai_magang n ON m.id_magang = n.id_magang
    WHERE m.status_magang = 'selesai' AND m.no_sertifikat IS NULL
    ORDER BY m.id_magang DESC
");
$data_magang_selesai = mysqli_fetch_all($query_selesai, MYSQLI_ASSOC);

// Fetch Riwayat (no_sertifikat IS NOT NULL)
$query_riwayat = mysqli_query($conn, "
    SELECT m.*, s.nama_lengkap, pr.nama_program 
    FROM magang m 
    JOIN siswa s ON m.id_siswa = s.id_siswa 
    JOIN pendaftaran p ON s.id_pendaftaran = p.id_pendaftaran
    LEFT JOIN program pr ON p.id_program = pr.id_program
    WHERE m.no_sertifikat IS NOT NULL
    ORDER BY m.id_magang DESC
");
$riwayat_sertifikat = mysqli_fetch_all($query_riwayat, MYSQLI_ASSOC);

// Get Next Certificate Sequence
$query_seq = mysqli_query($conn, "SELECT COUNT(*) as total FROM magang WHERE no_sertifikat IS NOT NULL");
$row_seq = mysqli_fetch_assoc($query_seq);
$next_cert_seq = (int)$row_seq['total'] + 1;

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat - HCTS Admin Center</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;800&family=Poppins:wght@400;500;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../style/sertifikat_admin.css?v=<?= time() ?>">
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
            <a href="sertifikat_admin.php" class="sidebar-link active">
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
                <button class="notif-btn" aria-label="Notifikasi">
                    <i class="fa-regular fa-bell"></i>
                    <span class="notif-badge">3</span>
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
                    <h1 class="banner-title">Sertifikat</h1>
                </div>
            </section>

            <!-- Section 1: Siswa Selesai Magang -->
            <section class="pendaftaran-list-section">
                <div class="pendaftaran-list-card">
                    <div class="table-header">
                        <h2 class="table-title">Data Terbaru Siswa Selesai Magang</h2>
                    </div>
                    <div class="filter-controls">
                        <div class="search-box">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" placeholder="Cari Nama Siswa...">
                        </div>
                        <select class="filter-select">
                            <option>Semua Program</option>
                            <option>Cruise Ship Deck Kadet</option>
                            <option>Hotel F&B Service</option>
                        </select>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>NAMA</th>
                                    <th>PROGRAM</th>
                                    <th>TEMPAT</th>
                                    <th>STATUS SERTIFIKAT</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($data_magang_selesai)): ?>
                                    <tr><td colspan="5" style="text-align:center;">Belum ada siswa yang menyelesaikan magang.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($data_magang_selesai as $row): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                                        <td><?= htmlspecialchars($row['nama_program'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($row['nama_perusahaan']) ?></td>
                                        <td><span class="badge badge-status-blue">Belum Diterbitkan</span></td>
                                        <td><button class="btn-detail" onclick='openModal(<?= json_encode($row) ?>)'>Detail</button></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Section 2: Riwayat Penerbitan -->
            <section class="pendaftaran-list-section" style="padding-top: 0;">
                <div class="pendaftaran-list-card">
                    <div class="table-header">
                        <h2 class="table-title">Riwayat Penerbitan Sertifikat</h2>
                    </div>
                    <div class="filter-controls">
                        <div class="search-box">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" placeholder="Cari Nama Siswa...">
                        </div>
                        <select class="filter-select">
                            <option>Semua Program</option>
                        </select>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>NAMA</th>
                                    <th>PROGRAM</th>
                                    <th>TEMPAT</th>
                                    <th>STATUS SERTIFIKAT</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($riwayat_sertifikat)): ?>
                                    <tr><td colspan="5" style="text-align:center;">Belum ada riwayat penerbitan.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($riwayat_sertifikat as $row): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                                        <td><?= htmlspecialchars($row['nama_program'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($row['nama_perusahaan']) ?></td>
                                        <td><span class="badge badge-status-green">Terbit (<?= htmlspecialchars($row['no_sertifikat']) ?>)</span></td>
                                        <td><button class="btn-detail">Detail</button></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
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
                        <li><i class="fa-solid fa-location-dot"></i><span>123 Maritime Avenue, Harbor District</span></li>
                        <li><i class="fa-solid fa-phone"></i><span>+1 (555) 123-4567</span></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p class="footer-copy">&copy; 2025 HCTS International. All rights reserved.</p>
                <div class="footer-legal">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                </div>
            </div>
        </footer>
    </div>

    <!-- ===== MODAL: INPUT DATA SERTIFIKAT ===== -->
    <div class="modal-overlay" id="sertifikatModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Input Data Sertifikat</h2>
                <button class="modal-close" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-divider"></div>
            <div class="modal-body">
                <div class="modal-grid-bottom">
                    <div class="modal-col-left">
                        <div class="detail-card">
                            <div class="detail-card-header">
                                <h3 class="detail-card-title">Data Siswa</h3>
                            </div>
                            <div class="identitas-grid">
                                <div class="detail-text-row"><span class="detail-text-label">Nama Lengkap:</span> <span class="detail-text-val" id="valNama">-</span></div>
                                <div class="detail-text-row"><span class="detail-text-label">ID Siswa:</span> <span class="detail-text-val" id="valIdSiswa">-</span></div>
                                <div class="detail-text-row"><span class="detail-text-label">Tanggal Lahir:</span> <span class="detail-text-val" id="valTglLahir">-</span></div>
                                <div class="detail-text-row"><span class="detail-text-label">Program:</span> <span class="detail-text-val" id="valProgram">-</span></div>
                            </div>
                        </div>
                        <div class="detail-card">
                            <div class="detail-card-header">
                                <h3 class="detail-card-title">Rekap Nilai OJT</h3>
                            </div>
                            <div class="identitas-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                                <div class="detail-text-row"><span class="detail-text-label" style="width:120px;">Job Knowledge:</span> <span class="detail-text-val" id="valJobKnowledge">-</span></div>
                                <div class="detail-text-row"><span class="detail-text-label" style="width:120px;">Quantity:</span> <span class="detail-text-val" id="valQuantity">-</span></div>
                                <div class="detail-text-row"><span class="detail-text-label" style="width:120px;">Quality:</span> <span class="detail-text-val" id="valQuality">-</span></div>
                                <div class="detail-text-row"><span class="detail-text-label" style="width:120px;">Character:</span> <span class="detail-text-val" id="valCharacter">-</span></div>
                                <div class="detail-text-row"><span class="detail-text-label" style="width:120px;">Personality:</span> <span class="detail-text-val" id="valPersonality">-</span></div>
                                <div class="detail-text-row"><span class="detail-text-label" style="width:120px;">Courtesy:</span> <span class="detail-text-val" id="valCourtesy">-</span></div>
                                <div class="detail-text-row"><span class="detail-text-label" style="width:120px;">Appearance:</span> <span class="detail-text-val" id="valAppearance">-</span></div>
                                <div class="detail-text-row"><span class="detail-text-label" style="width:120px;">Attendance:</span> <span class="detail-text-val" id="valAttendance">-</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-col-right">
                        <div class="detail-card">
                            <div class="detail-card-header">
                                <h3 class="detail-card-title">Data Sertifikat Resmi</h3>
                            </div>
                             <div class="input-group">
                                 <label>Nomor Sertifikat</label>
                                 <div style="display: flex; gap: 8px;">
                                     <input type="text" id="noSertifikat" placeholder="Ex: 001/123/HCTS/SL-1/2026" style="flex: 1;">
                                     <button class="btn-detail" onclick="refreshCertNo()" style="padding: 8px 12px;"><i class="fa-solid fa-arrows-rotate"></i></button>
                                 </div>
                             </div>
                             <div class="input-group">
                                 <label>Tanggal Terbit</label>
                                 <input type="date" id="tglTerbit" value="<?= date('Y-m-d') ?>">
                             </div>
                             <div class="input-group">
                                 <label>Nama Penandatangan</label>
                                 <input type="text" id="namaTtd" value="Agus Handoyo, S.E.">
                             </div>
                             <div class="input-group">
                                 <label>Jabatan Penandatangan</label>
                                 <input type="text" id="jabatanTtd" value="Direktur Utama HCTS">
                             </div>
                             <button class="btn-primary-yellow" onclick="terbitkanSertifikat()">Terbitkan Sertifikat</button>
                        </div>
                    </div>
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

        const modal = document.getElementById('sertifikatModal');
        let currentMagangId = null;
        let nextCertSeq = <?= $next_cert_seq ?>;

         function openModal(data) {
            currentMagangId = data.id_magang;
            window.currentModalData = data; // Store for refresh
            document.getElementById('valNama').innerText = data.nama_lengkap;
            document.getElementById('valIdSiswa').innerText = data.nim_siswa || ('HC' + data.id_siswa);
            document.getElementById('valTglLahir').innerText = data.tanggal_lahir || '-';
            document.getElementById('valProgram').innerText = data.nama_program || '-';
            document.getElementById('valJobKnowledge').innerText = data.job_knowledge || '0';
            document.getElementById('valQuantity').innerText = data.quantity_of_work || '0';
            document.getElementById('valQuality').innerText = data.quality_of_work || '0';
            document.getElementById('valCharacter').innerText = data.character_val || '0';
            document.getElementById('valPersonality').innerText = data.personality || '0';
            document.getElementById('valCourtesy').innerText = data.courtesy || '0';
            document.getElementById('valAppearance').innerText = data.personal_appearance || '0';
            document.getElementById('valAttendance').innerText = data.attendance || '0';
            
            // Auto-generate Unique ID
            refreshCertNo();
            
            modal.classList.add('show');
        }

        function generateUniqueId(data) {
            const now = new Date();
            const year = now.getFullYear();
            
            // 1. Sequence Number (001)
            const seqStr = String(nextCertSeq).padStart(3, '0');
            
            // 2. Last 3 digits of NIM
            let nim3 = '000';
            if (data.nim_siswa) {
                nim3 = data.nim_siswa.toString().slice(-3);
            } else {
                // Fallback to last 3 digits of id_siswa if NIM is missing
                nim3 = String(data.id_siswa).padStart(3, '0').slice(-3);
            }
            
            // Format: {SEQ}/{NIM_3}/HCTS/SL-1/{YEAR}
            return `${seqStr}/${nim3}/HCTS/SL-1/${year}`;
        }

        function refreshCertNo() {
            if (window.currentModalData) {
                document.getElementById('noSertifikat').value = generateUniqueId(window.currentModalData);
            }
        }

        function terbitkanSertifikat() {
            const noSertifikat = document.getElementById('noSertifikat').value;
            if (!noSertifikat) {
                alert('Nomor sertifikat wajib diisi.');
                return;
            }

            const formData = new FormData();
            formData.append('magang_id', currentMagangId);
            formData.append('no_sertifikat', noSertifikat);

            fetch('../../backend/terbitkanSertifikat.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if (data.status === 'success') {
                    nextCertSeq++; // Increment for next issuance in same session
                    location.reload();
                }
            });
        }

        // Modal Close logic
        function closeModal() { modal.classList.remove('show'); }

        function showLogoutPopup(e) {
            if(e) e.preventDefault();
            document.getElementById('logoutPopup').style.display = 'flex';
        }
        function closeLogoutPopup() {
            document.getElementById('logoutPopup').style.display = 'none';
        }

        // Close on outside click
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal();
            }
        });
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