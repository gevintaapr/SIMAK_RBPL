<?php
require_once 'c:/xampp/htdocs/SIMAK_RBPL/SIMAK_RBPL/config/config.php';

$sql = "CREATE TABLE IF NOT EXISTS pengajuan_remedial (
    id_remedial INT AUTO_INCREMENT PRIMARY KEY,
    id_siswa INT NOT NULL,
    id_evaluasi INT NOT NULL,
    mapel_kode VARCHAR(10) NOT NULL,
    alasan TEXT,
    tanggal_pengajuan TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status_remedial ENUM('pending', 'disetujui', 'selesai', 'ditolak') DEFAULT 'pending',
    nilai_lama INT,
    nilai_baru INT,
    jadwal_remedial DATETIME,
    catatan_pengajar TEXT
)";

if (mysqli_query($conn, $sql)) {
    echo "Tabel pengajuan_remedial berhasil disiapkan.\n";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
