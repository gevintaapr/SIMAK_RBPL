<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 4) {
    header("Location: ../../public/login/logPimpinan.php?role=4&error=" . urlencode("Akses ditolak."));
    exit;
}
require_once '../../config/database.php';

$db = new Database();
$conn = $db->getConnection();

// Fetch pending pendaftaran (waiting for pimpinan)
$stmt_p = $conn->query("SELECT * FROM pendaftaran WHERE status_approval = 'menunggu_pimpinan' ORDER BY id_pendaftaran DESC");
$pendaftaran_pending = $stmt_p->fetchAll();
$count_pendaftaran = count($pendaftaran_pending);

// Fetch pending magang applications
$stmt_m = $conn->query("SELECT m.*, s.nama_lengkap as name, u.email FROM magang m LEFT JOIN `user` u ON m.user_id = u.id_user LEFT JOIN `siswa` s ON u.id_user = s.id_user WHERE m.status_pengajuan = 'pending' ORDER BY m.created_at DESC");
$magang_pending = $stmt_m->fetchAll();
$count_magang = count($magang_pending);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Persetujuan Menunggu Tindakan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../style/approval.css?v=<?= time() ?>">
</head>
<body>

    <!-- ===== SIDEBAR ===== -->
    <aside class="sidebar collapsed" id="sidebar">
        <div class="sidebar-header">
            <span class="sidebar-logo">HCTS</span>
        </div>
        <nav class="sidebar-nav">
            <a href="dashboard_pimpinan.php" class="sidebar-link">
                <i class="fa-solid fa-gauge-high"></i>
                <span>Dashboard</span>
            </a>
            <a href="#" class="sidebar-link">
                <i class="fa-solid fa-users"></i>
                <span>Daftar Siswa</span>
            </a>
            <a href="#" class="sidebar-link active">
                <i class="fa-solid fa-check-double"></i>
                <span>Approval</span>
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
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <span class="navbar-brand">HCTS Executive</span>
            </div>
            <div class="navbar-right">
                <button class="notif-btn" id="notifBtn" aria-label="Notifikasi">
                    <i class="fa-regular fa-bell"></i>
                </button>
                <div class="admin-profile">
                    <div class="admin-avatar">GN</div>
                    <div class="admin-info">
                        <span class="admin-name">Pimpinan</span>
                        <span class="admin-role">Direktur Utama</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- ===== PAGE CONTENT ===== -->
        <main class="page-content">

            <!-- Hero / Dashboard Banner -->
            <section class="dashboard-banner">
                <div class="banner-content">
                    <h1 class="banner-title">Approval</h1>
                </div>
            </section>

            <!-- Approval List Section -->
            <section class="approval-list-section">
                <div class="approval-list-card">
                    <div class="table-header">
                        <h2 class="table-title">Persetujuan Menunggu Tindakan</h2>
                    </div>
                    
                    <div class="custom-tabs">
                        <button class="tab-btn active" onclick="openTab('pendaftaran')">Pendaftaran (<?= $count_pendaftaran ?>)</button>
                        <button class="tab-btn" onclick="openTab('evaluasi')">Evaluasi (3)</button>
                        <button class="tab-btn" onclick="openTab('magang')">Magang (<?= $count_magang ?>)</button>
                    </div>

                    <!-- TAB PANES -->
                    <!-- Pendaftaran -->
                    <div id="pendaftaran" class="tab-pane active">
                        <div class="approval-list">
                            <?php if (empty($pendaftaran_pending)): ?>
                                <div class="empty-state" style="text-align:center; padding: 40px;">
                                    <p>Tidak ada pendaftaran yang menunggu approval pimpinan.</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($pendaftaran_pending as $p): ?>
                                    <div class="approval-card">
                                        <div class="ac-left">
                                            <h3 class="ac-name"><?= htmlspecialchars($p['nama_cs']) ?></h3>
                                            <p class="ac-dept"><?= htmlspecialchars($p['program']) ?></p>
                                        </div>
                                        <div class="ac-center">
                                            <span class="ac-badge">Menunggu Approval</span>
                                            <span class="ac-date">| Wawancara Selesai Pada: <?= date('d M Y', strtotime($p['jadwal_wawancara'])) ?></span>
                                        </div>
                                        <div class="ac-right">
                                            <a href="approval_detail_pendaftaran.php?id=<?= $p['id_pendaftaran'] ?>" class="btn-primary-dark">Detail</a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Evaluasi -->
                    <div id="evaluasi" class="tab-pane">
                        <div class="approval-list">
                            <div class="approval-card">
                                <div class="ac-left">
                                    <h3 class="ac-name">Alexander Wibowo</h3>
                                    <p class="ac-dept">F&B Services</p>
                                </div>
                                <div class="ac-center">
                                    <span class="ac-badge">Menunggu Approval</span>
                                    <span class="ac-date">| Periode: 1-2025</span>
                                </div>
                                <div class="ac-right">
                                    <button class="btn-primary-dark" onclick="openModal('evalModal')">Detail</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Magang -->
                    <div id="magang" class="tab-pane">
                        <div class="approval-list">
                            <?php if (empty($magang_pending)): ?>
                                <div class="empty-state" style="text-align:center; padding: 40px;">
                                    <p>Tidak ada pengajuan magang yang menunggu tindakan.</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($magang_pending as $magang): ?>
                                    <div class="approval-card">
                                        <div class="ac-left">
                                            <h3 class="ac-name"><?= htmlspecialchars($magang['name'] ?? '-') ?></h3>
                                            <p class="ac-dept"><?= htmlspecialchars($magang['nama_tempat']) ?></p>
                                        </div>
                                        <div class="ac-center">
                                            <span class="ac-badge">Menunggu Approval</span>
                                            <span class="ac-date">| Diajukan: <?= date('d M Y', strtotime($magang['created_at'])) ?></span>
                                        </div>
                                        <div class="ac-right">
                                            <button class="btn-primary-dark" onclick='openMagangModal(<?= json_encode($magang) ?>)'>Detail</button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
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
                        <a href="#" class="social-btn" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="social-btn" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="social-btn" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
                        <a href="#" class="social-btn" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                    </div>
                </div>

                <div class="footer-col">
                    <h4 class="footer-heading">Aksi Cepat</h4>
                    <ul class="footer-links">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Our Programs</a></li>
                        <li><a href="#">Admission Process</a></li>
                        <li><a href="#">Career Opportunities</a></li>
                        <li><a href="#">Student Stories</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4 class="footer-heading">Program Kami</h4>
                    <ul class="footer-links">
                        <li><a href="#">Hotel Management</a></li>
                        <li><a href="#">Cruise Ship Operations</a></li>
                        <li><a href="#">Culinary Arts</a></li>
                        <li><a href="#">Hospitality Services</a></li>
                        <li><a href="#">Tourism Management</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4 class="footer-heading">Kontak Kami</h4>
                    <ul class="footer-contact">
                        <li><i class="fa-solid fa-location-dot"></i> <span>123 Maritime Avenue, Harbor District, HD 12345</span></li>
                        <li><i class="fa-solid fa-phone"></i> <span>+1 (555) 123-4567</span></li>
                        <li><i class="fa-regular fa-envelope"></i> <span>info@hcts.edu</span></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p class="footer-copy">&copy; 2025 HCTS International. All rights reserved.</p>
                <div class="footer-legal">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                    <a href="#">Cookie Policy</a>
                </div>
            </div>
        </footer>

    </div>

    <!-- ==============================================
         MODALS
         ============================================== -->

    <!-- Evaluasi Modal -->
    <div class="modal-overlay" id="evalModal">
        <div class="modal-content">
            <button class="btn-close" onclick="closeModal('evalModal')"><i class="fa-solid fa-xmark"></i></button>
            <h2 class="modal-header">Detail Evaluasi</h2>
            
            <div class="identitas-box">
                <h3 class="identitas-title">Identitas Siswa</h3>
                <div class="identitas-grid">
                    <div class="info-row"><span>ID Siswa</span> <span>HC123</span></div>
                    <div class="info-row"><span>Program:</span> <span>F&B Service</span></div>
                    <div class="info-row"><span>Nama Lengkap:</span> <span>Alexander Wibowo</span></div>
                    <div class="info-row"><span>Periode:</span> <span>1-2025</span></div>
                </div>
            </div>

            <div class="section-box">
                <div class="section-header">
                    <h3 class="section-title">Hasil Evaluasi</h3>
                    <span class="status-pill">Menunggu Persetujuan</span>
                </div>
                
                <table class="eval-table">
                    <thead>
                        <tr>
                            <th>Mata Pelajaran (Subject)</th>
                            <th>Nilai (0-100)</th>
                            <th>Grade</th>
                            <th>Evaluasi Pengajar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>English Basic</td>
                            <td><input type="text" class="input-nilai" value="88" readonly></td>
                            <td><strong>A</strong></td>
                            <td><input type="text" class="input-text" value="Memiliki kemampuan dasar bahasa In..." readonly></td>
                        </tr>
                        <tr>
                            <td>Food & Beverage Service</td>
                            <td><input type="text" class="input-nilai" value="89" readonly></td>
                            <td><strong>A</strong></td>
                            <td><input type="text" class="input-text" value="Menguasai standar pelayanan F&B den..." readonly></td>
                        </tr>
                        <tr>
                            <td>Housekeeping</td>
                            <td><input type="text" class="input-nilai" value="82" readonly></td>
                            <td><strong>B</strong></td>
                            <td><input type="text" class="input-text" value="Memahami prosedur dasar housekeepi..." readonly></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="modal-footer">
                <button class="btn-approve" style="width:auto;min-width:250px;">Setujui Hasil Evaluasi</button>
            </div>
        </div>
    </div>

    <!-- Magang Modal -->
    <div class="modal-overlay" id="magangModal">
        <div class="modal-content">
            <button class="btn-close" onclick="closeModal('magangModal')"><i class="fa-solid fa-xmark"></i></button>
            <h2 class="modal-header">Detail Magang</h2>

            <div class="identitas-box">
                <h3 class="identitas-title">Identitas Siswa</h3>
                <div class="identitas-grid">
                    <div class="info-row"><span>Nama Lengkap:</span> <span id="mNama">-</span></div>
                    <div class="info-row"><span>Email:</span> <span id="mEmail">-</span></div>
                    <div class="info-row"><span>ID Magang:</span> <span id="mId">-</span></div>
                </div>
            </div>

            <div class="magang-grid">
                <div class="magang-col" style="width: 100%;">
                    <div class="section-box" style="margin-bottom:0;">
                        <div class="section-header">
                            <h3 class="section-title">Detail Pengajuan</h3>
                            <span class="status-pill">Menunggu Persetujuan</span>
                        </div>
                        <div class="magang-details">
                            <div class="md-row"><span>Nama Perusahaan/Hotel:</span> <span id="mTempat">-</span></div>
                            <div class="md-row"><span>Alamat:</span> <span id="mAlamat">-</span></div>
                        </div>
                        
                        <div class="approval-action-box" style="margin-top: 24px; padding-top: 16px; border-top: 1px solid #eee;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 500;">Catatan (Wajib jika menolak):</label>
                            <textarea id="pimpinanCatatan" style="width: 100%; border: 1px solid #ddd; border-radius: 4px; padding: 10px; margin-bottom: 16px; min-height: 80px;"></textarea>
                            
                            <div class="magang-actions" style="display: flex; gap: 15px; justify-content: flex-end;">
                                <button class="btn-magang-reject" onclick="prosesApproveMagang('ditolak_pimpinan')" style="background: #ef4444; color: white; border: none; padding: 10px 24px; border-radius: 6px; cursor: pointer;">Tolak</button>
                                <button class="btn-magang-approve" onclick="prosesApproveMagang('disetujui_pimpinan')" style="background: #1e293b; color: white; border: none; padding: 10px 24px; border-radius: 6px; cursor: pointer;">Setujui</button>
                            </div>
                        </div>
                    </div>
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

        // Tabs Logic
        function openTab(tabId) {
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));
            
            event.target.classList.add('active');
            document.getElementById(tabId).classList.add('active');
        }

        // Modal Logic
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('active');
        }
        
        let currentMagang = null;
        function openMagangModal(data) {
            currentMagang = data;
            document.getElementById('mNama').innerText = data.name;
            document.getElementById('mEmail').innerText = data.email;
            document.getElementById('mId').innerText = 'IDM-' + data.id;
            document.getElementById('mTempat').innerText = data.nama_tempat;
            document.getElementById('mAlamat').innerText = data.alamat_tempat;
            
            openModal('magangModal');
        }

        function prosesApproveMagang(status) {
            const catatan = document.getElementById('pimpinanCatatan').value;
            if (status === 'ditolak_pimpinan' && !catatan) {
                alert('Catatan wajib diisi jika menolak.');
                return;
            }

            const formData = new FormData();
            formData.append('action', 'approve');
            formData.append('magang_id', currentMagang.id);
            formData.append('status', status);
            formData.append('catatan', catatan);

            fetch('../../backend/approvalMagang.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if (data.status === 'success') location.reload();
            });
        }
        
        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }
    </script>
</body>
</html>
