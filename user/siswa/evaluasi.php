<?php
require_once __DIR__ . '/../../config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../../public/login/logSiswa.php?error=" . urlencode("Silakan login terlebih dahulu."));
    exit;
}

$id_user = $_SESSION['user_id'];

// Ambil data siswa
$query_siswa = mysqli_query($conn, "SELECT id_siswa, nama_lengkap FROM siswa WHERE id_user = $id_user");
$siswa = mysqli_fetch_assoc($query_siswa);

if (!$siswa) {
    echo "Profil siswa tidak ditemukan.";
    exit;
}

$id_siswa = $siswa['id_siswa'];

// Ambil data evaluasi
$query_eval = mysqli_query($conn, "SELECT * FROM evaluasi WHERE id_siswa = $id_siswa LIMIT 1");
$eval = mysqli_fetch_assoc($query_eval);

// Definisikan Mapel (Mapping Kode ke Nama)
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

// Logika Grade & Penentuan Kelulusan
function getGrade($score) {
    if ($score >= 90) return 'A';
    if ($score >= 85) return 'A-';
    if ($score >= 80) return 'B+';
    if ($score >= 75) return 'B';
    if ($score >= 70) return 'C';
    return 'D';
}

$avg = $eval ? $eval['rata_rata'] : 0;

// Hitung jumlah mata pelajaran yang remedial (nilai < 80)
$remedial_count = 0;
if ($eval) {
    foreach(['DUI1','DUI2','DUI3','DUI4','DUI5','DUI6','DUI7','DUI8'] as $key) {
        if (($eval[$key] ?? 0) < 80) {
            $remedial_count++;
        }
    }
}

$grade_final = getGrade($avg);

// Logika Baru Status Kelulusan
$can_intern = ($avg >= 80 && $remedial_count <= 1);
$has_option = ($avg >= 80 && $remedial_count == 1);
$must_remedial = ($avg < 80 || $remedial_count >= 2);

if ($must_remedial) {
    $status_label = "Belum Lulus (Wajib Remedial)";
    $status_class = "badge-remedial";
} elseif ($has_option) {
    $status_label = "Lulus (Opsi Remedial Tersedia)";
    $status_class = "badge-remedial"; // Kuning karena ada catatan
} else {
    $status_label = "Lulus Evaluasi";
    $status_class = "badge-lulus";
}

// Cek apakah sedang dalam proses remedial
$check_remedial = mysqli_query($conn, "SELECT COUNT(*) as aktif FROM pengajuan_remedial WHERE id_siswa = $id_siswa AND status_remedial != 'selesai'");
$data_remedial = mysqli_fetch_assoc($check_remedial);
$is_waiting_remedial = ($data_remedial['aktif'] > 0);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluasi Akademik - HCTS</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../style/dashboard_siswa.css">
    <link rel="stylesheet" href="../../style/evaluasi_siswa.css">
    <link rel="stylesheet" href="../../style/popup_logout.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-brand">HCTS</div>
        <ul class="nav-menu">
            <li><a href="dashboard_siswa.php">Home</a></li>
            <li><a href="evaluasi.php" class="active">Evaluasi</a></li>
            <li><a href="pembayaranSiswa.php">Keuangan</a></li>
            <li><a href="magang_siswa.php">Magang</a></li>
        </ul>
        <div class="nav-action">
            <a href="#" class="nav-bell"><i class="far fa-bell"></i></a>
            <a href="#" onclick="showLogoutPopup(event)" class="btn-logout">Logout</a>
        </div>
    </nav>

    <div class="eval-container">
        <h1 style="font-family: 'Playfair Display', serif; color: #003B73; margin-bottom: 30px;">Evaluasi Akademik</h1>

        <!-- Header Card -->
        <div class="eval-card">
            <div class="eval-header">
                <div class="eval-header-info">
                    <h2>Laporan Hasil Evaluasi</h2>
                    <div class="eval-meta">
                        Nama: <strong><?= htmlspecialchars($siswa['nama_lengkap']) ?></strong> | 
                        Periode: <strong><?= $eval['periode_semester'] ?? 'Periode 1 / 2025' ?></strong>
                    </div>
                    <div class="status-grid">
                        <span>Status Akhir:</span>
                        <span class="badge <?= $status_class ?>"><?= $status_label ?></span>
                    </div>
                </div>
                <div class="score-display">
                    <span class="score-label">Rata-Rata Nilai</span>
                    <div class="score-large"><?= number_format($avg, 1) ?></div>
                    <div class="score-grade">Grade <?= $grade_final ?></div>
                </div>
            </div>
        </div>

        <?php if ($eval): ?>
        <!-- Nilai Table Card -->
        <div class="eval-card">
            <div class="eval-table-container">
                <h3 style="padding: 25px 0 15px; color: #003B73;">Rincian Nilai Mata Pelajaran</h3>
                <table class="eval-table">
                    <thead>
                        <tr>
                            <th>Mata Pelajaran</th>
                            <th>Nilai</th>
                            <th>Grade</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($mapel_names as $code => $name): 
                            $val = $eval[$code] ?? 0;
                            $g = getGrade($val);
                            $s = ($val >= 80) ? "Lulus" : "Remedial";
                            $sc = ($val >= 80) ? "badge-lulus" : "badge-remedial";
                        ?>
                        <tr class="<?= ($val < 80) ? 'row-remedial' : '' ?>">
                            <td class="mapel-name"><?= $name ?></td>
                            <td class="mapel-score"><?= $val ?></td>
                            <td><?= $g ?></td>
                            <td><span class="badge <?= $sc ?>"><?= $s ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Box Catatan Baru (Sesuai Gambar) -->
            <div style="padding: 0 30px 30px;">
                <h4 style="color: var(--primary); margin-bottom: 15px; font-weight: 700;">Catatan Umum Pengajar:</h4>
                <div style="width: 100%; min-height: 100px; padding: 20px; border: 1px solid #E2E8F0; border-radius: 12px; background-color: #FFFFFF; color: var(--text-muted); font-size: 14px; line-height: 1.6; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);">
                    <?= !empty($eval['catatan_pengajar']) ? nl2br(htmlspecialchars($eval['catatan_pengajar'])) : "Belum ada catatan evaluasi menyeluruh untuk periode ini." ?>
                </div>
            </div>
        </div>

        <!-- Action Area -->
        <div class="card action-card <?= ($must_remedial) ? 'remedial' : 'lulus' ?>">
            <?php if ($must_remedial): ?>
                <div class="action-icon" style="color: <?= $is_waiting_remedial ? '#F59E0B' : '#B91C1C' ?>;">
                    <i class="fas <?= $is_waiting_remedial ? 'fa-spinner fa-spin' : 'fa-times-circle' ?>"></i>
                </div>
                <h2 class="action-title" style="color: <?= $is_waiting_remedial ? '#F59E0B' : '#B91C1C' ?>;">
                    <?= $is_waiting_remedial ? 'Remedial Sedang Diproses' : 'Maaf! Anda Belum Lulus Evaluasi' ?>
                </h2>
                <p class="action-desc">
                    <?php if ($is_waiting_remedial): ?>
                        Permohonan remedial Anda sudah diterima dan sedang dalam proses penilaian oleh Pengajar. Mohon tunggu hingga semua mata pelajaran selesai diperbarui untuk melihat status kelulusan akhir Anda.
                    <?php elseif ($remedial_count >= 2): ?>
                        Anda memiliki <strong><?= $remedial_count ?> mata pelajaran</strong> dengan status Remedial. Sesuai aturan, Anda wajib mengikuti remedial untuk semua mapel tersebut sebelum bisa mengajukan magang.
                    <?php else: ?>
                        Rata-rata nilai Anda (<?= number_format($avg, 1) ?>) masih di bawah standar minimal 80. Silakan ajukan remedial untuk memperbaiki nilai Anda.
                    <?php endif; ?>
                </p>
                <?php if (!$is_waiting_remedial): ?>
                    <a href="javascript:void(0)" class="btn-action btn-remedial" onclick="openRemedialModal()">
                       <i class="fas fa-calendar-check"></i> Ajukan Jadwal Remedial
                    </a>
                <?php endif; ?>
            <?php elseif ($has_option): ?>
                <div class="action-icon" style="color: #F59E0B;"><i class="fas fa-exclamation-circle"></i></div>
                <h2 class="action-title">Lulus dengan Catatan</h2>
                <p class="action-desc">Selamat! Rata-rata Anda sudah memenuhi syarat (>= 80). Namun, terdapat <strong>1 mata pelajaran</strong> yang masih remedial. Anda diperbolehkan langsung lanjut magang, atau mengambil remedial terlebih dahulu untuk menyempurnakan nilai.</p>
                <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                    <a href="magang_siswa.php" class="btn-action btn-magang">
                        Lanjut ke Pengajuan Magang <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="javascript:void(0)" class="btn-action btn-remedial" style="background: #E2E8F0; color: #475569;" onclick="openRemedialModal()">
                        Ambil Remedial Terlebih Dahulu
                    </a>
                </div>
            <?php else: ?>
                <div class="action-icon" style="color: #22C55E;"><i class="fas fa-certificate"></i></div>
                <h2 class="action-title">Selamat! Anda Lulus Sempurna</h2>
                <p class="action-desc">Seluruh mata pelajaran Anda telah memenuhi standar kompetensi dan rata-rata Anda sangat baik. Anda kini berhak melanjutkan ke tahap pengajuan magang.</p>
                <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap; margin-top: 20px;">
                    <a href="magang_siswa.php" class="btn-action btn-magang">
                       Lanjut ke Pengajuan Magang <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="sertifikat.php" class="btn-action" style="background: #059669; color: white;">
                       <i class="fas fa-certificate"></i> Cetak Sertifikat
                    </a>
                </div>
            <?php endif; ?>
        </div>
        <?php else: ?>
            <div class="eval-card" style="padding: 50px; text-align: center;">
                <i class="fas fa-info-circle" style="font-size: 48px; color: #CBD5E1; margin-bottom: 20px;"></i>
                <h2 style="color: #64748B;">Data Evaluasi Belum Tersedia</h2>
                <p style="color: #94A3B8;">Pengajar belum melakukan input nilai untuk periode ini. Silakan hubungi bagian akademik jika menurut Anda ini adalah kesalahan.</p>
            </div>
        <?php endif; ?>
    </div>

    </div>

    <!-- Modal Pengajuan Remedial -->
    <div id="remedialModal" class="modal" style="display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); align-items: center; justify-content: center;">
        <div class="modal-content" style="background: white; padding: 35px; border-radius: 20px; width: 500px; max-width: 90%; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
            <div style="text-align: center; margin-bottom: 25px;">
                <div style="background: #FEF3C7; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                    <i class="fas fa-edit" style="font-size: 24px; color: #D97706;"></i>
                </div>
                <h3 style="color: #003B73; font-size: 22px;">Pengajuan Remedial</h3>
                <p style="color: #64748B; font-size: 14px;">Silakan pilih mata pelajaran yang ingin diperbaiki.</p>
            </div>

            <form id="remedialForm">
                <input type="hidden" name="action" value="ajukan_remedial">
                <input type="hidden" name="id_siswa" value="<?= $id_siswa ?>">
                <input type="hidden" name="id_evaluasi" value="<?= $eval['id_evaluasi'] ?>">

                <div style="margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <label style="font-weight: 600; color: #1E293B;">Pilih Mata Pelajaran:</label>
                        <label style="font-size: 13px; color: var(--primary); cursor: pointer; user-select: none;">
                            <input type="checkbox" id="selectAllMapel" onclick="toggleSelectAll(this)"> Pilih Semua
                        </label>
                    </div>
                    
                    <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 5px; max-height: 200px; overflow-y: auto;" class="scroll-container">
                        <?php 
                        $any_rem = false;
                        foreach($mapel_names as $code => $name): 
                            $score = $eval[$code] ?? 0;
                            if ($score < 80): 
                                $any_rem = true;
                        ?>
                            <label style="display: flex; align-items: center; gap: 12px; padding: 12px; cursor: pointer; border-bottom: 1px solid #F1F5F9; transition: background 0.2s;" onmouseover="this.style.background='#F1F5F9'" onmouseout="this.style.background='transparent'">
                                <input type="checkbox" name="mapel_kode[]" class="mapel-checkbox" value="<?= $code ?>|<?= $score ?>">
                                <div style="flex: 1;">
                                    <div style="font-weight: 600; color: #0F172A; font-size: 14px;"><?= $name ?></div>
                                    <div style="font-size: 12px; color: #B91C1C;">Nilai saat ini: <?= $score ?></div>
                                </div>
                            </label>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </div>
                    <style>
                        .scroll-container::-webkit-scrollbar { width: 6px; }
                        .scroll-container::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
                        .scroll-container::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
                        .scroll-container::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
                    </style>
                </div>

                <div style="margin-bottom: 25px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 10px; color: #1E293B;">Alasan Pengajuan:</label>
                    <textarea name="alasan" style="width: 100%; height: 80px; padding: 12px; border: 1px solid #E2E8F0; border-radius: 10px; font-family: inherit; resize: none;" placeholder="Contoh: Kurang fokus saat ujian, ingin memperbaiki pemahaman materi..." required></textarea>
                </div>

                <div style="display: flex; gap: 15px;">
                    <button type="submit" class="btn-action btn-remedial" style="flex: 1; justify-content: center;">Kirim Pengajuan</button>
                    <button type="button" onclick="closeRemedialModal()" style="flex: 1; background: #F1F5F9; color: #64748B; border: none; border-radius: 30px; font-weight: 600; cursor: pointer;">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer Space -->
    <div style="height: 100px;"></div>

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
        function showLogoutPopup(e) {
            if(e) e.preventDefault();
            document.getElementById('logoutPopup').style.display = 'flex';
        }
        function closeLogoutPopup() {
            document.getElementById('logoutPopup').style.display = 'none';
        }

        function openRemedialModal() {
            document.getElementById('remedialModal').style.display = 'flex';
        }

        function closeRemedialModal() {
            document.getElementById('remedialModal').style.display = 'none';
        }

        function toggleSelectAll(source) {
            const checkboxes = document.querySelectorAll('.mapel-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = source.checked;
            });
        }

        document.getElementById('remedialForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('../../backend/remedial_end.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    alert('Berhasil! ' + data.message);
                    closeRemedialModal();
                    location.reload();
                } else {
                    alert('Gagal: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan koneksi.');
            });
        });
    </script>
</body>
</html>
