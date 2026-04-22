<?php
session_start();
require_once '../../config/database.php';

// Fallback dummy session for dev/testing
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
}
$admin_id = $_SESSION['user_id'];

$db = new Database();
$conn = $db->getConnection();

// Fetch all payments with student info
$stmt = $conn->query("SELECT p.*, s.nama_lengkap as nama_siswa, s.email_belajar as email
    FROM pembayaran p
    LEFT JOIN `siswa` s ON p.id_siswa = s.id_siswa
    ORDER BY p.tanggal_pembayaran DESC");
$payments = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akademik - Verifikasi Pembayaran - HCTS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;800&family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../style/verifPembayaran_akademik.css?v=<?= time() ?>">
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
            <a href="pendaftaran_admin.php" class="sidebar-link">
                <i class="fa-solid fa-file-signature"></i>
                <span>Pendaftaran</span>
            </a>
            <a href="magang_admin.php" class="sidebar-link">
                <i class="fa-solid fa-briefcase"></i>
                <span>Magang (OJT)</span>
            </a>
            <a href="akademik_admin.php" class="sidebar-link active">
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
            <a href="../../app/logout.php" class="sidebar-link sidebar-logout">
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

            <!-- Hero / Dashboard Banner -->
            <section class="dashboard-banner">
                <div class="banner-content">
                    <h1 class="banner-title">Akademik</h1>
                </div>
            </section>

            <!-- Tabs Navigation -->
            <div class="tabs-nav-container">
                <button class="tab-btn" onclick="window.location.href='akademik_admin.php'">
                    <i class="fa-solid fa-users"></i>
                    Daftar Siswa
                </button>
                <button class="tab-btn active" onclick="window.location.href='verifPembayaran_akademik.php'">
                    <i class="fa-solid fa-wallet"></i>
                    Verifikasi Pembayaran
                </button>
                <button class="tab-btn" onclick="window.location.href='aturJadwal_akademik.php'">
                    <i class="fa-solid fa-calendar-days"></i>
                    Atur Jadwal
                </button>
                <button class="tab-btn" onclick="window.location.href='verifEval_akademik.php'">
                    <i class="fa-solid fa-clipboard-check"></i>
                    Verifikasi Evaluasi
                </button>
            </div>

            <!-- Pendaftaran List Section / Verif Pembayaran-->
            <section class="pendaftaran-list-section">
                <div class="pendaftaran-list-card">
                    <div class="table-header">
                        <h2 class="table-title">Verifikasi Pembayaran Siswa</h2>
                    </div>
                    <div class="filter-controls">
                        <select class="filter-select">
                            <option>Program</option>
                            <option>Hotel Management</option>
                            <option>Cruise Ship Operations</option>
                            <option>Culinary Arts</option>
                        </select>
                        <select class="filter-select">
                            <option>Status</option>
                            <option>Valid</option>
                            <option>Pending</option>
                        </select>
                        <div class="search-box">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" placeholder="Cari Nama Siswa">
                            <i class="fa-solid fa-xmark" style="margin-left: 10px;"></i>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>TGL BAYAR</th>
                                    <th>NAMA</th>
                                    <th>PROGRAM</th>
                                    <th>STATUS</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($payments)): ?>
                                    <tr>
                                        <td colspan="5" style="text-align:center; padding:2rem; color:#94a3b8;">
                                            <i class="fa-solid fa-inbox"
                                                style="font-size:2rem; margin-bottom:.5rem; display:block;"></i>
                                            Belum ada data pembayaran.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($payments as $pay): ?>
                                        <?php
                                        $badgeClass = 'badge-pending-blue';
                                        $badgeLabel = 'Pending';
                                        if ($pay['status_pembayaran'] === 'diterima') {
                                            $badgeClass = 'badge-valid';
                                            $badgeLabel = 'Diterima';
                                        } elseif ($pay['status_pembayaran'] === 'ditolak') {
                                            $badgeClass = 'badge-rejected';
                                            $badgeLabel = 'Ditolak';
                                        }
                                        ?>
                                        <tr>
                                            <td><?= date('d/m/Y', strtotime($pay['tanggal_pembayaran'])) ?></td>
                                            <td><?= htmlspecialchars($pay['nama_siswa'] ?? 'Siswa #' . $pay['id_siswa']) ?></td>
                                            <td><?= htmlspecialchars($pay['deskripsi'] ?? '-') ?></td>
                                            <td><span class="badge <?= $badgeClass ?>"><?= $badgeLabel ?></span></td>
                                            <td>
                                                <button class="btn-detail" onclick="openModal(
                                            <?= $pay['id_pembayaran'] ?>,
                                            '<?= addslashes($pay['nama_siswa'] ?? 'Siswa #' . $pay['id_siswa']) ?>',
                                            '<?= addslashes($pay['email'] ?? '-') ?>',
                                            '<?= addslashes($pay['deskripsi'] ?? '-') ?>',
                                            '<?= number_format($pay['nominal'] ?? 0, 0, ',', '.') ?>',
                                            '<?= date('d M Y', strtotime($pay['tanggal_pembayaran'])) ?>',
                                            '<?= $pay['status_pembayaran'] ?>',
                                            '<?= addslashes($pay['keterangan'] ?? '') ?>',
                                            '<?= addslashes($pay['bukti_file'] ?? '') ?>'
                                        )">Detail</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

        </main>



    </div>

    <!-- MODAL DETAIL PEMBAYARAN -->
    <div class="modal-overlay" id="detailModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Detail Pembayaran</h2>
                <button class="modal-close" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-divider"></div>

            <div class="modal-body">
                <div class="modal-left">
                    <div class="detail-card">
                        <h3 class="detail-card-title">Identitas Siswa</h3>
                        <div class="detail-row"><span class="detail-label">Nama Lengkap:</span><span class="detail-val"
                                id="d-nama">-</span></div>
                        <div class="detail-row"><span class="detail-label">Email:</span><span class="detail-val"
                                id="d-email">-</span></div>
                        <div class="detail-row"><span class="detail-label">Deskripsi:</span><span class="detail-val"
                                id="d-deskripsi">-</span></div>
                    </div>

                    <div class="detail-card">
                        <h3 class="detail-card-title">Detail Transaksi</h3>
                        <div class="detail-row"><span class="detail-label">Nominal Bayar:</span><span class="detail-val"
                                id="d-nominal">-</span></div>
                        <div class="detail-row"><span class="detail-label">Tanggal Upload:</span><span
                                class="detail-val" id="d-tanggal">-</span></div>
                        <div class="detail-row"><span class="detail-label">Status:</span><span
                                id="d-status-badge"></span></div>
                        <div class="detail-row" id="d-keterangan-row" style="display:none">
                            <span class="detail-label">Keterangan:</span>
                            <span class="detail-val" id="d-keterangan" style="color:#991b1b;">-</span>
                        </div>
                    </div>

                    <!-- Form keterangan penolakan -->
                    <div class="detail-card" id="formKeteranganWrapper" style="display:none;">
                        <h3 class="detail-card-title">Alasan Penolakan</h3>
                        <textarea id="inputKeterangan"
                            style="width:100%; border:1px solid #e2e8f0; border-radius:8px; padding:.75rem; min-height:80px; resize:vertical;"
                            placeholder="Tuliskan alasan penolakan..."></textarea>
                    </div>
                </div>

                <div class="modal-right">
                    <div class="detail-card bukti-card">
                        <h3 class="detail-card-title w-100">Bukti Pembayaran</h3>
                        <div class="bukti-content" id="buktiContent">
                            <div class="verif-icon"><i class="fa-solid fa-file-image"></i></div>
                            <p class="verif-text" id="d-bukti-text">Memuat...</p>
                            <a id="d-bukti-link" href="#" target="_blank"
                                style="display:none; margin-top:.5rem; color:#3b82f6; font-size:.9rem;">
                                <i class="fas fa-external-link-alt"></i> Lihat File
                            </a>
                        </div>
                        <span class="scroll-msg">*Klik 'Lihat File' untuk membuka bukti pembayaran</span>
                    </div>
                </div>
            </div>

            <div class="modal-footer" style="display:flex; justify-content: center; gap: 1rem; padding: 1.5rem 2rem; background: #f8fafc; border-top: 1px solid #e2e8f0;">
                <input type="hidden" id="modalPaymentId" value="">
                <button class="btn-tolak" id="btnTolak" onclick="submitVerif('ditolak')"
                    style="flex: 1; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.2s; background:#fee2e2; color:#991b1b; border:1px solid #fecaca; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                    <i class="fas fa-times"></i> Tolak Pembayaran
                </button>
                <button class="btn-terima" id="btnTerima" onclick="submitVerif('diterima')"
                    style="flex: 1; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.2s; background:#10b981; color:white; border:none; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                    <i class="fas fa-check"></i> Terima Pembayaran
                </button>
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

        // Modal Logic
        const modal = document.getElementById('detailModal');

        function openModal(id, nama, email, deskripsi, nominal, tanggal, status, keterangan, buktiFile) {
            // Set hidden id
            document.getElementById('modalPaymentId').value = id;

            // Fill identitas
            document.getElementById('d-nama').textContent = nama;
            document.getElementById('d-email').textContent = email;
            document.getElementById('d-deskripsi').textContent = deskripsi;
            document.getElementById('d-nominal').textContent = 'Rp ' + nominal;
            document.getElementById('d-tanggal').textContent = tanggal;

            // Status badge
            const badgeEl = document.getElementById('d-status-badge');
            if (status === 'diterima') {
                badgeEl.innerHTML = '<span class="badge badge-valid">Diterima</span>';
            } else if (status === 'ditolak') {
                badgeEl.innerHTML = '<span class="badge badge-rejected">Ditolak</span>';
            } else {
                badgeEl.innerHTML = '<span class="badge badge-pending-blue">Pending</span>';
            }

            // Keterangan penolakan
            const keteranganRow = document.getElementById('d-keterangan-row');
            if (keterangan && keterangan.trim() !== '') {
                document.getElementById('d-keterangan').textContent = keterangan;
                keteranganRow.style.display = 'flex';
            } else {
                keteranganRow.style.display = 'none';
            }

            // Toggle verif buttons: only show if status is pending
            const isPending = status === 'pending';
            document.getElementById('btnTerima').style.display = isPending ? 'inline-flex' : 'none';
            document.getElementById('btnTolak').style.display = isPending ? 'inline-flex' : 'none';
            document.getElementById('formKeteranganWrapper').style.display = 'none';

            // Bukti file
            const buktiLink = document.getElementById('d-bukti-link');
            const buktiText = document.getElementById('d-bukti-text');
            if (buktiFile && buktiFile.trim() !== '') {
                buktiLink.href = '/SIMAKHCTS' + buktiFile;
                buktiLink.style.display = 'block';
                buktiText.textContent = 'File bukti tersedia';
            } else {
                buktiLink.style.display = 'none';
                buktiText.textContent = 'Tidak ada file bukti';
            }

            modal.classList.add('show');
        }

        function closeModal() {
            modal.classList.remove('show');
        }

        function submitVerif(action) {
            const id = document.getElementById('modalPaymentId').value;
            const keterangan = document.getElementById('inputKeterangan').value;

            if (action === 'ditolak') {
                // Show textarea if not shown yet
                const wrapper = document.getElementById('formKeteranganWrapper');
                if (wrapper.style.display === 'none') {
                    wrapper.style.display = 'block';
                    return; // Wait for user to fill and click again
                }
            }

            const formData = new FormData();
            formData.append('action', 'verifikasi');
            formData.append('id', id);
            formData.append('status_verif', action);
            formData.append('keterangan', keterangan);

            fetch('../../backend/pembayaran_end.php', {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('Berhasil: ' + data.message);
                        closeModal();
                        location.reload();
                    } else {
                        alert('Gagal: ' + data.message);
                    }
                })
                .catch(err => {
                    alert('Terjadi kesalahan. Coba lagi.');
                    console.error(err);
                });
        }

        // Close on outside click
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });
    </script>
</body>

</html>