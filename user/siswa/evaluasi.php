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
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-brand">HCTS</div>
        <ul class="nav-menu">
            <li><a href="dashboard_siswa.php">Home</a></li>
            <li><a href="#" class="active">Evaluasi</a></li>
            <li><a href="pembayaranSiswa.php">Keuangan</a></li>
        </ul>
        <div class="nav-action">
            <a href="../../app/logout.php" class="btn-logout">Logout</a>
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
                            <th>Catatan Pengajar</th>
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
                            <td style="font-style: italic; color: #64748B; font-size: 13px;">
                                <?= $eval['catatan_pengajar'] ?? '-' ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Action Area -->
        <div class="card action-card <?= ($must_remedial) ? 'remedial' : 'lulus' ?>">
            <?php if ($must_remedial): ?>
                <div class="action-icon" style="color: #B91C1C;"><i class="fas fa-times-circle"></i></div>
                <h2 class="action-title" style="color: #B91C1C;">Maaf! Anda Belum Lulus Evaluasi</h2>
                <p class="action-desc">
                    <?php if ($remedial_count >= 2): ?>
                        Anda memiliki <strong><?= $remedial_count ?> mata pelajaran</strong> dengan status Remedial. Sesuai aturan, Anda wajib mengikuti remedial untuk semua mapel tersebut sebelum bisa mengajukan magang.
                    <?php else: ?>
                        Rata-rata nilai Anda (<?= number_format($avg, 1) ?>) masih di bawah standar minimal 80. Silakan ajukan remedial untuk memperbaiki nilai Anda.
                    <?php endif; ?>
                </p>
                <a href="#" class="btn-action btn-remedial">
                   <i class="fas fa-calendar-check"></i> Ajukan Jadwal Remedial
                </a>
            <?php elseif ($has_option): ?>
                <div class="action-icon" style="color: #F59E0B;"><i class="fas fa-exclamation-circle"></i></div>
                <h2 class="action-title">Lulus dengan Catatan</h2>
                <p class="action-desc">Selamat! Rata-rata Anda sudah memenuhi syarat (>= 80). Namun, terdapat <strong>1 mata pelajaran</strong> yang masih remedial. Anda diperbolehkan langsung lanjut magang, atau mengambil remedial terlebih dahulu untuk menyempurnakan nilai.</p>
                <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                    <a href="#" class="btn-action btn-magang">
                        Lanjut ke Pengajuan Magang <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="#" class="btn-action btn-remedial" style="background: #E2E8F0; color: #475569;">
                        Ambil Remedial Terlebih Dahulu
                    </a>
                </div>
            <?php else: ?>
                <div class="action-icon" style="color: #22C55E;"><i class="fas fa-certificate"></i></div>
                <h2 class="action-title">Selamat! Anda Lulus Sempurna</h2>
                <p class="action-desc">Seluruh mata pelajaran Anda telah memenuhi standar kompetensi dan rata-rata Anda sangat baik. Anda kini berhak melanjutkan ke tahap pengajuan magang.</p>
                <a href="#" class="btn-action btn-magang">
                   Lanjut ke Pengajuan Magang <i class="fas fa-arrow-right"></i>
                </a>
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

    <!-- Footer Space -->
    <div style="height: 100px;"></div>
</body>
</html>
