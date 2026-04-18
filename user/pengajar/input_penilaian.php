<?php
session_start();
require_once '../../config/config.php';

$success_msg = "";
$error_msg = "";

// Cek submit form evaluasi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_evaluasi'])) {
    $id_siswa_raw = $_POST['id_siswa'] ?? ''; 
    $parts = explode('|', $id_siswa_raw);
    $id_siswa = $parts[0] ?? '';
    
    // Dummy pengajar ID, menyesuaikan dengan yang ada di db dump
    $id_pengajar = 102; 
    $periode = "Semester Ganjil 2025";
    
    $mapel_arr = $_POST['mapel'] ?? [];
    $nilai_arr = $_POST['nilai'] ?? [];
    $grade_arr = $_POST['grade'] ?? [];
    $catatan_arr = $_POST['catatan'] ?? [];

    if (!empty($id_siswa) && count($mapel_arr) > 0) {
        mysqli_begin_transaction($conn);
        try {
            $inserted = 0;
            for ($i = 0; $i < count($mapel_arr); $i++) {
                $m = mysqli_real_escape_string($conn, $mapel_arr[$i]);
                $n = $nilai_arr[$i];
                $g = mysqli_real_escape_string($conn, $grade_arr[$i]);
                $c = mysqli_real_escape_string($conn, $catatan_arr[$i]);
                
                // Cek nilai kosong, lewati jika pengajar tidak menginput mapel ini
                if ($n === '') continue; 
                
                $n_int = (int)$n;
                
                $sql = "INSERT INTO evaluasi (id_siswa, id_pengajar, mata_pelajaran, nilai_angka, grade, catatan_pengajar, periode_semester) 
                        VALUES ('$id_siswa', $id_pengajar, '$m', $n_int, '$g', '$c', '$periode')";
                mysqli_query($conn, $sql);
                $inserted++;
            }
            
            if ($inserted > 0) {
                mysqli_commit($conn);
                $success_msg = "Laporan evaluasi ($inserted mapel) berhasil disimpan ke database!";
            } else {
                mysqli_rollback($conn);
                $error_msg = "Tidak ada nilai yang diinputkan.";
            }
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $error_msg = "Gagal menyimpan: " . $e->getMessage();
        }
    } else {
        $error_msg = "Pilih siswa terlebih dahulu.";
    }
}

// Ambil data siswa untuk dropdown
$siswaDropdown = [];
$query_siswa = mysqli_query($conn, "SELECT * FROM siswa");
if ($query_siswa) {
    while($row = mysqli_fetch_assoc($query_siswa)) {
        $siswaDropdown[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Penilaian Evaluasi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;800&family=Poppins:wght@400;500;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../style/dashboard_pengajar.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../style/input_penilaian.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../style/popup_logout.css">
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
            <a href="#" class="sidebar-link">
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
                    <a href="input_penilaian.php" class="dropdown-link active">Input Penilaian</a>
                    <a href="riwayat_evaluasi.php" class="dropdown-link">Riwayat Evaluasi</a>
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
                    <h1 class="banner-title" id="pageTitle">Input Penilaian</h1>
                    <div class="semester-pill">Semester Ganjil 2025</div>
                </div>
            </section>

            <!-- ===== INPUT PENILAIAN SECTION ===== -->
            <section class="full-width" style="margin-top: -30px; position: relative; z-index: 10;">
                <div class="eval-card">
                    <h2 class="eval-title" id="sectionTitle">Form Evaluasi Akademik</h2>
                    
                    <div class="select-wrapper">
                        <span class="section-label">Pilih Siswa untuk Dievaluasi</span>
                        <select class="select-student" id="studentSelect">
                            <option value="">-- Cari Nama Siswa --</option>
                            <?php foreach($siswaDropdown as $s): ?>
                                <option value="<?= htmlspecialchars($s['id_siswa']) ?>|<?= htmlspecialchars($s['program_pembelajaran']) ?>|<?= htmlspecialchars($s['nim_siswa']) ?>">
                                    <?= htmlspecialchars($s['nama_lengkap']) ?> (<?= htmlspecialchars($s['program_pembelajaran']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <form id="evalForm" method="POST" action="" onsubmit="return validateForm()">
                        <input type="hidden" name="id_siswa" id="formIdSiswa" value="">

                        <div class="student-card" id="studentCard" style="display:none;">
                            <div class="student-avatar">
                                <i class="fa-solid fa-address-card"></i>
                            </div>
                            <div class="student-info">
                                <h3 id="studentNameDisplay"></h3>
                                <p>NIM Siswa: <span id="studentIdDisplay"></span> | Program: <span id="studentProgramDisplay"></span></p>
                            </div>
                            <div class="period-badge">Periode 1 - 2025</div>
                        </div>

                        <div class="eval-table-container" id="evalTableContainer" style="display:none;">
                            <table class="eval-table">
                                <thead>
                                    <tr>
                                        <th>Mata Pelajaran (Subject)</th>
                                        <th style="text-align: center;">Nilai (0-100)</th>
                                        <th style="text-align: center;">Grade</th>
                                        <th>Evaluasi Pengajar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $mapels = [
                                        "English Basic", "Food & Beverage Service", "Housekeeping", 
                                        "English Profession", "General Hotel Operation", "General Hotel Knowledge", 
                                        "Psychology of Service", "Hygiene and Sanitation"
                                    ];
                                    foreach($mapels as $mapel):
                                    ?>
                                    <tr>
                                        <td>
                                            <?= $mapel ?>
                                            <input type="hidden" name="mapel[]" value="<?= $mapel ?>">
                                        </td>
                                        <td style="text-align: center;">
                                            <input type="number" name="nilai[]" class="input-score" min="0" max="100" placeholder="0">
                                        </td>
                                        <td style="text-align: center;">
                                            <input type="hidden" name="grade[]" class="input-grade-hidden" value="-">
                                            <span class="grade-display">-</span>
                                        </td>
                                        <td>
                                            <input type="text" name="catatan[]" class="input-note" placeholder="Berikan catatan singkat...">
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="form-actions" id="formActions" style="display:none; justify-content: space-between; align-items: center;">
                            <div class="form-msg" id="formMsg">Pastikan mengisi nilai pada form yang diinginkan sebelum menyimpan.</div>
                            <div class="btn-group">
                                <button type="button" class="btn-batal" id="btnBatal">Batal</button>
                                <button type="submit" name="submit_evaluasi" class="btn-simpan" id="btnSimpan">
                                    <i class="fa-solid fa-floppy-disk"></i> Simpan & Konfirmasi Laporan
                                </button>
                            </div>
                        </div>
                    </form>

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

    <!-- Logout Popup -->
    <div id="logoutPopup" class="popup-overlay" style="display: none;">
        <div class="popup-wrapper">
... (Same logout popup structure remains)...
                <div class="popup-footer">
                    <a href="../../app/logout.php" class="btn-yakin">Yakin</a>
                    <button class="btn-tidak" onclick="closeLogoutPopup()">Tidak</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== MODAL SUCCESS/ERROR ===== -->
    <div id="statusModal" class="modal-overlay" style="display: none;">
        <div class="modal-content" style="max-width: 450px;">
            <div class="modal-header">
                <h2 class="modal-title" id="statusModalTitle">Status</h2>
                <button class="modal-close" onclick="closeStatusModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body" style="text-align: center;">
                <div class="info-box" style="margin-bottom: 0;">
                    <h3 class="info-box-title" id="statusModalMessage" style="font-size: 18px; margin-bottom: 20px;"></h3>
                    <button class="btn-simpan" style="width: 100%; border-radius: 20px; font-weight: bold; justify-content: center;" onclick="closeStatusModal()">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const statusModal = document.getElementById('statusModal');
        const statusModalTitle = document.getElementById('statusModalTitle');
        const statusModalMessage = document.getElementById('statusModalMessage');

        function showStatusModal(title, msg) {
            statusModalTitle.textContent = title;
            statusModalMessage.textContent = msg;
            statusModal.style.display = 'flex';
        }

        function closeStatusModal() {
            statusModal.style.display = 'none';
        }

        statusModal.addEventListener('click', function(e) {
            if (e.target === statusModal) {
                closeStatusModal();
            }
        });
    </script>
    
    <?php if(!empty($success_msg)): ?>
        <script>showStatusModal("Berhasil", "<?= addslashes($success_msg) ?>");</script>
    <?php endif; ?>
    <?php if(!empty($error_msg)): ?>
        <script>showStatusModal("Gagal", "<?= addslashes($error_msg) ?>");</script>
    <?php endif; ?>

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

        // Logic for Form Evaluasi
        const studentSelect = document.getElementById('studentSelect');
        const studentCard = document.getElementById('studentCard');
        const evalTableContainer = document.getElementById('evalTableContainer');
        const formActions = document.getElementById('formActions');
        const studentNameDisplay = document.getElementById('studentNameDisplay');
        const studentIdDisplay = document.getElementById('studentIdDisplay');
        const studentProgramDisplay = document.getElementById('studentProgramDisplay');
        const formIdSiswa = document.getElementById('formIdSiswa');
        
        const pageTitle = document.getElementById('pageTitle');
        const sectionTitle = document.getElementById('sectionTitle');

        const scoreInputs = document.querySelectorAll('.input-score');
        const btnBatal = document.getElementById('btnBatal');
        const formMsg = document.getElementById('formMsg');

        function getGrade(score) {
            if(score === '' || isNaN(score)) return '-';
            score = parseFloat(score);
            
            if(score >= 85) return 'A';           // Istimewa/4.0
            if(score >= 80) return 'A-';          // Sangat Baik/3.5-3.7
            if(score >= 75) return 'B';           // Baik/3.0
            if(score >= 70) return 'B-';          // Cukup Baik/2.5-2.7
            if(score >= 60) return 'C';           // Cukup/2.0
            if(score >= 50) return 'D';           // Kurang/1.0
            return 'E';                           // Sangat Kurang/Gagal/0.0
        }

        studentSelect.addEventListener('change', function() {
            if(this.value) {
                // Parse select value
                const rawText = this.options[this.selectedIndex].text;
                const namePart = rawText.split(' (')[0];
                const [id, program, nim] = this.value.split('|');
                
                studentNameDisplay.textContent = namePart;
                studentIdDisplay.textContent = nim;
                studentProgramDisplay.textContent = program;
                formIdSiswa.value = id;

                studentCard.style.display = 'flex';
                evalTableContainer.style.display = 'block';
                formActions.style.display = 'flex';
                
                pageTitle.textContent = 'Input Penilaian Evaluasi';
                sectionTitle.textContent = 'Laporan Evaluasi Akademik';
            } else {
                studentCard.style.display = 'none';
                evalTableContainer.style.display = 'none';
                formActions.style.display = 'none';
                formIdSiswa.value = '';

                pageTitle.textContent = 'Input Penilaian';
                sectionTitle.textContent = 'Form Evaluasi Akademik';
            }
        });

        btnBatal.addEventListener('click', () => {
            studentSelect.value = '';
            studentSelect.dispatchEvent(new Event('change'));
            
            // Reset inputs
            scoreInputs.forEach(input => {
                input.value = '';
                input.classList.remove('error');
                const tr = input.closest('tr');
                tr.querySelector('.grade-display').textContent = '-';
                tr.querySelector('.input-grade-hidden').value = '-';
            });
            document.querySelectorAll('.input-note').forEach(input => input.value = '');
            formMsg.textContent = 'Pastikan mengisi nilai pada form yang diinginkan sebelum menyimpan.';
            formMsg.classList.remove('error-msg');
        });

        scoreInputs.forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('error');
                if(parseInt(this.value) > 100) this.value = '100';
                
                const tr = this.closest('tr');
                const newGrade = getGrade(this.value);
                tr.querySelector('.grade-display').textContent = newGrade;
                tr.querySelector('.input-grade-hidden').value = newGrade;
            });
        });

        function validateForm() {
            let isValid = true;
            let hasScore = false;
            scoreInputs.forEach(input => {
                if(input.value.trim() !== '') {
                    hasScore = true;
                    if(isNaN(input.value)) {
                        isValid = false;
                        input.classList.add('error');
                    }
                }
            });

            if(!hasScore) {
                formMsg.textContent = 'Harap isi setidaknya satu nilai mata pelajaran sebelum menyimpan.';
                formMsg.classList.add('error-msg');
                return false;
            }

            if(!isValid) {
                formMsg.textContent = 'Nilai harus dalam format angka.';
                formMsg.classList.add('error-msg');
                return false;
            }
            return true;
        }

    </script>
</body>
</html>
