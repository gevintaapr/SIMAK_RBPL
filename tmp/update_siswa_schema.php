<?php
require_once 'c:/xampp/htdocs/SIMAK_RBPL/SIMAK_RBPL/config/config.php';

// 1. Tambah kolom status_pembayaran ke tabel siswa
$sql = "ALTER TABLE siswa ADD COLUMN status_pembayaran VARCHAR(50) DEFAULT 'belum_bayar' AFTER password";
if (mysqli_query($conn, $sql)) {
    echo "Kolom status_pembayaran berhasil ditambahkan ke tabel siswa.\n";
} else {
    echo "Gagal/Sudah ada: " . mysqli_error($conn) . "\n";
}

// 2. Cek data biaya pendidikan untuk mencari nominal DP
echo "\nData Biaya Pendidikan:\n";
$query = mysqli_query($conn, "SELECT * FROM biaya_pendidikan");
while($row = mysqli_fetch_assoc($query)) {
    print_r($row);
}
?>
