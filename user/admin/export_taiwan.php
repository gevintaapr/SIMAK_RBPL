<?php
session_start();
require_once __DIR__ . '/../../config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 5) {
    exit('Akses ditolak.');
}

$ids = $_GET['ids'] ?? '';
if (empty($ids)) exit('Pilih data terlebih dahulu.');

$id_array = array_map('intval', explode(',', $ids));
$id_list = implode(',', $id_array);

$query = mysqli_query($conn, "
    SELECT pt.*, s.nama_lengkap, s.nim_siswa, p.tanggal_lahir, pr.nama_program,
           m.no_sertifikat, e.rata_rata as nilai_akhir
    FROM program_taiwan pt
    JOIN siswa s ON pt.id_siswa = s.id_siswa
    JOIN pendaftaran p ON s.id_pendaftaran = p.id_pendaftaran
    LEFT JOIN program pr ON p.id_program = pr.id_program
    LEFT JOIN magang m ON s.id_siswa = m.id_siswa AND m.no_sertifikat IS NOT NULL
    LEFT JOIN evaluasi e ON s.id_siswa = e.id_siswa
    WHERE pt.id_taiwan IN ($id_list)
");

$data = mysqli_fetch_all($query, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Export Data Pendaftar Taiwan</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 40px; color: #333; }
        .header { text-align: center; border-bottom: 3px double #003B73; padding-bottom: 20px; margin-bottom: 30px; }
        .header h1 { margin: 0; color: #003B73; text-transform: uppercase; }
        .header p { margin: 5px 0 0; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; font-size: 13px; }
        th { background-color: #f2f2f2; font-weight: bold; text-transform: uppercase; }
        .footer-print { margin-top: 50px; text-align: right; font-size: 14px; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="background:#fff3cd; padding:10px; text-align:center; margin-bottom:20px; border:1px solid #ffeeba;">
        Halaman ini siap cetak. Gunakan <b>Ctrl + P</b> jika print dialog tidak muncul otomatis.
    </div>

    <div class="header">
        <h1>HCTS INDONESIA</h1>
        <p>Laporan Data Calon Peserta Program Magang & Kuliah Taiwan</p>
        <p>Tanggal Cetak: <?= date('d F Y') ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>Tgl Lahir</th>
                <th>Jurusan / Program</th>
                <th>No Sertifikat</th>
                <th>Nilai Akhir</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $i => $row): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td style="font-weight:bold;"><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                <td><?= date('d M Y', strtotime($row['tanggal_lahir'])) ?></td>
                <td><?= htmlspecialchars($row['nama_program'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['no_sertifikat'] ?? 'Pending') ?></td>
                <td style="font-weight:bold; text-align:center;"><?= number_format($row['nilai_akhir'], 1) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer-print">
        <p>Dicetak oleh: Admin HCTS</p>
        <p style="margin-top:60px;">( __________________________ )</p>
    </div>
</body>
</html>
