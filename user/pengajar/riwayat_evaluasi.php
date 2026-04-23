session_start();
require_once '../../config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 3) {
    header("Location: ../../public/login/logPengajar.php");
    exit;
}

// Fetch batch evaluations in pivoted format
$query_batch = mysqli_query($conn, "
    SELECT e.*, s.nama_lengkap, p.nama_program as program_pembelajaran, s.nim_siswa,
           DATE_FORMAT(e.tanggal_input, '%d %b %Y') as tgl_input
    FROM evaluasi e 
    JOIN siswa s ON e.id_siswa = s.id_siswa 
    JOIN program p ON s.id_program = p.id_program
    ORDER BY e.tanggal_input DESC
");

// Pre-process evaluations for the detail modal (pivoted format)
$evaluations_raw = [];
if ($query_batch && mysqli_num_rows($query_batch) > 0) {
    mysqli_data_seek($query_batch, 0); // Reset pointer
    while($row = mysqli_fetch_assoc($query_batch)) {
        $key = $row['id_siswa'] . '_' . $row['periode_semester'];
        $evaluations_raw[$key][] = $row;
    }
    mysqli_data_seek($query_batch, 0); // Reset pointer for the HTML loop
}
?>

<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Evaluasi - HCTS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../style/dashboard_pengajar.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../style/riwayat_evaluasi.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../style/popup_logout.css">
    <style>
        .badge-gagal { background: #fecdd3; color: #9f1239; padding: 8px 24px; border-radius: 20px; font-weight: 700; font-size: 15px; }
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
                    <a href="riwayat_evaluasi.php" class="dropdown-link active">Riwayat Evaluasi</a>
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

            <!-- Hero / Dashboard Banner -->
            <section class="dashboard-banner">
                <div class="banner-content">
                    <h1 class="banner-title">Riwayat Evaluasi</h1>
                    <div class="semester-pill">Daftar Laporan Penilaian</div>
                </div>
            </section>

            <!-- ===== RIWAYAT SECTION ===== -->
            <section class="full-width" style="margin-top: -30px; position: relative; z-index: 10;">
                <div class="riwayat-card">
                    <h2 class="riwayat-title">Riwayat Penilaian (Batch)</h2>
                    
                    <div class="riwayat-table-wrapper">
                        <table class="riwayat-table">
                            <thead>
                                <tr>
                                    <th>Tanggal Input</th>
                                    <th>Nama Lengkap</th>
                                    <th>Periode/Tipe Evaluasi</th>
                                    <th>Rata-Rata</th>
                                    <th style="text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($query_batch && mysqli_num_rows($query_batch) > 0): ?>
                                    <?php while($r = mysqli_fetch_assoc($query_batch)): 
                                        $rata = round($r['rata_rata']);
                                        
                                        $rt_grade = 'E';
                                        if($rata >= 85) $rt_grade = 'A';
                                        elseif($rata >= 80) $rt_grade = 'A-';
                                        elseif($rata >= 75) $rt_grade = 'B';
                                        elseif($rata >= 70) $rt_grade = 'B-';
                                        elseif($rata >= 60) $rt_grade = 'C';
                                        elseif($rata >= 50) $rt_grade = 'D';

                                        $lulus = ($rata >= 60);
                                        $statusClass = ($r['status_kelulusan'] === 'Lulus') ? 'badge-lulus' : 'badge-gagal';
                                        
                                        $key = $r['id_siswa'] . '_' . $r['periode_semester'];
                                        $siswa_grades = isset($evaluations_raw[$key]) ? $evaluations_raw[$key] : [];
                                        $json_data = htmlspecialchars(json_encode($siswa_grades), ENT_QUOTES, 'UTF-8');
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($r['tgl_input']) ?></td>
                                        <td><?= htmlspecialchars($r['nama_lengkap']) ?></td>
                                        <td><?= htmlspecialchars($r['periode_semester']) ?></td>
                                        <td><?= $rata ?> (<?= $rt_grade ?>)</td>
                                        <td style="text-align: center;">
                                            <button class="btn-detail" 
                                                 onclick="openDetailModal('<?= htmlspecialchars($r['nim_siswa']) ?>', 
                                                                         '<?= htmlspecialchars($r['nama_lengkap'], ENT_QUOTES, 'UTF-8') ?>', 
                                                                         '<?= htmlspecialchars($r['program_pembelajaran'], ENT_QUOTES, 'UTF-8') ?>', 
                                                                         '<?= htmlspecialchars($r['periode_semester'], ENT_QUOTES, 'UTF-8') ?>', 
                                                                         '<?= $r['status_kelulusan'] ?>', 
                                                                         this, 
                                                                         '<?= $statusClass ?>')" 
                                                 data-details="<?= $json_data ?>">
                                                 Detail
                                             </button>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="5" style="text-align:center;">Belum ada riwayat siswa yang dinilai di database.</td></tr>
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
                </div>
            </div>
            <div class="footer-bottom">
                <p class="footer-copy">&copy; 2025 HCTS International. All rights reserved.</p>
            </div>
        </footer>

    </div>

    <!-- ===== MODAL DETAIL EVALUASI ===== -->
    <div id="detailModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Detail Evaluasi</h2>
                <button class="modal-close" onclick="closeDetailModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                
                <!-- Identitas Siswa -->
                <div class="info-box">
                    <h3 class="info-box-title">Identitas Siswa</h3>
                    <div class="info-grid">
                        <div class="info-row">
                            <span class="info-label">NIM Siswa</span>
                            <span class="info-value" id="modalIdSiswa"></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Program:</span>
                            <span class="info-value" id="modalProgram"></span>
                        </div>
                        <div class="divider"></div>
                        <div class="info-row">
                            <span class="info-label">Nama Lengkap:</span>
                            <span class="info-value" id="modalNamaSiswa"></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Periode:</span>
                            <span class="info-value" id="modalPeriode"></span>
                        </div>
                    </div>
                </div>

                <!-- Hasil Evaluasi -->
                <div class="info-box">
                    <div class="info-box-header">
                        <h3 class="info-box-title">Hasil Evaluasi</h3>
                        <div id="modalStatusBadge" class="badge-lulus">Lulus</div>
                    </div>
                    
                    <table class="detail-table">
                        <thead>
                            <tr>
                                <th>Mata Pelajaran (Subject)</th>
                                <th class="text-center">Nilai (0-100)</th>
                                <th class="text-center">Grade</th>
                            </tr>
                        </thead>
                        <tbody id="modalTableBody">
                            <!-- Populated via JS -->
                        </tbody>
                        <tfoot id="modalTableFooter">
                            <!-- Populated via JS -->
                        </tfoot>
                    </table>
                </div>

                <div class="modal-footer-note">*scroll to see full list</div>

            </div>
        </div>
    </div>

    <!-- Logout Popup -->
    <div id="logoutPopup" class="popup-overlay" style="display: none;">
        <div class="popup-wrapper">
... (Same Structure)...
                <div class="popup-footer">
                    <a href="../../app/logout.php" class="btn-yakin">Yakin</a>
                    <button class="btn-tidak" onclick="closeLogoutPopup()">Tidak</button>
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

        // Modal Logic
        const detailModal = document.getElementById('detailModal');
        const modalTableBody = document.getElementById('modalTableBody');
        const modalStatusBadge = document.getElementById('modalStatusBadge');

        function openDetailModal(id_siswa, nama, program, periode, statusText, btnNode, statusClassBadge) {
            // Update identitas
            document.getElementById('modalIdSiswa').textContent = id_siswa;
            document.getElementById('modalNamaSiswa').textContent = nama;
            document.getElementById('modalProgram').textContent = program;
            document.getElementById('modalPeriode').textContent = periode;

            // Setup Badge
            modalStatusBadge.textContent = statusText;
            modalStatusBadge.className = statusClassBadge;

            // Extract JSON Data
            const jsonDataStr = btnNode.getAttribute('data-details');
            let data = [];
            try {
                data = JSON.parse(jsonDataStr);
            } catch(e) {
                console.error("Invalid JSON Details");
            }
            
            // Build Table String
            let htmlStr = '';
            if(data.length === 0) {
                htmlStr = '<tr><td colspan="3" class="text-center">Data rinci tidak ditemukan</td></tr>';
            } else {
                const item = data[0];
                const mapelMap = {
                    'DUI1': 'English for Hospitality',
                    'DUI2': 'Hotel & Cruise Ship Overview',
                    'DUI3': 'Food & Beverage Service Foundation',
                    'DUI4': 'Kitchen & Food Production Basics',
                    'DUI5': 'Housekeeping & Laundry Fundamentals',
                    'DUI6': 'Front Office & Guest Interaction',
                    'DUI7': 'Basic Safety Training (BST) & STCW',
                    'DUI8': 'Grooming & Professional Conduct'
                };

                function getG(s) {
                    if (s >= 90) return 'A';
                    if (s >= 85) return 'A-';
                    if (s >= 80) return 'B+';
                    if (s >= 75) return 'B';
                    if (s >= 70) return 'C';
                    return 'D';
                }

                Object.keys(mapelMap).forEach(code => {
                    const score = item[code] || 0;
                    htmlStr += `
                        <tr ${score < 80 ? 'style="background:#FFFBEB"' : ''}>
                            <td class="fw-bold">${mapelMap[code]}</td>
                            <td class="text-center"><span class="mapel-score">${score}</span></td>
                            <td class="text-center fw-bold">${getG(score)}</td>
                        </tr>
                    `;
                });
            }
            modalTableBody.innerHTML = htmlStr;

            // Build Footer Note (Outside Table - Matching Request)
            let footerNoteHtml = `
                <div style="margin-top: 25px; padding-top: 20px; border-top: 1px solid #E2E8F0;">
                    <h4 style="color: #003B73; margin-bottom: 12px; font-weight: 700; font-size: 15px;">Catatan Umum Pengajar:</h4>
                    <div style="width: 100%; min-height: 80px; padding: 15px; border: 1px solid #E2E8F0; border-radius: 10px; background-color: #F8FAFC; color: #64748B; font-size: 13px; line-height: 1.5;">
                        ${(data.length > 0 && data[0].catatan_pengajar) ? data[0].catatan_pengajar : 'Tidak ada catatan evaluasi.'}
                    </div>
                </div>
            `;
            
            // Check if footer container exists, if not create it or append to body
            let footerContainer = document.getElementById('modalNoteContainer');
            if (!footerContainer) {
                footerContainer = document.createElement('div');
                footerContainer.id = 'modalNoteContainer';
                modalTableBody.parentElement.parentElement.appendChild(footerContainer);
            }
            footerContainer.innerHTML = footerNoteHtml;

            detailModal.style.display = 'flex';
        }

        function closeDetailModal() {
            detailModal.style.display = 'none';
        }

        // Close when clicking outside content
        detailModal.addEventListener('click', function(e) {
            if (e.target === detailModal) {
                closeDetailModal();
            }
        });
    </script>
</body>
</html>
