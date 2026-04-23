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

// Ambil Pengajuan Aktif (Bukan draft) untuk Tracker
$query_aktif = mysqli_query($conn, "SELECT * FROM magang WHERE id_siswa = $id_siswa AND status_magang != 'draft' ORDER BY id_magang DESC LIMIT 1");
$magang_aktif = mysqli_fetch_assoc($query_aktif);

$status_magang = $magang_aktif['status_magang'] ?? 'none';
$status_laporan = $magang_aktif['status_laporan'] ?? 'none';

// Logika Step Tracker
$current_step = 1;
if ($status_magang == 'pending')
    $current_step = 1;
if ($status_magang == 'disetujui')
    $current_step = 2;
if ($status_magang == 'berlangsung')
    $current_step = 3;
if ($status_magang == 'selesai')
    $current_step = 4;

$is_pending_pimpinan = ($status_magang == 'pending');
$is_pending_admin = ($status_magang == 'disetujui');
$is_aktif_magang = ($status_magang == 'berlangsung' && empty($magang_aktif['file_laporan']));
$is_verifikasi_laporan = (!empty($magang_aktif['file_laporan']) && ($status_laporan === 'pending' || $status_laporan === 'ditolak'));
// Internship is considered 'finished' in terms of UI once the report is approved
$is_selesai_magang = ($status_laporan === 'disetujui' || $status_magang === 'selesai');
$is_sertifikat_ready = !empty($magang_aktif['no_sertifikat']);
$is_rejected_magang = ($status_magang === 'ditolak');
$has_uploaded = !empty($magang_aktif['file_laporan']);
$is_disetujui = ($status_laporan === 'disetujui');
$is_ditolak = ($status_laporan === 'ditolak');


?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proses Magang - HCTS</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../style/dashboard_siswa.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../style/evaluasi_siswa.css?v=<?= time() ?>">
    <style>
        :root {
            --primary: #003B73;
            --gold: #D4AF37;
            --bg-light: #F8FAFC;
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Poppins', sans-serif;
        }

        .page-title {
            font-family: 'Playfair Display', serif;
            color: var(--primary);
            margin: 40px auto 30px;
            width: 90%;
            max-width: 1100px;
            font-size: 32px;
        }

        .magang-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            padding: 35px;
            border: 1px solid #edf2f7;
        }

        .section-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 25px;
            color: var(--primary);
            font-family: 'Playfair Display', serif;
            font-size: 22px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 25px;
        }

        .info-box {
            background: #F1F5F9;
            padding: 20px;
            border-radius: 12px;
        }

        .info-box h4 {
            margin-bottom: 15px;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .check-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            margin-bottom: 8px;
            color: #475569;
        }

        .check-item i {
            color: #10B981;
        }

        .check-item.failed i {
            color: #EF4444;
        }

        .eligibility-banner {
            background: #ECFDF5;
            border: 1px solid #10B981;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        .eligibility-banner i {
            font-size: 28px;
            color: #059669;
        }

        .eligibility-banner.failed {
            background: #FEF2F2;
            border-color: #EF4444;
        }

        .eligibility-banner.failed i {
            color: #DC2626;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group.full {
            grid-column: span 2;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--primary);
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #E2E8F0;
            border-radius: 10px;
            font-family: inherit;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: var(--gold);
            outline: none;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        }

        .btn-group {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .btn-draft {
            border: 2px solid var(--primary);
            background: transparent;
            color: var(--primary);
            padding: 12px 30px;
            border-radius: 30px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-submit {
            background: #E9C46A;
            color: var(--primary);
            border: none;
            padding: 12px 40px;
            border-radius: 30px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 4px 10px rgba(233, 196, 106, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(233, 196, 106, 0.4);
        }

        .archive-empty {
            text-align: center;
            padding: 50px;
            color: #94A3B8;
        }

        .archive-empty i {
            font-size: 64px;
            margin-bottom: 15px;
        }

        /* Progress Tracker Styles */
        .progress-container {
            margin: 40px 0;
            display: flex;
            justify-content: space-between;
            position: relative;
            width: 100%;
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
        }

        .progress-container::before {
            content: "";
            background: #E2E8F0;
            height: 4px;
            width: 100%;
            position: absolute;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
            z-index: 1;
        }

        .progress-bar-fill {
            background: var(--primary);
            height: 4px;
            position: absolute;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
            z-index: 1;
            transition: 0.4s ease;
        }

        .step {
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            position: relative;
        }

        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            border: 4px solid #E2E8F0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #94A3B8;
            transition: 0.4s ease;
        }

        .step.active .step-circle {
            border-color: #E9C46A;
            color: var(--primary);
            background: #FEF3C7;
        }

        .step.completed .step-circle {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
        }

        .step-label {
            font-size: 13px;
            font-weight: 600;
            color: #64748B;
            position: absolute;
            top: 50px;
            white-space: nowrap;
        }

        .step.active .step-label {
            color: var(--primary);
        }

        .step.completed .step-label {
            color: var(--primary);
        }

        .status-alert {
            background: #ECFDF5;
            border: 1px solid #10B981;
            border-radius: 12px;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
            color: #065F46;
            font-weight: 500;
        }

        .status-alert i {
            margin-right: 10px;
        }

        .status-alert.rejected {
            background: #FEF2F2;
            border-color: #EF4444;
            color: #991B1B;
        }

        .status-hero {
            background: white;
            border-radius: 20px;
            padding: 40px;
            border: 1px solid #E2E8F0;
            display: flex;
            align-items: center;
            gap: 30px;
            margin-bottom: 40px;
        }

        .status-icon-box {
            width: 100px;
            height: 100px;
            border-radius: 20px;
            background: #FFF7ED;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: #F97316;
        }

        .status-icon-box.approved {
            background: #F0FDF4;
            color: #16A34A;
        }

        .status-icon-box.finished {
            background: #F5F3FF;
            color: #7C3AED;
        }

        .status-content h2 {
            font-family: 'Playfair Display', serif;
            color: var(--primary);
            margin-bottom: 8px;
            font-size: 24px;
        }

        .status-content p {
            color: #64748B;
            font-size: 16px;
            line-height: 1.5;
        }

        .info-card-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-bottom: 40px;
        }

        .detail-info-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            border: 1px solid #E2E8F0;
        }

        .detail-info-card h3 {
            font-size: 18px;
            color: var(--primary);
            margin-bottom: 20px;
            border-bottom: 1px solid #F1F5F9;
            padding-bottom: 10px;
        }

        .info-row-detail {
            display: flex;
            margin-bottom: 12px;
            font-size: 14px;
        }

        .info-row-detail span:first-child {
            width: 120px;
            color: #94A3B8;
        }

        .info-row-detail span:last-child {
            font-weight: 600;
            color: #334155;
        }

        .btn-download-template {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            padding: 15px 25px;
            border-radius: 12px;
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            transition: 0.3s;
            margin-top: 15px;
        }

        .btn-download-template:hover {
            background: #F1F5F9;
            border-color: var(--primary);
        }

        .status-pill {
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
        }

        .status-pill.success {
            background: #DCFCE7;
            color: #166534;
        }

        .status-pill.warning {
            background: #FEF3C7;
            color: #92400E;
        }

        .status-pill.danger {
            background: #FEE2E2;
            color: #991B1B;
        }

        .status-pill.info {
            background: #E0F2FE;
            color: #075985;
        }
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
        <h1 style="font-family: 'Playfair Display', serif; color: #003B73; margin: 40px 0 10px;">Proses Magang</h1>

        <?php if (isset($_GET['success'])): ?>
            <div class="status-alert">
                <span><i class="fas fa-check-circle"></i> Pengajuan Anda berhasil dikirim! pantau status pengajuan magang
                    Anda.</span>
                <i class="fas fa-times cursor-pointer"></i>
            </div>
        <?php endif; ?>

        <?php if ($is_rejected_magang): ?>
            <div class="status-alert rejected">
                <span><i class="fas fa-exclamation-circle"></i> Pengajuan magang Anda di
                    <strong><?= htmlspecialchars($magang_aktif['nama_perusahaan']) ?></strong> ditolak. Silakan ajukan
                    kembali dengan data industri yang berbeda.</span>
            </div>
        <?php endif; ?>

        <!-- Progress Tracker -->
        <div class="magang-card" style="padding-bottom: 70px;">
            <div class="section-header" style="margin-bottom: 50px;">Pengajuan Magang (On-the-Job Training)</div>

            <div class="progress-container">
                <div class="progress-bar-fill" style="width: <?= (($current_step - 1) / 3) * 100 ?>%"></div>

                <div class="step <?= $current_step >= 1 ? 'completed' : '' ?>">
                    <div class="step-circle"><?= $current_step > 1 ? '<i class="fas fa-check"></i>' : '1' ?></div>
                    <span class="step-label">Persetujuan Pimpinan</span>
                </div>

                <div class="step <?= $current_step >= 2 ? ($current_step > 2 ? 'completed' : 'active') : '' ?>">
                    <div class="step-circle"><?= $current_step > 2 ? '<i class="fas fa-check"></i>' : '2' ?></div>
                    <span class="step-label">Verifikasi Admin</span>
                </div>

                <div class="step <?= $current_step >= 3 ? ($current_step > 3 ? 'completed' : 'active') : '' ?>">
                    <div class="step-circle"><?= $current_step > 3 ? '<i class="fas fa-check"></i>' : '3' ?></div>
                    <span class="step-label">Magang Aktif</span>
                </div>

                <div class="step <?= $current_step == 4 ? 'active' : '' ?>">
                    <div class="step-circle">4</div>
                    <span class="step-label">Selesai Magang</span>
                </div>
            </div>
        </div>

        <?php if ($magang_aktif && $status_magang !== 'ditolak'): ?>
            <?php if ($is_selesai_magang): ?>
                <!-- Tampilan Hasil Magang (Muncul setelah laporan disetujui) -->
                <div class="status-hero">
                    <div class="status-icon-box <?= $is_sertifikat_ready ? 'finished' : '' ?>" style="<?= !$is_sertifikat_ready ? 'background: #EFF6FF; color: #3B82F6;' : '' ?>">
                        <i class="fas <?= $is_sertifikat_ready ? 'fa-certificate' : 'fa-medal' ?>"></i>
                    </div>
                    <div class="status-content">
                        <h2>Magang Selesai</h2>
                        <p><?= $is_sertifikat_ready
                            ? 'Selamat! Sertifikat magang Anda telah diterbitkan. Silakan unduh hasil penilaian dan sertifikat Anda.'
                            : 'Admin sedang memproses penilaian akhir dan penerbitan sertifikat Anda.' ?></p>
                    </div>
                </div>

                <div class="magang-card">
                    <div class="section-header">Hasil Evaluasi & Sertifikat</div>
                    <div class="info-card-grid">
                        <div class="detail-info-card">
                            <h3>Hasil Penilaian OJT</h3>
                            <?php
                            $q_nilai = mysqli_query($conn, "SELECT * FROM nilai_magang WHERE id_magang = " . $magang_aktif['id_magang']);
                            $n = mysqli_fetch_assoc($q_nilai);
                            // Cek apakah nilai sudah diinput (bukan semua 0 atau null)
                            $nilai_sudah_diinput = $n && ($n['job_knowledge'] > 0 || $n['quantity_of_work'] > 0 || $n['quality_of_work'] > 0);
                            ?>
                            <?php if ($nilai_sudah_diinput): ?>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                                    <div class="info-row-detail"><span>Job Knowledge</span><span>: <?= $n['job_knowledge'] ?></span></div>
                                    <div class="info-row-detail"><span>Quantity</span><span>: <?= $n['quantity_of_work'] ?></span></div>
                                    <div class="info-row-detail"><span>Quality</span><span>: <?= $n['quality_of_work'] ?></span></div>
                                    <div class="info-row-detail"><span>Character</span><span>: <?= $n['character_val'] ?></span></div>
                                    <div class="info-row-detail"><span>Personality</span><span>: <?= $n['personality'] ?></span></div>
                                    <div class="info-row-detail"><span>Courtesy</span><span>: <?= $n['courtesy'] ?></span></div>
                                    <div class="info-row-detail"><span>Appearance</span><span>: <?= $n['personal_appearance'] ?></span></div>
                                    <div class="info-row-detail"><span>Attendance</span><span>: <?= $n['attendance'] ?></span></div>
                                </div>
                                <?php if (!empty($n['evaluasi_laporan'])): ?>
                                <div style="margin-top: 15px; padding-top: 10px; border-top: 1px solid #eee;">
                                    <span style="font-size: 12px; color: #64748B;">Catatan: <?= htmlspecialchars($n['evaluasi_laporan']) ?></span>
                                </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div style="text-align: center; padding: 30px 20px; color: #94A3B8;">
                                    <i class="fas fa-clock" style="font-size: 32px; margin-bottom: 12px; color: #CBD5E1;"></i>
                                    <p style="font-size: 13px; font-weight: 600; color: #64748B;">Nilai belum diinputkan</p>
                                    <p style="font-size: 12px; margin-top: 5px;">Admin sedang menyiapkan penilaian akhir untuk magang Anda.</p>
                                </div>
                            <?php endif; ?>

                        </div>

                        <div class="detail-info-card">
                            <h3>Sertifikat Digital</h3>
                            <?php if ($is_sertifikat_ready): ?>
                                <div
                                    style="background: #F0FDF4; padding: 15px; border-radius: 12px; border: 1px solid #BBF7D0; margin-bottom: 15px;">
                                    <p style="font-size: 13px; color: #166534; font-weight: 600;">Sertifikat Tersedia!</p>
                                    <p style="font-size: 11px; color: #15803D;">No:
                                        <?= htmlspecialchars($magang_aktif['no_sertifikat']) ?></p>
                                </div>
                                <a href="sertifikat.php" class="btn-submit"
                                    style="display: block; text-align: center; background: #D4AF37; color: #003B73; text-decoration: none;">
                                    <i class="fas fa-download"></i> Download Sertifikat
                                </a>
                            <?php else: ?>
                                <div
                                    style="background: #F8FAFC; padding: 20px; border-radius: 12px; border: 1px solid #E2E8F0; text-align: center;">
                                    <i class="fas fa-hourglass-half"
                                        style="font-size: 24px; color: #94A3B8; margin-bottom: 10px;"></i>
                                    <p style="font-size: 13px; color: #64748B;">Sertifikat sedang dalam antrean penerbitan.</p>
                                </div>
                                <button disabled class="btn-submit"
                                    style="width: 100%; margin-top: 15px; opacity: 0.6; cursor: not-allowed;">
                                    Belum Tersedia
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Card: Riwayat Unggah Dokumen -->
                <div class="magang-card">
                    <div class="section-header">Riwayat Unggah Dokumen</div>
                    <?php
                    $q_docs = mysqli_query($conn, "SELECT * FROM magang WHERE id_siswa = $id_siswa AND file_laporan IS NOT NULL AND file_laporan != '' ORDER BY id_magang DESC");
                    if (mysqli_num_rows($q_docs) > 0):
                        ?>
                        <div style="overflow-x: auto;">
                            <table style="width: 100%; border-collapse: collapse;">
                                <thead>
                                    <tr style="background: #F8FAFC;">
                                        <th
                                            style="padding: 12px 15px; text-align: left; border-bottom: 2px solid #E2E8F0; color: var(--primary); font-size: 13px; font-weight: 600;">
                                            Nama File</th>
                                        <th
                                            style="padding: 12px 15px; text-align: left; border-bottom: 2px solid #E2E8F0; color: var(--primary); font-size: 13px; font-weight: 600;">
                                            Periode Magang</th>
                                        <th
                                            style="padding: 12px 15px; text-align: center; border-bottom: 2px solid #E2E8F0; color: var(--primary); font-size: 13px; font-weight: 600;">
                                            Status</th>
                                        <th
                                            style="padding: 12px 15px; text-align: center; border-bottom: 2px solid #E2E8F0; color: var(--primary); font-size: 13px; font-weight: 600;">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($doc = mysqli_fetch_assoc($q_docs)): ?>
                                        <?php
                                        $sl = $doc['status_laporan'] ?? 'pending';
                                        $sl_label = match ($sl) {
                                            'disetujui' => ['Disetujui', 'success'],
                                            'ditolak' => ['Ditolak', 'danger'],
                                            default => ['Menunggu', 'info'],
                                        };
                                        ?>
                                        <tr style="border-bottom: 1px solid #F1F5F9;">
                                            <td style="padding: 14px 15px;">
                                                <div style="display: flex; align-items: center; gap: 10px;">
                                                    <div
                                                        style="width: 36px; height: 36px; background: #FFF1F2; color: #E11D48; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                        <i class="fas fa-file-pdf"></i>
                                                    </div>
                                                    <span
                                                        style="font-size: 13px; font-weight: 600; color: #334155;"><?= htmlspecialchars($doc['file_laporan']) ?></span>
                                                </div>
                                            </td>
                                            <td style="padding: 14px 15px; font-size: 13px; color: #64748B;">
                                                <?= date('d M Y', strtotime($doc['tanggal_mulai'])) ?> &ndash;
                                                <?= date('d M Y', strtotime($doc['tanggal_selesai'])) ?>
                                            </td>
                                            <td style="padding: 14px 15px; text-align: center;">
                                                <span class="status-pill <?= $sl_label[1] ?>"><?= $sl_label[0] ?></span>
                                            </td>
                                            <td style="padding: 14px 15px; text-align: center;">
                                                <a href="../../assets/uploads/laporan/<?= htmlspecialchars($doc['file_laporan']) ?>"
                                                    target="_blank"
                                                    style="display: inline-flex; align-items: center; gap: 6px; background: var(--primary); color: white; padding: 7px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; text-decoration: none; transition: 0.2s;"
                                                    onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                                                    <i class="fas fa-eye"></i> Lihat
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="archive-empty">
                            <i class="fas fa-folder-open"></i>
                            <p>Belum ada dokumen yang diunggah.</p>
                        </div>
                    <?php endif; ?>
                </div>

            <?php else: ?>
                <!-- Tampilan Magang Berjalan / Verifikasi Laporan / Menunggu Sertifikat -->
                <?php if ($is_pending_pimpinan): ?>

                    <div class="status-hero">
                        <div class="status-icon-box">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        <div class="status-content">
                            <h2>Menunggu Persetujuan Pimpinan</h2>
                            <p>Pengajuan Anda sedang menunggu persetujuan dari pimpinan. Mohon menunggu proses evaluasi internal.
                            </p>
                        </div>
                    </div>
                <?php elseif ($is_pending_admin): ?>
                    <div class="status-hero">
                        <div class="status-icon-box" style="background: #E0F2FE; color: #0369A1;">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="status-content">
                            <h2>Menunggu Verifikasi Admin</h2>
                            <p>Pimpinan telah menyetujui pengajuan Anda. Saat ini admin sedang melakukan verifikasi akhir sebelum
                                magang Anda diaktifkan.</p>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Hero Section based on State -->
                    <?php if ($is_aktif_magang): ?>
                        <div class="status-hero">
                            <div class="status-icon-box approved">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <div class="status-content">
                                <h2>Magang Sedang Berlangsung</h2>
                                <p>Selamat bekerja! Saat ini Anda sedang melaksanakan program magang di
                                    <strong><?= htmlspecialchars($magang_aktif['nama_perusahaan']) ?></strong>.
                                </p>
                            </div>
                        </div>

                        <!-- NEW: Download Logbook Template -->
                        <div class="magang-card" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: white; border: none;">
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 20px;">
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                                        <i class="fas fa-file-invoice"></i>
                                    </div>
                                    <div>
                                        <h3 style="margin: 0; font-size: 1.2rem;">Laporan Kegiatan Harian (Logbook)</h3>
                                        <p style="margin: 5px 0 0; opacity: 0.7; font-size: 0.85rem;">Gunakan format resmi ini untuk mencatat aktivitas harian Anda selama magang.</p>
                                    </div>
                                </div>
                                <a href="download_template.php?code=laporan_harian" target="_blank" style="background: #E9C46A; color: #003B73; text-decoration: none; padding: 10px 20px; border-radius: 8px; font-weight: 700; white-space: nowrap; display: flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-download"></i> Unduh Format
                                </a>
                            </div>
                        </div>
                    <?php elseif ($is_waiting_sertifikat): ?>
                        <div class="status-hero">
                            <div class="status-icon-box" style="background: #ECFDF5; color: #10B981;">
                                <i class="fas fa-check-double"></i>
                            </div>
                            <div class="status-content">
                                <h2>Laporan Disetujui</h2>
                                <p>Laporan harian Anda telah disetujui oleh admin. Saat ini sertifikat Anda sedang dalam proses
                                    penerbitan.</p>
                            </div>
                        </div>
                    <?php elseif ($is_verifikasi_laporan): ?>
                        <div class="status-hero">
                            <div class="status-icon-box finished">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <div class="status-content">
                                <h2>Verifikasi Laporan</h2>
                                <p>Laporan harian Anda sedang dalam proses validasi oleh admin. Mohon tunggu informasi selanjutnya.</p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Internship Details & Document Section (Visible for all active/verifying states) -->
                    <div class="info-card-grid">
                        <div class="detail-info-card">
                            <div
                                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #F1F5F9; padding-bottom: 10px;">
                                <h3 style="margin-bottom: 0; border-bottom: none; padding-bottom: 0;">Detail Magang</h3>
                                <span class="status-pill <?= $status_magang === 'selesai' ? 'success' : 'warning' ?>">
                                    <?= htmlspecialchars($status_magang) ?>
                                </span>
                            </div>
                            <div class="info-row-detail"><span>Industri/Hotel</span><span>:
                                    <?= htmlspecialchars($magang_aktif['nama_perusahaan']) ?></span></div>
                            <div class="info-row-detail"><span>Posisi</span><span>:
                                    <?= htmlspecialchars($magang_aktif['posisi']) ?></span></div>
                            <div class="info-row-detail"><span>Periode</span><span>:
                                    <?= date('d M', strtotime($magang_aktif['tanggal_mulai'])) ?> -
                                    <?= date('d M Y', strtotime($magang_aktif['tanggal_selesai'])) ?></span></div>
                        </div>
                        <div class="detail-info-card">
                            <div
                                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #F1F5F9; padding-bottom: 10px;">
                                <h3 style="margin-bottom: 0; border-bottom: none; padding-bottom: 0;">Dokumen Harian</h3>
                                <?php if ($has_uploaded): ?>
                                    <span class="status-pill <?= $is_disetujui ? 'success' : ($is_ditolak ? 'danger' : 'info') ?>">
                                        <?= $is_disetujui ? 'Disetujui' : ($is_ditolak ? 'Ditolak' : 'Menunggu') ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <?php if ($is_ditolak): ?>
                                <div
                                    style="background: #FEF2F2; color: #991B1B; padding: 15px; border-radius: 10px; margin-bottom: 15px; border: 1px solid #FCA5A5;">
                                    <strong style="font-size: 12px; display: block; margin-bottom: 5px;"><i
                                            class="fas fa-exclamation-circle"></i> Revisi Diperlukan:</strong>
                                    <?php
                                    $q_eval = mysqli_query($conn, "SELECT evaluasi_laporan FROM nilai_magang WHERE id_magang = " . $magang_aktif['id_magang']);
                                    $eval = mysqli_fetch_assoc($q_eval);
                                    ?>
                                    <p style="font-size: 11px; opacity: 0.9;">
                                        <?= htmlspecialchars($eval['evaluasi_laporan'] ?? 'Silakan cek kembali dokumen Anda.') ?>
                                    </p>
                                </div>
                            <?php endif; ?>

                            <?php if ($is_disetujui): ?>
                                <div
                                    style="background: #F0FDF4; border: 1px solid #BBF7D0; padding: 15px; border-radius: 10px; text-align: center;">
                                    <i class="fas fa-check-circle" style="color: #16A34A; font-size: 24px; margin-bottom: 10px;"></i>
                                    <p style="font-size: 12px; color: #166534; font-weight: 600;">Laporan Anda telah disetujui.</p>
                                    <p style="font-size: 11px; color: #14532D; margin-top: 5px;">Menunggu admin menerbitkan sertifikat.
                                    </p>
                                </div>
                            <?php elseif ($is_verifikasi_laporan && !$is_ditolak): ?>
                                <div
                                    style="background: #F0F9FF; border: 1px solid #BAE6FD; padding: 15px; border-radius: 10px; text-align: center;">
                                    <i class="fas fa-clock" style="color: #0EA5E9; font-size: 24px; margin-bottom: 10px;"></i>
                                    <p style="font-size: 12px; color: #0369A1; font-weight: 600;">Sedang Diverifikasi</p>
                                    <p style="font-size: 11px; color: #0C4A6E; margin-top: 5px;">File:
                                        <?= htmlspecialchars($magang_aktif['file_laporan']) ?>
                                    </p>
                                </div>
                            <?php else: ?>
                                <p style="font-size: 13px; color: #64748B; margin-bottom: 15px;">Unggah laporan harian (PDF) yang telah
                                    ditandatangani pihak industri.</p>
                                <form action="../../backend/selesaiMagang.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="magang_id" value="<?= $magang_aktif['id_magang'] ?>">
                                    <input type="file" name="file_laporan" accept=".pdf" required
                                        style="width: 100%; font-size: 12px; padding: 8px; border: 1px solid #E2E8F0; border-radius: 8px; background: #F8FAFC; margin-bottom: 10px;">
                                    <button type="submit" class="btn-submit"
                                        style="width: 100%; background: var(--primary); color: white; border-radius: 8px; padding: 10px;"><?= $is_ditolak ? 'Upload Ulang Revisi' : 'Upload & Selesaikan Magang' ?></button>
                                </form>
                            <?php endif; ?>

                            <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #F1F5F9;">
                                <a href="../../assets/templates/template_laporan_harian.pdf"
                                    style="font-size: 12px; color: var(--primary); text-decoration: none; font-weight: 600;"
                                    download>
                                    <i class="fas fa-file-download"></i> Download Template Laporan
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

        <?php elseif ($can_apply): ?>
            <!-- FORMULIR PENGAJUAN (Muncul jika tidak ada pengajuan aktif atau jika ditolak) -->
            <div class="magang-card">
                <div class="section-header">Formulir Rencana Magang</div>
                <form action="../../backend/magang_end.php" method="POST">

                    <div class="form-grid">
                        <div class="form-group">
                            <label>Nama Perusahaan / Hotel</label>
                            <input type="text" name="nama_perusahaan" class="form-control"
                                placeholder="Contoh: Ritz Carlton, Marriott, etc." required>
                            <small style="color: #94A3B8; font-size: 12px;">Pastikan nama perusahaan sesuai ejaan
                                resmi</small>
                        </div>
                        <div class="form-group">
                            <label>Posisi / Departemen</label>
                            <input type="text" name="posisi" class="form-control"
                                placeholder="Contoh: F&B Service, Kitchen, etc." required>
                        </div>
                        <div class="form-group">
                            <label>Lokasi (Kota, Negara)</label>
                            <input type="text" name="lokasi" class="form-control"
                                placeholder="Contoh: Jakarta, Indonesia / Dubai, UAE" required>
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
                            <input type="text" name="kontak_person" class="form-control"
                                placeholder="Nama HRD / Supervisor - Kontak">
                        </div>
                    </div>
                    <div class="btn-group">
                        <button type="submit" name="action" value="draft" class="btn-draft">Simpan Draft</button>
                        <button type="submit" name="action" value="submit" class="btn-submit">Ajukan Permohonan</button>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <!-- Tampilan Tidak Memenuhi Syarat -->
            <div class="magang-card">
                <div class="section-header">Pemberitahuan</div>
                <div class="archive-empty">
                    <i class="fas fa-lock" style="font-size: 48px; color: #CBD5E1; margin-bottom: 20px;"></i>
                    <p style="color: #64748B;">Anda belum memenuhi syarat untuk mengajukan magang.<br>Pastikan pembayaran DP
                        telah lunas dan Anda telah lulus evaluasi akademik.</p>
                </div>
            </div>
        <?php endif; ?>


        <!-- Arsip -->
        <div class="magang-card">
            <div class="section-header">Riwayat & Arsip Pengajuan</div>
            <?php
            $query_riwayat = mysqli_query($conn, "SELECT * FROM magang WHERE id_siswa = $id_siswa ORDER BY id_magang DESC");
            if (mysqli_num_rows($query_riwayat) > 0):
                ?>
                <div class="table-responsive" style="overflow-x: auto;">
                    <table class="eval-table" style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                        <thead>
                            <tr style="background: #F8FAFC; text-align: left;">
                                <th
                                    style="padding: 15px; border-bottom: 2px solid #E2E8F0; color: var(--primary); font-size: 14px;">
                                    Industri / Hotel</th>
                                <th
                                    style="padding: 15px; border-bottom: 2px solid #E2E8F0; color: var(--primary); font-size: 14px;">
                                    Posisi</th>
                                <th
                                    style="padding: 15px; border-bottom: 2px solid #E2E8F0; color: var(--primary); font-size: 14px;">
                                    Periode</th>
                                <th
                                    style="padding: 15px; border-bottom: 2px solid #E2E8F0; color: var(--primary); font-size: 14px;">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($r = mysqli_fetch_assoc($query_riwayat)): ?>
                                <tr style="border-bottom: 1px solid #F1F5F9;">
                                    <td style="padding: 15px; font-weight: 600; color: #334155;">
                                        <?= htmlspecialchars($r['nama_perusahaan']) ?>
                                    </td>
                                    <td style="padding: 15px; color: #64748B; font-size: 14px;">
                                        <?= htmlspecialchars($r['posisi']) ?>
                                    </td>
                                    <td style="padding: 15px; color: #64748B; font-size: 13px;">
                                        <?= date('d/m/Y', strtotime($r['tanggal_mulai'])) ?> -
                                        <?= date('d/m/Y', strtotime($r['tanggal_selesai'])) ?>
                                    </td>
                                    <td style="padding: 15px;">
                                        <?php
                                        $bg = "#F1F5F9";
                                        $cl = "#64748B";
                                        if ($r['status_magang'] == 'pending') {
                                            $bg = "#FEF3C7";
                                            $cl = "#92400E";
                                        }
                                        if ($r['status_magang'] == 'disetujui') {
                                            $bg = "#DEF7EC";
                                            $cl = "#03543F";
                                        }
                                        if ($r['status_magang'] == 'selesai') {
                                            $bg = "#E0E7FF";
                                            $cl = "#3730A3";
                                        }
                                        if ($r['status_magang'] == 'ditolak') {
                                            $bg = "#FEE2E2";
                                            $cl = "#991B1B";
                                        }
                                        ?>
                                        <span class="badge"
                                            style="background: <?= $bg ?>; color: <?= $cl ?>; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                                            <?= $r['status_magang'] ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="archive-empty">
                    <i class="fas fa-folder-open"></i>
                    <p>Belum ada riwayat pengajuan magang</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer Space -->
    <div style="height: 50px;"></div>
</body>

</html>