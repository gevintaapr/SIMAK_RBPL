<?php
require_once 'config/config.php';
$queries = [
    "ALTER TABLE magang MODIFY COLUMN status_magang ENUM('draft','pending','disetujui','ditolak','berlangsung','selesai')",
    "ALTER TABLE magang ADD COLUMN no_sertifikat VARCHAR(50) DEFAULT NULL",
    "ALTER TABLE nilai_magang ADD COLUMN evaluasi_laporan TEXT DEFAULT NULL"
];

foreach ($queries as $q) {
    if (mysqli_query($conn, $q)) {
        echo "Success: $q\n";
    } else {
        echo "Error: $q -> " . mysqli_error($conn) . "\n";
    }
}
