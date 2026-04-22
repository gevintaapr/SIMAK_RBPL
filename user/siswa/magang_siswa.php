<?php
require_once __DIR__ . '/../../config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../../public/login/logSiswa.php");
    exit;
}

$id_user = $_SESSION['user_id'];

// Ambil data siswa & status pembayaran
$query_siswa = mysqli_query($conn, "SELECT id_siswa, nama_lengkap, status_pembayaran FROM siswa WHERE id_user = $id_user");
$siswa = mysqli_fetch_assoc($query_siswa);
$id_siswa = $siswa['id_siswa'];

// Cek Kelulusan Evaluasi
$query_eval = mysqli_query($conn, "SELECT status_kelulusan, rata_rata FROM evaluasi WHERE id_siswa = $id_siswa LIMIT 1");
$eval = mysqli_fetch_assoc($query_eval);

// Logika Syarat Magang (Sesuai kolom status_pembayaran)
$is_lunas = ($siswa['status_pembayaran'] === 'lunas_dp');
$is_lulus = ($eval && ($eval['status_kelulusan'] == 'Lulus' || $eval['status_kelulusan'] == 'Lulus (Opsi Remedial Tersedia)'));
$can_apply = ($is_lunas && $is_lulus);

// Ambil Riwayat Pengajuan
$query_riwayat = mysqli_query($conn, "SELECT * FROM magang WHERE id_siswa = $id_siswa ORDER BY tanggal_pengajuan DESC");

// Cek apakah ada pengajuan yang sedang diproses (pending)
$check_pending = mysqli_query($conn, "SELECT COUNT(*) as jml FROM magang WHERE id_siswa = $id_siswa AND status_magang = 'pending'");
$is_pending_magang = (mysqli_fetch_assoc($check_pending)['jml'] > 0);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proses Magang - HCTS</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../style/dashboard_siswa.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../style/evaluasi_siswa.css?v=<?= time() ?>">
    <style>
        :root { --primary: #003B73; --gold: #D4AF37; --bg-light: #F8FAFC; }
        body { background-color: var(--bg-light); font-family: 'Poppins', sans-serif; }
        .page-title { font-family: 'Playfair Display', serif; color: var(--primary); margin: 40px auto 30px; width: 90%; max-width: 1100px; font-size: 32px; }
        .magang-card { background: white; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); margin-bottom: 30px; padding: 35px; border: 1px solid #edf2f7; }
        .section-header { border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 25px; color: var(--primary); font-family: 'Playfair Display', serif; font-size: 22px; }
        
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 25px; }
        .info-box { background: #F1F5F9; padding: 20px; border-radius: 12px; }
        .info-box h4 { margin-bottom: 15px; color: var(--primary); display: flex; align-items: center; gap: 10px; }
        .check-item { display: flex; align-items: center; gap: 10px; font-size: 14px; margin-bottom: 8px; color: #475569; }
        .check-item i { color: #10B981; } .check-item.failed i { color: #EF4444; }
        
        .eligibility-banner { background: #ECFDF5; border: 1px solid #10B981; border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 20px; margin-bottom: 30px; }
        .eligibility-banner i { font-size: 28px; color: #059669; }
        .eligibility-banner.failed { background: #FEF2F2; border-color: #EF4444; }
        .eligibility-banner.failed i { color: #DC2626; }

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; }
        .form-group { margin-bottom: 20px; }
        .form-group.full { grid-column: span 2; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 8px; color: var(--primary); font-size: 14px; }
        .form-control { width: 100%; padding: 12px 15px; border: 1px solid #E2E8F0; border-radius: 10px; font-family: inherit; font-size: 14px; }
        .form-control:focus { border-color: var(--gold); outline: none; box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1); }
        .btn-group { display: flex; gap: 15px; justify-content: flex-end; margin-top: 20px; }
        .btn-draft { border: 2px solid var(--primary); background: transparent; color: var(--primary); padding: 12px 30px; border-radius: 30px; font-weight: 600; cursor: pointer; transition: 0.3s; }
        .btn-submit { background: #E9C46A; color: var(--primary); border: none; padding: 12px 40px; border-radius: 30px; font-weight: 700; cursor: pointer; transition: 0.3s; box-shadow: 0 4px 10px rgba(233, 196, 106, 0.3); }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(233, 196, 106, 0.4); }

        .archive-empty { text-align: center; padding: 50px; color: #94A3B8; }
        .archive-empty i { font-size: 64px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-brand">HCTS</div>
        <ul class="nav-menu">
            <li><a href="dashboard_siswa.php">Home</a></li>
            <li><a href="evaluasi.php">Evaluasi</a></li>
            <li><a href="pembayaranSiswa.php">Keuangan</a></li>
            <li><a href="#" class="active">Magang</a></li>
        </ul>
        <div class="nav-action">
            <a href="../../app/logout.php" class="btn-logout">Logout</a>
        </div>
    </nav>

    <div class="eval-container">
        <h1 style="font-family: 'Playfair Display', serif; color: #003B73; margin: 40px 0 30px;">Proses Magang</h1>


        <!-- Syarat & Alur -->
        <div class="magang-card">
            <div class="section-header">Pengajuan Magang (On-the-Job Training)</div>
            <div class="info-grid">
                <div class="info-box">
                    <h4><i class="fas fa-clipboard-check"></i> Syarat Pengajuan</h4>
                    <div class="check-item <?= !$is_lunas ? 'failed' : '' ?>">
                        <i class="fas <?= $is_lunas ? 'fa-check' : 'fa-times' ?>"></i> Status siswa aktif & administrasi lunas.
                    </div>
                    <div class="check-item <?= !$is_lulus ? 'failed' : '' ?>">
                        <i class="fas <?= $is_lulus ? 'fa-check' : 'fa-times' ?>"></i> Lulus Evaluasi Akademik (Min. Nilai B).
                    </div>
                    <div class="check-item"><i class="fas fa-check"></i> Sehat jasmani (MCU jika diperlukan).</div>
                </div>
                <div class="info-box">
                    <h4><i class="fas fa-route"></i> Alur Pelaksanaan</h4>
                    <div class="check-item"><strong>1.</strong> &nbsp; Pengajuan Data Industri</div>
                    <div class="check-item"><strong>2.</strong> &nbsp; Verifikasi Akademik & Interview</div>
                    <div class="check-item"><strong>3.</strong> &nbsp; Penerbitan Surat Pengantar</div>
                </div>
            </div>

            <?php if ($can_apply): ?>
                <div class="eligibility-banner">
                    <i class="fas fa-check-circle"></i>
                    <div>
                        <strong style="display: block; color: #065F46;">Anda Memenuhi Syarat Magang</strong>
                        <span style="font-size: 14px; color: #065F46;">Status Evaluasi Akademik Anda dinyatakan LULUS. Silakan lengkapi formulir di bawah ini.</span>
                    </div>
                </div>
            <?php else: ?>
                <div class="eligibility-banner failed">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        <strong style="display: block; color: #991B1B;">Belum Memenuhi Syarat</strong>
                        <span style="font-size: 14px; color: #991B1B;">Mohon selesaikan administrasi atau perbaikan nilai akademik untuk membuka formulir pengajuan.</span>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($can_apply): ?>
            <?php if ($is_pending_magang): ?>
                <div class="magang-card" style="text-align: center; border-left: 5px solid #F59E0B;">
                    <i class="fas fa-history" style="font-size: 48px; color: #F59E0B; margin-bottom: 20px;"></i>
                    <h2 style="color: #003B73;">Pengajuan Sedang Diverifikasi</h2>
                    <p style="color: #64748B; max-width: 600px; margin: 0 auto;">Anda telah mengirimkan rencana magang. Saat ini Pimpinan sedang meninjau data industri yang Anda ajukan. Tunggu hingga status disetujui untuk melanjutkan ke tahap berikutnya.</p>
                </div>
            <?php else: ?>
                <!-- Formulir Rencana Magang -->
                <div class="magang-card">
                    <div class="section-header">Formulir Rencana Magang</div>
                    <form action="../../backend/magang_end.php" method="POST">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nama Perusahaan / Hotel</label>
                        <input type="text" name="nama_perusahaan" class="form-control" placeholder="Contoh: Ritz Carlton, Marriott, etc." required>
                        <small style="color: #94A3B8; font-size: 12px;">Pastikan nama perusahaan sesuai ejaan resmi</small>
                    </div>
                    <div class="form-group">
                        <label>Posisi / Departemen</label>
                        <input type="text" name="posisi" class="form-control" placeholder="Contoh: F&B Service, Kitchen, etc." required>
                    </div>
                    <div class="form-group">
                        <label>Lokasi (Kota, Negara)</label>
                        <input type="text" name="lokasi" class="form-control" placeholder="Contoh: Jakarta, Indonesia / Dubai, UAE" required>
                    </div>
                    <div class="form-group" style="display: flex; gap: 15px;">
                        <div style="flex: 1;">
                            <label>Periode Pelaksanaan (Mulai)</label>
                            <input type="date" name="tanggal_mulai" class="form-control" required>
                        </div>
                        <div style="flex: 1;">
                            <label>Selesai</label>
                            <input type="date" name="tanggal_selesai" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group full">
                        <label>Kontak Person Industri (Jika Ada)</label>
                        <input type="text" name="kontak_person" class="form-control" placeholder="Nama HRD / Supervisor - Kontak">
                    </div>
                </div>
                <div class="btn-group">
                    <button type="submit" name="action" value="draft" class="btn-draft">Simpan Draft</button>
                    <button type="submit" name="action" value="submit" class="btn-submit">Ajukan Permohonan</button>
                </div>
            </form>
        </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Arsip -->
        <div class="magang-card">
            <div class="section-header">Arsip</div>
            <?php if (mysqli_num_rows($query_riwayat) > 0): ?>
                <table class="eval-table">
                    <thead><tr><th>Perusahaan</th><th>Posisi</th><th>Tanggal</th><th>Status</th></tr></thead>
                    <tbody>
                        <?php while($r = mysqli_fetch_assoc($query_riwayat)): ?>
                        <tr>
                            <td><?= htmlspecialchars($r['nama_perusahaan']) ?></td>
                            <td><?= htmlspecialchars($r['posisi']) ?></td>
                            <td><?= $r['tanggal_mulai'] ?> s/d <?= $r['tanggal_selesai'] ?></td>
                            <td>
                                <span class="badge" style="background: <?= $r['status_magang'] == 'disetujui' ? '#DEF7EC' : '#FEF3C7' ?>; color: <?= $r['status_magang'] == 'disetujui' ? '#03543F' : '#92400E' ?>;">
                                    <?= strtoupper($r['status_magang']) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="archive-empty">
                    <i class="fas fa-folder-open"></i>
                    <p>Belum ada riwayat magang</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer Space -->
    <div style="height: 50px;"></div>
</body>
</html>
