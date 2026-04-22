<?php
require_once '../../config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../../public/login/logSiswa.php?error=" . urlencode("Sesi berakhir. Silakan login kembali."));
    exit;
}
$user_id = $_SESSION['user_id'];

// Fetch Student Data (including id_siswa) using mysqli from config.php
$stmt_siswa = mysqli_prepare($conn, "SELECT * FROM siswa WHERE id_user = ? LIMIT 1");
mysqli_stmt_bind_param($stmt_siswa, "i", $user_id);
mysqli_stmt_execute($stmt_siswa);
$result_siswa = mysqli_stmt_get_result($stmt_siswa);
$siswa_data = mysqli_fetch_assoc($result_siswa);

if (!$siswa_data) {
    echo "Data profil siswa tidak ditemukan di database. Sesi ID: " . htmlspecialchars($user_id);
    exit;
}

$id_siswa = $siswa_data['id_siswa'];
$nama_siswa = $siswa_data['nama_lengkap'];

// Fetch payment history
$stmt_pay = mysqli_prepare($conn, "SELECT * FROM pembayaran WHERE id_siswa = ? ORDER BY tanggal_pembayaran DESC");
mysqli_stmt_bind_param($stmt_pay, "i", $id_siswa);
mysqli_stmt_execute($stmt_pay);
$riwayat = mysqli_fetch_all(mysqli_stmt_get_result($stmt_pay), MYSQLI_ASSOC);

// Fetch educational fees
$res_bp = mysqli_query($conn, "SELECT * FROM biaya_pendidikan ORDER BY id_bp ASC");
$biaya_list = mysqli_fetch_all($res_bp, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Siswa - HCTS</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../style/pembayaranSiswa.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-brand">HCTS</div>
        <ul class="nav-menu">
            <li><a href="dashboard_siswa.php" class="active">Home</a></li>
            <li><a href="#">Pages</a></li>
            <li><a href="#">Programs</a></li>
            <li><a href="#">Admission</a></li>
        </ul>
        <div class="nav-action">
            <a href="#" class="nav-bell"><i class="far fa-bell"></i></a>
            <a href="../../app/logout.php" class="btn-logout">Logout</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero-section" style="background-image: url('../../assets/Hero.png');">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div class="breadcrumb">Beranda &gt; Keuangan &amp; Pembayaran</div>
            <h1>Selamat datang, <?= htmlspecialchars($nama_siswa) ?>!</h1>
        </div>
    </header>

    <!-- Main Container -->
    <main class="container">

        <!-- ================= VIEW: RIWAYAT PEMBAYARAN ================= -->
        <div id="riwayat-view">
            <h2 class="main-heading">Keuangan &amp; Pembayaran</h2>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success" id="successAlert">
                    <i class="fas fa-check-circle"></i>
                    Bukti pembayaran berhasil diunggah dan menunggu verifikasi admin.
                </div>
            <?php elseif (isset($_GET['error'])): ?>
                <div class="alert alert-error">
                    <i class="fas fa-times-circle"></i>
                    <?= htmlspecialchars($_GET['error']) ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Riwayat Pembayaran</h3>
                </div>
                <div class="card-body" style="overflow-x: auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jumlah</th>
                                <th>Metode</th>
                                <th>Bukti</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($riwayat)): ?>
                                <tr>
                                    <td colspan="6" style="text-align:center; color:#94a3b8; padding: 2rem;">
                                        <i class="fas fa-inbox" style="font-size:2rem; margin-bottom:.5rem; display:block;"></i>
                                        Belum ada riwayat pembayaran.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($riwayat as $row): ?>
                                <tr>
                                    <td><?= date('d/m/Y', strtotime($row['tanggal_pembayaran'])) ?></td>
                                    <td class="price-tag">Rp <?= number_format($row['nominal'], 0, ',', '.') ?></td>
                                    <td>Bank Transfer</td>
                                    <td>
                                        <?php if ($row['bukti_file']): ?>
                                            <a href="<?= htmlspecialchars($row['bukti_file']) ?>" target="_blank" class="btn btn-outline" style="padding: 4px 8px; font-size: 12px;">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                        <?php else: ?>
                                            <span class="badge pending">Tidak ada file</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $status = $row['status_pembayaran'];
                                        if ($status === 'diterima') {
                                            echo '<span class="badge lunas">Diterima</span>';
                                        } elseif ($status === 'ditolak') {
                                            echo '<span class="badge ditolak">Ditolak</span>';
                                            if ($row['keterangan']) {
                                                echo '<div class="status-note"><i class="fas fa-info-circle"></i> ' . htmlspecialchars($row['keterangan']) . '</div>';
                                            }
                                        } else {
                                            echo '<span class="badge pending">Pending</span>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php if ($status === 'diterima'): ?>
                                            <i class="fas fa-check-circle" style="color: #22c55e;"></i> Verified
                                        <?php elseif ($status === 'ditolak'): ?>
                                            <button class="btn btn-primary" style="padding: 4px 10px; font-size: 11px;" onclick="showKonfirmasi(<?= $row['id_pembayaran'] ?>)">
                                                <i class="fas fa-redo"></i> Re-upload
                                            </button>
                                        <?php else: ?>
                                            <span class="badge-waiting"><i class="fas fa-history"></i> Menunggu Verifikasi</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tombol bayar baru -->
            <div style="text-align: right; margin-top: -1rem; margin-bottom: 1rem;">
                <button class="btn btn-primary" onclick="showKonfirmasi(0)">
                    <i class="fas fa-plus"></i> Unggah Bukti Pembayaran
                </button>
            </div>
        </div>

        <!-- ================= VIEW: KONFIRMASI PEMBAYARAN ================= -->
        <div id="konfirmasi-view">
            <div class="back-header" onclick="showRiwayat()">
                <a href="javascript:void(0)" class="back-btn"><i class="fas fa-arrow-left"></i></a>
                <h2 class="back-title">Konfirmasi Pembayaran</h2>
            </div>
            <p class="subtitle">Silakan transfer dan unggah bukti pembayaran di bawah ini.</p>

            <div class="konfirmasi-grid">

                <!-- Kiri: Info Rekening & Tagihan -->
                <div class="left-column">
                    <!-- Rekening Card -->
                    <div class="card">
                        <h3 class="rek-card-title"><i class="fas fa-building"></i> Rekening Pembayaran</h3>
                        <div class="rek-box">
                            <i class="fas fa-credit-card rek-icon-bg"></i>
                            <div class="bank-name">Bank Mandiri</div>
                            <div class="rek-number">
                                1450012344567
                                <i class="far fa-copy" title="Salin" onclick="copyToClipboard('1450012344567')"></i>
                            </div>
                            <div class="rek-name-label">Atas Nama:</div>
                            <div class="rek-name-value">LEMBAGA PENDIDIKAN HCTS</div>
                        </div>
                        <div class="warning-box">
                            <i class="fas fa-exclamation-circle"></i>
                            <p>Mohon pastikan nominal transfer sesuai dengan tagihan agar proses verifikasi berjalan lebih cepat.</p>
                        </div>
                    </div>

                    <!-- Detail Tagihan Card -->
                    <div class="card">
                        <h3 class="detail-tagihan-title">DETAIL TAGIHAN</h3>
                        <div class="tagihan-row">
                            <span id="biaya_label">Pilih Jenis Pembayaran</span>
                            <span class="value" id="biaya_value">Rp 0</span>
                        </div>
                        <div class="tagihan-row total">
                            <span>Total Transfer</span>
                            <span class="value" id="total_value">Rp 0</span>
                        </div>
                    </div>
                </div>

                <!-- Kanan: Upload Bukti -->
                <div class="right-column">
                    <div class="card" style="height: 100%;">
                        <h3 class="upload-card-title"><i class="fas fa-upload"></i> Unggah Bukti Transfer</h3>
                        <p class="upload-subtitle">Pilih Foto atau File Bukti (JPG, PNG, PDF)</p>

                        <!-- Form upload linked to backend -->
                        <form id="formUpload" method="POST" action="../../backend/pembayaran_end.php" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="upload">
                            
                            <div class="form-group">
                                <label>Jenis Pembayaran</label>
                                <select name="id_bp" id="id_bp" class="form-control" style="width:100%; padding:0.8rem; border:1px solid #e2e8f0; border-radius:8px;" onchange="updateNominal(this)" required>
                                    <option value="" data-nominal="0">-- Pilih Jenis Pembayaran --</option>
                                    <?php foreach ($biaya_list as $bp): ?>
                                        <option value="<?= $bp['id_bp'] ?>" data-nominal="<?= $bp['nominal'] ?>" data-nama="<?= htmlspecialchars($bp['nama_bp']) ?>">
                                            <?= htmlspecialchars($bp['nama_bp']) ?> - Rp <?= number_format($bp['nominal'], 0, ',', '.') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <input type="hidden" name="deskripsi" id="deskripsi_hidden" value="">
                            <input type="hidden" name="nominal" id="nominal_hidden" value="">

                            <div class="upload-area" onclick="document.getElementById('fileUpload').click()">
                                <i class="fas fa-arrow-up upload-icon"></i>
                                <div class="upload-text-main" id="uploadLabel">Klik atau tarik file ke sini</div>
                                <div class="upload-text-sub">Maksimal ukuran file 2MB (JPG, PNG, PDF)</div>
                                <input type="file" name="bukti_file" id="fileUpload" style="display: none;" accept=".jpg,.jpeg,.png,.pdf" onchange="onFileSelected(this)">
                            </div>

                            <div class="form-group" style="margin-top:1rem;">
                                <label>Catatan (Opsional)</label>
                                <textarea name="catatan" placeholder="Contoh: Pembayaran Laporan OJT atas nama Budi"></textarea>
                            </div>

                            <button type="submit" id="btnSubmit" class="btn-block" disabled>Kirim Bukti Pembayaran</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </main>



    <script>
        function showKonfirmasi(id) {
            document.getElementById('riwayat-view').style.display = 'none';
            document.getElementById('konfirmasi-view').style.display = 'block';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Logic for deep linking from dashboard announcement
        window.addEventListener('load', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('view') === 'upload') {
                showKonfirmasi(0);
                // Pre-select DP if possible
                const selectBP = document.getElementById('id_bp');
                for (let i = 0; i < selectBP.options.length; i++) {
                    if (selectBP.options[i].value == "1") {
                        selectBP.selectedIndex = i;
                        updateNominal(selectBP);
                        break;
                    }
                }
            }
        });

        function updateNominal(select) {
            const option = select.options[select.selectedIndex];
            const nominal = parseInt(option.getAttribute('data-nominal')) || 0;
            const nama = option.getAttribute('data-nama') || 'Pilih Jenis Pembayaran';
            
            document.getElementById('nominal_hidden').value = nominal;
            document.getElementById('deskripsi_hidden').value = nama;
            
            // Update UI display
            document.getElementById('biaya_label').textContent = nama;
            document.getElementById('biaya_value').textContent = 'Rp ' + nominal.toLocaleString('id-ID');
            document.getElementById('total_value').textContent = 'Rp ' + nominal.toLocaleString('id-ID');
        }

        function showRiwayat() {
            document.getElementById('konfirmasi-view').style.display = 'none';
            document.getElementById('riwayat-view').style.display = 'block';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function onFileSelected(input) {
            const btn = document.getElementById('btnSubmit');
            const label = document.getElementById('uploadLabel');
            if (input.files.length > 0) {
                label.textContent = input.files[0].name;
                btn.removeAttribute('disabled');
                btn.classList.add('active');
            } else {
                label.textContent = 'Klik atau tarik file ke sini';
                btn.setAttribute('disabled', 'disabled');
                btn.classList.remove('active');
            }
        }

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Nomor rekening ' + text + ' telah disalin!');
            });
        }

        // Auto-hide alert after 3 seconds
        window.addEventListener('load', function() {
            const alert = document.getElementById('successAlert');
            if (alert) {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.style.display = 'none', 500);
                }, 3000);
            }
        });
    </script>
</body>
</html>
