<?php
session_start();
require_once __DIR__ . '/../../config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../../public/login/logSiswa.php");
    exit;
}

$id_user = $_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT id_siswa, nama_lengkap FROM siswa WHERE id_user = $id_user");
$siswa = mysqli_fetch_assoc($query);
$id_siswa = $siswa['id_siswa'];

// Cek Sertifikat
$query_cert = mysqli_query($conn, "SELECT no_sertifikat FROM magang WHERE id_siswa = $id_siswa AND no_sertifikat IS NOT NULL LIMIT 1");
$has_certificate = mysqli_num_rows($query_cert) > 0;

// Cek Status Taiwan
$query_taiwan = mysqli_query($conn, "SELECT * FROM program_taiwan WHERE id_siswa = $id_siswa");
$taiwan_data = mysqli_fetch_assoc($query_taiwan);
$taiwan_status = $taiwan_data['status'] ?? null;

// Tahapan Tracker
$current_step = 1;
if ($taiwan_status == 'berminat') $current_step = 2;
if ($taiwan_status == 'diajukan_mitra') $current_step = 3;
if ($taiwan_status == 'lolos') $current_step = 5;

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Magang Taiwan - HCTS</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../style/dashboard_siswa.css?v=<?= time() ?>">
    <style>
        :root {
            --primary: #003B73;
            --gold: #E9C46A;
            --navy-dark: #002D5A;
        }

        body { background: #f4f7f6; font-family: 'Poppins', sans-serif; }

        .program-container { max-width: 1100px; margin: 40px auto; padding: 0 20px; }

        .hero-banner {
            background: linear-gradient(135deg, var(--primary) 0%, var(--navy-dark) 100%);
            border-radius: 24px;
            padding: 50px;
            color: white;
            display: flex;
            align-items: center;
            gap: 40px;
            margin-bottom: 40px;
            box-shadow: 0 20px 40px rgba(0, 59, 115, 0.2);
            position: relative;
            overflow: hidden;
        }

        .hero-banner::after {
            content: '';
            position: absolute;
            right: -100px;
            bottom: -100px;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }

        .hero-icon {
            width: 100px;
            height: 100px;
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 50px;
            flex-shrink: 0;
        }

        .hero-text h1 { font-family: 'Playfair Display', serif; font-size: 36px; margin-bottom: 10px; }
        .hero-text p { font-size: 16px; opacity: 0.9; line-height: 1.6; }

        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 40px; }
        .info-card { background: white; border-radius: 20px; padding: 35px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); border: 1px solid #edf2f7; }
        .info-card h2 { font-family: 'Playfair Display', serif; color: var(--primary); font-size: 22px; margin-bottom: 25px; display: flex; align-items: center; gap: 15px; }
        .info-card h2 i { color: var(--gold); }

        .feature-list { list-style: none; }
        .feature-item { display: flex; gap: 15px; margin-bottom: 20px; font-size: 14px; color: #4a5568; line-height: 1.5; }
        .feature-item i { color: #48bb78; margin-top: 3px; }

        .timeline-section { background: white; border-radius: 24px; padding: 50px; text-align: center; box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
        .timeline-section h2 { font-family: 'Playfair Display', serif; color: var(--primary); margin-bottom: 50px; font-size: 24px; }

        /* Step Tracker */
        .steps { display: flex; justify-content: space-between; position: relative; max-width: 900px; margin: 0 auto 60px; }
        .steps::before { content: ''; position: absolute; top: 20px; left: 0; right: 0; height: 3px; background: #e2e8f0; z-index: 1; }
        .step { position: relative; z-index: 2; width: 150px; }
        .step-circle { width: 40px; height: 40px; background: white; border: 3px solid #e2e8f0; border-radius: 50%; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #94a3b8; transition: 0.3s; }
        .step.active .step-circle { border-color: var(--gold); background: #FEF3C7; color: var(--primary); }
        .step.completed .step-circle { background: var(--primary); border-color: var(--primary); color: white; }
        .step-label { font-size: 12px; font-weight: 600; color: #64748b; }

        .cta-box { background: #F8FBFF; border: 1px dashed #BFDBFE; border-radius: 16px; padding: 40px; margin-top: 30px; }
        .cta-box h3 { font-family: 'Playfair Display', serif; color: var(--primary); margin-bottom: 15px; }
        .cta-box p { color: #64748b; font-size: 14px; margin-bottom: 25px; line-height: 1.6; }

        .btn-interest { background: var(--gold); color: var(--primary); border: none; padding: 16px 40px; border-radius: 50px; font-weight: 700; font-size: 16px; cursor: pointer; transition: 0.3s; display: inline-flex; align-items: center; gap: 12px; box-shadow: 0 10px 20px rgba(233, 196, 106, 0.3); }
        .btn-interest:hover { transform: translateY(-3px); box-shadow: 0 15px 25px rgba(233, 196, 106, 0.4); }
        .btn-interest:disabled { background: #e2e8f0; color: #94a3b8; cursor: not-allowed; transform: none; box-shadow: none; }

        .status-badge { display: inline-block; padding: 12px 30px; border-radius: 50px; font-weight: 700; font-size: 15px; margin-top: 10px; }
        .status-waiting { background: #FEF3C7; color: #92400E; border: 1px solid #FDE68A; }
        .status-mitra { background: #E0F2FE; color: #0369A1; border: 1px solid #BAE6FD; }
        .status-success { background: #DCFCE7; color: #166534; border: 1px solid #BBF7D0; }
        .status-failed { background: #FEE2E2; color: #991B1B; border: 1px solid #FECACA; }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 700;
            margin-bottom: 25px;
            font-size: 14px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-back i {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            background: white;
            border-radius: 50%;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            transition: 0.3s;
        }
        .btn-back:hover { color: var(--gold); }
        .btn-back:hover i { transform: translateX(-5px); background: var(--gold); color: white; }

        .req-card {
            border-left: 5px solid var(--gold);
            transition: 0.3s;
        }
        .req-card:hover { transform: translateY(-5px); }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-brand">HCTS</div>
        <ul class="nav-menu">
            <li><a href="dashboard_siswa.php">Home</a></li>
            <li><a href="evaluasi.php">Evaluasi</a></li>
            <li><a href="pembayaranSiswa.php">Keuangan</a></li>
            <li><a href="magang_siswa.php">Magang</a></li>
        </ul>
        <div class="nav-action">
            <a href="../../app/logout.php" class="btn-logout">Logout</a>
        </div>
    </nav>

    <div class="program-container">
        <a href="dashboard_siswa.php" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>

        <div class="hero-banner">
            <div class="hero-icon">✈️</div>
            <div class="hero-text">
                <h1>Program Magang & Kuliah Taiwan</h1>
                <p>Raih Gelar Sarjana (S1) + Pengalaman Kerja Internasional. Program kerjasama eksklusif HCTS dengan universitas ternama dan industri perhotelan di Taiwan.</p>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-card req-card">
                <h2><i class="fas fa-clipboard-check"></i> Persyaratan Umum</h2>
                <ul class="feature-list">
                    <li class="feature-item"><i class="fas fa-check-circle"></i> Usia 18 - 22 tahun.</li>
                    <li class="feature-item"><i class="fas fa-check-circle"></i> Lulusan SMA/SMK Sederajat (Semua Jurusan).</li>
                    <li class="feature-item"><i class="fas fa-check-circle"></i> Sehat Jasmani & Rohani (Lolos MCU).</li>
                    <li class="feature-item"><i class="fas fa-check-circle"></i> Memiliki motivasi belajar Bahasa Mandarin.</li>
                    <li class="feature-item"><i class="fas fa-check-circle"></i> Izin tertulis dari orang tua/wali.</li>
                </ul>
            </div>
            <div class="info-card">
                <h2><i class="fas fa-award"></i> Keuntungan Program</h2>
                <ul class="feature-list">
                    <li class="feature-item"><i class="fas fa-graduation-cap"></i> <strong>Gelar Sarjana (S1) resmi</strong> dari Universitas di Taiwan.</li>
                    <li class="feature-item"><i class="fas fa-wallet"></i> <strong>Penghasilan Bulanan</strong> dari magang kerja legal (Part-time & Full-time).</li>
                    <li class="feature-item"><i class="fas fa-globe-asia"></i> <strong>Pengalaman Internasional</strong> dan fasih berbahasa Mandarin.</li>
                </ul>
            </div>
        </div>

        <div class="timeline-section">
            <h2>Alur Pendaftaran</h2>
            <div class="steps">
                <div class="step <?= $current_step >= 1 ? 'completed' : 'active' ?>">
                    <div class="step-circle"><?= $current_step > 1 ? '<i class="fas fa-check"></i>' : '1' ?></div>
                    <span class="step-label">Nyatakan Minat</span>
                </div>
                <div class="step <?= $current_step >= 2 ? ($current_step > 2 ? 'completed' : 'active') : '' ?>">
                    <div class="step-circle"><?= $current_step > 2 ? '<i class="fas fa-check"></i>' : '2' ?></div>
                    <span class="step-label">Konfirmasi Admin</span>
                </div>
                <div class="step <?= $current_step >= 3 ? ($current_step > 3 ? 'completed' : 'active') : '' ?>">
                    <div class="step-circle"><?= $current_step > 3 ? '<i class="fas fa-check"></i>' : '3' ?></div>
                    <span class="step-label">Pemberkasan</span>
                </div>
                <div class="step <?= $current_step >= 4 ? ($current_step > 4 ? 'completed' : 'active') : '' ?>">
                    <div class="step-circle"><?= $current_step > 4 ? '<i class="fas fa-check"></i>' : '4' ?></div>
                    <span class="step-label">Seleksi & MCU</span>
                </div>
                <div class="step <?= $current_step >= 5 ? 'completed' : ($current_step == 4 ? 'active' : '') ?>">
                    <div class="step-circle">5</div>
                    <span class="step-label">Berangkat</span>
                </div>
            </div>

            <div class="cta-box">
                <?php if (!$taiwan_status): ?>
                    <?php if ($has_certificate): ?>
                        <h3>Tertarik bergabung?</h3>
                        <p>Jika Anda memenuhi syarat dan ingin mengetahui lebih lanjut, cukup klik tombol di bawah ini. Admin kami akan menerima data Anda dan menghubungi Anda untuk konsultasi personal tanpa biaya.</p>
                        <button id="btnDaftar" class="btn-interest">
                            <i class="fas fa-hand-paper"></i> Saya Berminat Mengikuti Program
                        </button>
                    <?php else: ?>
                        <h3>Tahap Belum Selesai</h3>
                        <p>Anda perlu menyelesaikan program pelatihan HCTS dan mendapatkan sertifikat terlebih dahulu sebelum dapat mendaftar program internasional ini.</p>
                        <button disabled class="btn-interest">Belum Memenuhi Syarat</button>
                    <?php endif; ?>
                <?php else: ?>
                    <h3>Status Pendaftaran Anda</h3>
                    <?php if ($taiwan_status == 'berminat'): ?>
                        <div class="status-badge status-waiting">
                            <i class="fas fa-clock"></i> Menunggu Konfirmasi Admin
                        </div>
                        <p style="margin-top: 15px;">Minat Anda telah tercatat. Admin akan segera memproses data Anda untuk tahap selanjutnya.</p>
                    <?php elseif ($taiwan_status == 'diajukan_mitra'): ?>
                        <div class="status-badge status-mitra">
                            <i class="fas fa-exchange-alt"></i> Menunggu Konfirmasi Pihak Taiwan
                        </div>
                        <p style="margin-top: 15px;">Data Anda telah dikirimkan ke mitra kami di Taiwan. Mohon tunggu proses seleksi berkas oleh pihak universitas/industri.</p>
                    <?php elseif ($taiwan_status == 'lolos'): ?>
                        <div class="status-badge status-success">
                            <i class="fas fa-check-circle"></i> Selamat! Anda Lolos Seleksi
                        </div>
                        <p style="margin-top: 15px;">Persiapkan dokumen keberangkatan Anda. Tim HCTS akan menghubungi Anda untuk koordinasi lebih lanjut.</p>
                    <?php elseif ($taiwan_status == 'ditolak'): ?>
                        <div class="status-badge status-failed">
                            <i class="fas fa-times-circle"></i> Maaf, Anda Belum Lolos
                        </div>
                        <p style="margin-top: 15px;">Tetap semangat! Anda dapat mencoba peluang karir internasional lainnya melalui program penempatan HCTS.</p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('btnDaftar')?.addEventListener('click', function() {
            if (confirm('Nyatakan minat Anda untuk mengikuti program Taiwan? Admin akan segera menghubungi Anda.')) {
                fetch('../../backend/daftarTaiwan.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'id_siswa=<?= $id_siswa ?>'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                });
            }
        });
    </script>
</body>
</html>
