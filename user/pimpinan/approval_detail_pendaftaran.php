<?php
session_start();
require_once '../../config/config.php';

$id = $_GET['id'] ?? '';
$query = mysqli_query($conn, "SELECT * FROM pendaftaran WHERE id_pendaftaran = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: approval.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pendaftaran: <?= htmlspecialchars($data['nama_cs']) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../style/approval.css?v=<?= time() ?>">
    <style>
        .action-section {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 40px;
            padding: 30px;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
        .btn-reject {
            background: #ef4444;
            color: #fff;
            padding: 15px 40px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-approve {
            background: #003B73;
            color: #fff;
            padding: 15px 40px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-reject:hover { opacity: 0.9; transform: translateY(-2px); }
        .btn-approve:hover { opacity: 0.9; transform: translateY(-2px); }
    </style>
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
            <a href="approval.php" class="sidebar-link active">
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
                    <div class="banner-text-wrapper">
                        <p class="breadcrumb"><a href="approval.php">Approval: Pendaftaran</a> &gt; Detail</p>
                        <h1 class="banner-title">Detail: <?= htmlspecialchars($data['nama_cs']) ?></h1>
                    </div>
                </div>
            </section>

            <!-- Detail Section -->
            <section class="detail-section">
                <!-- Card 1: Informasi Pribadi -->
                <div class="detail-card">
                    <h2 class="card-title-detail">Informasi Pribadi</h2>
                    <hr class="card-divider">
                    <div class="card-body-detail">
                        <div class="info-content">
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="info-label">Nama Lengkap</span>
                                    <span class="info-value"><?= htmlspecialchars($data['nama_cs']) ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Email</span>
                                    <span class="info-value"><?= htmlspecialchars($data['email'] ?? '-') ?></span>
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
                                    <span class="info-value"><?= htmlspecialchars($data['program']) ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Tanggal Wawancara</span>
                                    <span class="info-value"><?= date('d F Y, H:i', strtotime($data['jadwal_wawancara'])) ?> WIB</span>
                                </div>
                                <div class="info-item full-width">
                                    <span class="info-label">Alamat Lengkap</span>
                                    <span class="info-value"><?= htmlspecialchars($data['alamat']) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="photo-content">
                            <img src="../../<?= htmlspecialchars($data['foto_siswa']) ?>" alt="Foto Calon Siswa" class="student-photo" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($data['nama_cs']) ?>&background=0D8ABC&color=fff&size=200'">
                            <p class="photo-title">Foto Calon Siswa</p>
                        </div>
                    </div>
                </div>

                <!-- Action Section for Pimpinan -->
                <?php if ($data['status_approval'] === 'menunggu_pimpinan'): ?>
                <div class="action-section">
                    <button class="btn-reject" onclick="prosesApproval('ditolak')">Tolak Pendaftaran</button>
                    <button class="btn-approve" onclick="prosesApproval('disetujui')">Setujui Pendaftaran</button>
                </div>
                <?php else: ?>
                <div class="status-info" style="padding: 20px; text-align: center; background: #f8fafc; border-radius: 12px; margin-top: 20px;">
                    <p>Status pendaftaran ini adalah: <strong><?= strtoupper($data['status_approval']) ?></strong></p>
                </div>
                <?php endif; ?>

                <!-- Card 2: Dokumen Pendaftaran -->
                <div class="detail-card">
                    <h2 class="card-title-detail">Dokumen Pendaftaran</h2>
                    <hr class="card-divider">
                    <div class="doc-grid">
                        <div class="doc-card">
                            <div class="doc-icon"><i class="fa-regular fa-file-lines"></i></div>
                            <div class="doc-info">
                                <h3 class="doc-name">Surat Pernyataan</h3>
                                <div class="doc-actions">
                                    <a href="../../<?= htmlspecialchars($data['surat_pernyataan']) ?>" target="_blank" class="btn-doc btn-view">Lihat</a>
                                </div>
                            </div>
                        </div>
                        <div class="doc-card">
                            <div class="doc-icon"><i class="fa-regular fa-id-card"></i></div>
                            <div class="doc-info">
                                <h3 class="doc-name">KTP</h3>
                                <div class="doc-actions">
                                    <a href="../../<?= htmlspecialchars($data['ktp']) ?>" target="_blank" class="btn-doc btn-view">Lihat</a>
                                </div>
                            </div>
                        </div>
                        <div class="doc-card">
                            <div class="doc-icon"><i class="fa-solid fa-graduation-cap"></i></div>
                            <div class="doc-info">
                                <h3 class="doc-name">Ijazah</h3>
                                <div class="doc-actions">
                                    <a href="../../<?= htmlspecialchars($data['ijazah']) ?>" target="_blank" class="btn-doc btn-view">Lihat</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const mainWrapper = document.getElementById('mainWrapper');
        const toggleBtn = document.getElementById('sidebarToggle');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            mainWrapper.classList.toggle('sidebar-collapsed');
        });

        function prosesApproval(status) {
            const confirmed = confirm('Anda yakin ingin ' + (status === 'disetujui' ? 'menyetujui' : 'menolak') + ' pendaftaran ini?');
            if (!confirmed) return;

            const formData = new FormData();
            formData.append('action', 'approve_pendaftaran');
            formData.append('id_pendaftaran', '<?= $data['id_pendaftaran'] ?>');
            formData.append('status', status);

            fetch('../../backend/approvalPendaftaran.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if (data.status === 'success') {
                    window.location.href = 'approval.php';
                }
            })
            .catch(err => {
                console.error(err);
                alert('Terjadi kesalahan sistem.');
            });
        }
    </script>
</body>
</html>
