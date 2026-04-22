<?php
require_once __DIR__ . '/../../config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 5) {
    header("Location: ../../public/login/logAdmin.php?role=5&error=" . urlencode("Sesi berakhir atau akses ditolak."));
    exit;
}

$id = $_GET['id'] ?? '';
$query = mysqli_query($conn, "SELECT p.*, u.create_at, pr.nama_program 
                             FROM pendaftaran p 
                             LEFT JOIN user u ON p.id_user = u.id_user 
                             LEFT JOIN program pr ON p.id_program = pr.id_program
                             WHERE p.id_pendaftaran = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: pendaftaran_admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pendaftar - HCTS Admin Center</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;800&family=Poppins:wght@400;500;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../style/detail_pendaftar_A.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../style/popup_admin.css?v=<?= time() ?>">
    <style>
        .workflow-card {
            background: #fff;
            border-radius: 20px;
            padding: 30px;
            margin-top: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            border: 1px solid #edf2f7;
        }
        .wf-title {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            color: #003B73;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .wf-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .input-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .input-group label {
            font-size: 14px;
            font-weight: 600;
            color: #4A5568;
        }
        .input-group input {
            padding: 12px;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            font-family: inherit;
        }
        .btn-action {
            padding: 14px 24px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 16px;
        }
        .btn-blue {
            background: #003B73;
            color: #fff;
        }
        .btn-gold {
            background: #EBC372;
            color: #003B73;
        }
        .btn-green {
            background: #10B981;
            color: #fff;
        }
        .btn-action:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
        .status-info {
            padding: 15px;
            background: #F8FAFC;
            border-radius: 10px;
            border-left: 4px solid #003B73;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        .status-info p {
            margin: 0;
            font-size: 14px;
        }
        .status-info strong {
            color: #003B73;
        }
    </style>
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
            <a href="pendaftaran_admin.php" class="sidebar-link active">
                <i class="fa-solid fa-file-signature"></i>
                <span>Pendaftaran</span>
            </a>
            <a href="#" class="sidebar-link">
                <i class="fa-solid fa-briefcase"></i>
                <span>Magang (OJT)</span>
            </a>
            <a href="akademik_admin.php" class="sidebar-link">
                <i class="fa-solid fa-book"></i>
                <span>Akademik</span>
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

            <!-- Hero / Banner -->
            <section class="dashboard-banner">
                <div class="banner-content">
                    <div class="banner-text-wrapper">
                        <p class="breadcrumb">Pendaftaran &gt; Detail Pendaftar</p>
                        <h1 class="banner-title">Detail Pendaftar: <?= htmlspecialchars($data['nama_cs']) ?></h1>
                    </div>
                </div>
            </section>

            <!-- Detail Section -->
            <section class="detail-section">
                <!-- Card 1: Informasi Pribadi -->
                <div class="detail-card">
                    <h2 class="card-title">Informasi Pribadi</h2>
                    <hr class="card-divider">
                    <div class="card-body">
                        <div class="info-content">
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="info-label">Nama Lengkap</span>
                                    <span class="info-value"><?= htmlspecialchars($data['nama_cs']) ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Email</span>
                                    <span class="info-value"><?= htmlspecialchars($data['email']) ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Nomor Whatsapp</span>
                                    <span class="info-value"><?= htmlspecialchars($data['no_wa']) ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Tanggal Lahir</span>
                                    <span class="info-value"><?= htmlspecialchars($data['tanggal_lahir']) ?></span>
                                </div>

                                <div class="info-item">
                                    <span class="info-label">Posisi-Program Pilihan</span>
                                    <span class="info-value"><?= htmlspecialchars($data['nama_program'] ?? '-') ?></span>
                                </div>
                                <div class="info-item full-width">
                                    <span class="info-label">Alamat Lengkap</span>
                                    <span class="info-value"><?= htmlspecialchars($data['alamat']) ?></span>
                                </div>
                                <div class="info-item full-width mt-2">
                                    <span class="info-label">Status Alur Pendaftaran:</span>
                                    <?php 
                                    $status_label = "Menunggu Verifikasi";
                                    $status_color = "#2563EB";
                                    $status_bg = "#DBEAFE";
                                    
                                    if ($data['status_approval'] === 'disetujui' || $data['status_approval'] === '1') {
                                        $status_label = "Lulus Seleksi";
                                        $status_color = "#059669";
                                        $status_bg = "#D1FAE5";
                                    } elseif ($data['status_approval'] === 'ditolak') {
                                        $status_label = "Tidak Lulus";
                                        $status_color = "#DC2626";
                                        $status_bg = "#FEE2E2";
                                    } elseif ($data['status_approval'] === 'menunggu_pimpinan') {
                                        $status_label = "Menunggu Approval Pimpinan";
                                        $status_color = "#D97706";
                                        $status_bg = "#FEF3C7";
                                    } elseif ($data['jadwal_wawancara'] !== NULL) {
                                        $status_label = "Jadwal Wawancara Ditetapkan";
                                        $status_color = "#7C3AED";
                                        $status_bg = "#EDE9FE";
                                    } elseif ($data['status_berkas'] === 'valid') {
                                        $status_label = "Berkas Terverifikasi";
                                        $status_color = "#059669";
                                        $status_bg = "#D1FAE5";
                                    }
                                    ?>
                                    <span class="badge" style="background: <?= $status_bg ?>; color: <?= $status_color ?>; display: inline-block; padding: 8px 16px; border-radius: 30px; font-size: 14px; font-weight: 600;">
                                        <?= $status_label ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="photo-content">
                            <img src="../../<?= htmlspecialchars($data['foto_siswa']) ?>" alt="Foto Calon Siswa" class="student-photo" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($data['nama_cs']) ?>&background=0D8ABC&color=fff&size=200'">
                            <p class="photo-title">Foto Calon Siswa</p>
                            <p class="photo-date">Tanggal Upload: <?= isset($data['create_at']) ? date('d/m/Y', strtotime($data['create_at'])) : '-' ?></p>
                        </div>
                    </div>
                </div>

                <!-- Workflow Action Card -->
                <div class="workflow-card">
                    <h3 class="wf-title"><i class="fa-solid fa-list-check"></i> Kontrol Tahapan Pendaftaran</h3>
                    
                    <?php if ($data['status_berkas'] !== 'valid'): ?>
                        <!-- TAHAP 1: Verifikasi -->
                        <div class="wf-form">
                            <p>Tinjau dokumen calon siswa di bawah ini. Jika sudah benar, lakukan verifikasi berkas.</p>
                            <button class="btn-action btn-gold" onclick="prosesWorkflow('verifikasi_berkas')">
                                <i class="fa-solid fa-check-double"></i> Verifikasi Berkas Sekarang
                            </button>
                        </div>

                    <?php elseif ($data['jadwal_wawancara'] === NULL): ?>
                        <!-- TAHAP 2: Set Jadwal -->
                        <div class="wf-form">
                            <div class="status-info">
                                <p><i class="fa-solid fa-circle-check"></i> <strong>Berkas Telah Terverifikasi.</strong> Tahap selanjutnya adalah menetapkan jadwal wawancara.</p>
                            </div>
                            <div class="input-group">
                                <label for="jadwalInput">Pilih Jadwal Wawancara:</label>
                                <input type="datetime-local" id="jadwalInput" min="<?= date('Y-m-d\TH:i') ?>">
                            </div>
                            <button class="btn-action btn-blue" onclick="prosesWorkflow('set_jadwal')">
                                <i class="fa-solid fa-calendar-plus"></i> Tetapkan Jadwal Wawancara
                            </button>
                        </div>

                    <?php elseif ($data['status_approval'] === 'pending' || $data['status_approval'] === '0'): ?>
                        <!-- TAHAP 3: Selesai Wawancara -->
                        <div class="wf-form">
                            <div class="status-info">
                                <p><i class="fa-solid fa-calendar-check"></i> <strong>Jadwal Wawancara Ditetapkan:</strong></p>
                                <p><strong><?= date('d F Y, H:i', strtotime($data['jadwal_wawancara'])) ?> WIB</strong></p>
                            </div>
                            <p>Jika wawancara telah dilaksanakan, silakan klik tombol di bawah untuk meneruskan ke Pimpinan.</p>
                            <button class="btn-action btn-green" onclick="prosesWorkflow('selesai_wawancara')">
                                <i class="fa-solid fa-flag-checkered"></i> Wawancara Selesai & Kirim Approval
                            </button>
                        </div>

                    <?php elseif ($data['status_approval'] === 'menunggu_pimpinan'): ?>
                        <!-- TAHAP 4: Menunggu Pimpinan -->
                        <div class="status-info" style="border-left-color: #D97706;">
                            <p><i class="fa-solid fa-hourglass-half"></i> <strong>Sedang Menunggu Approval Pimpinan.</strong></p>
                            <p>Data pendaftaran ini telah muncul di dashboard pimpinan untuk diputuskan (Lulus/Tidak Lulus).</p>
                        </div>

                    <?php else: ?>
                        <!-- FINISHED -->
                        <div class="status-info" style="border-left-color: <?= $data['status_approval'] === 'disetujui' ? '#10B981' : '#DC2626' ?>;">
                            <p><i class="fa-solid fa-circle-info"></i> <strong>Proses Seleksi Selesai.</strong></p>
                            <p>Hasil Akhir: <strong><?= $data['status_approval'] === 'disetujui' ? 'LULUS SELEKSI' : 'TIDAK LULUS' ?></strong></p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Card 2: Dokumen Pendaftaran -->
                <div class="detail-card">
                    <h2 class="card-title">Dokumen Pendaftaran</h2>
                    <hr class="card-divider">
                    <div class="doc-grid">
                        <div class="doc-card">
                            <div class="doc-icon"><i class="fa-regular fa-file-lines"></i></div>
                            <div class="doc-info">
                                <h3 class="doc-name">Surat Pernyataan</h3>
                                <p class="doc-filename"><?= basename($data['surat_pernyataan'] ?? 'file.pdf') ?></p>
                                <div class="doc-actions">
                                    <a href="../../<?= htmlspecialchars($data['surat_pernyataan']) ?>" target="_blank" class="btn-doc btn-view"><i class="fa-regular fa-eye"></i> Lihat</a>
                                </div>
                            </div>
                        </div>
                        <div class="doc-card">
                            <div class="doc-icon"><i class="fa-regular fa-id-card"></i></div>
                            <div class="doc-info">
                                <h3 class="doc-name">KTP</h3>
                                <p class="doc-filename"><?= basename($data['ktp'] ?? 'file.pdf') ?></p>
                                <div class="doc-actions">
                                    <a href="../../<?= htmlspecialchars($data['ktp']) ?>" target="_blank" class="btn-doc btn-view"><i class="fa-regular fa-eye"></i> Lihat</a>
                                </div>
                            </div>
                        </div>
                        <div class="doc-card">
                            <div class="doc-icon"><i class="fa-solid fa-graduation-cap"></i></div>
                            <div class="doc-info">
                                <h3 class="doc-name">Ijazah</h3>
                                <p class="doc-filename"><?= basename($data['ijazah'] ?? 'file.pdf') ?></p>
                                <div class="doc-actions">
                                    <a href="../../<?= htmlspecialchars($data['ijazah']) ?>" target="_blank" class="btn-doc btn-view"><i class="fa-regular fa-eye"></i> Lihat</a>
                                </div>
                            </div>
                        </div>
                        <div class="doc-card">
                            <div class="doc-icon"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                            <div class="doc-info">
                                <h3 class="doc-name">Bukti Pembayaran</h3>
                                <p class="doc-filename"><?= basename($data['bukti_pendaftaran'] ?? 'file.pdf') ?></p>
                                <div class="doc-actions">
                                    <a href="../../<?= htmlspecialchars($data['bukti_pendaftaran']) ?>" target="_blank" class="btn-doc btn-view"><i class="fa-regular fa-eye"></i> Lihat</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Popup Status -->
    <div id="popupWorkflow" class="popup-overlay" style="display: none;">
        <div class="popup-admin-box">
            <button class="popup-admin-close" onclick="closePopup()"><i class="fa-solid fa-xmark"></i></button>
            <h2 class="popup-admin-title" id="popupTitle">Berhasil!</h2>
            <hr class="popup-admin-divider">
            <div class="verif-list">
                <div class="verif-item">
                    <div class="verif-icon"><i class="fa-solid fa-check"></i></div>
                    <span id="popupMessage">Tahapan pendaftaran telah diperbarui.</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        function prosesWorkflow(action) {
            const formData = new FormData();
            formData.append('action', action);
            formData.append('id_pendaftaran', "<?= $data['id_pendaftaran'] ?>");
            
            if (action === 'set_jadwal') {
                const jadwal = document.getElementById('jadwalInput').value;
                if (!jadwal) {
                    alert('Harap pilih jadwal wawancara terlebih dahulu.');
                    return;
                }
                formData.append('jadwal_wawancara', jadwal);
            }

            fetch('../../backend/pendaftaranAdmin_end.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById('popupTitle').innerText = "Berhasil!";
                    document.getElementById('popupMessage').innerText = data.message;
                    document.getElementById('popupWorkflow').style.display = 'flex';
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert('Gagal menghubungkan ke server.');
            });
        }

        function closePopup() {
            location.reload();
        }

        const sidebar = document.getElementById('sidebar');
        const mainWrapper = document.getElementById('mainWrapper');
        const toggleBtn = document.getElementById('sidebarToggle');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            mainWrapper.classList.toggle('sidebar-collapsed');
        });
    </script>
</body>
</html>