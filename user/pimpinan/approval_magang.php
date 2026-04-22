<?php
require_once __DIR__ . '/../../config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) { // Asumsi Role 2 = Pimpinan
    header("Location: ../../public/login/logPimpinan.php");
    exit;
}

// Ambil antrean magang yang berstatus 'pending'
$query = mysqli_query($conn, "SELECT m.*, s.nama_lengkap, s.nim_siswa 
                              FROM magang m 
                              JOIN siswa s ON m.id_siswa = s.id_siswa 
                              WHERE m.status_magang = 'pending' 
                              ORDER BY m.tanggal_pengajuan ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Persetujuan Magang - Pimpinan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../style/dashboard_pengajar.css"> <!-- Pakai style dashboard yang sudah ada -->
    <style>
        .table-container { background: white; padding: 25px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .btn-approve { background: #10B981; color: white; border: none; padding: 8px 15px; border-radius: 8px; cursor: pointer; }
        .btn-reject { background: #EF4444; color: white; border: none; padding: 8px 15px; border-radius: 8px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar placeholder, bisa di-include nanti -->
        <main class="main-content" style="margin-left: 0; padding: 40px;">
            <h1 style="color: #003B73; margin-bottom: 30px;">Persetujuan Pengajuan Magang</h1>

            <div class="table-container">
                <table class="data-table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #F1F5F9; text-align: left;">
                            <th style="padding: 15px;">Siswa</th>
                            <th style="padding: 15px;">Perusahaan</th>
                            <th style="padding: 15px;">Periode</th>
                            <th style="padding: 15px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($query) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($query)): ?>
                            <tr style="border-bottom: 1px solid #E2E8F0;">
                                <td style="padding: 15px;">
                                    <strong><?= htmlspecialchars($row['nama_lengkap']) ?></strong><br>
                                    <small style="color: #64748B;">NIM: <?= $row['nim_siswa'] ?></small>
                                </td>
                                <td style="padding: 15px;">
                                    <?= htmlspecialchars($row['nama_perusahaan']) ?><br>
                                    <small><?= htmlspecialchars($row['posisi']) ?> - <?= htmlspecialchars($row['lokasi']) ?></small>
                                </td>
                                <td style="padding: 15px; font-size: 13px;">
                                    <?= date('d M Y', strtotime($row['tanggal_mulai'])) ?> - <?= date('d M Y', strtotime($row['tanggal_selesai'])) ?>
                                </td>
                                <td style="padding: 15px;">
                                    <button onclick="updateStatus(<?= $row['id_magang'] ?>, 'disetujui')" class="btn-approve">Setujui</button>
                                    <button onclick="updateStatus(<?= $row['id_magang'] ?>, 'ditolak')" class="btn-reject">Tolak</button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="4" style="text-align: center; padding: 30px; color: #94A3B8;">Tidak ada antrean pengajuan magang.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script>
    function updateStatus(id, status) {
        if(confirm('Apakah Anda yakin ingin memproses pengajuan ini?')) {
            const formData = new FormData();
            formData.append('id_magang', id);
            formData.append('status', status);

            fetch('../../backend/approval_magang_end.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    alert('Status berhasil diperbarui!');
                    location.reload();
                } else {
                    alert('Gagal memperbarui status.');
                }
            });
        }
    }
    </script>
</body>
</html>
