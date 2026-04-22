<?php
require_once 'c:/xampp/htdocs/SIMAK_RBPL/SIMAK_RBPL/config/config.php';

// Drop tabel lama jika perlu atau kita rename untuk cadangan
mysqli_query($conn, "DROP TABLE IF EXISTS evaluasi");

$sql = "CREATE TABLE evaluasi (
    id_evaluasi INT AUTO_INCREMENT PRIMARY KEY,
    id_siswa INT NOT NULL,
    id_pengajar INT,
    DUI1 INT DEFAULT 0,
    DUI2 INT DEFAULT 0,
    DUI3 INT DEFAULT 0,
    DUI4 INT DEFAULT 0,
    DUI5 INT DEFAULT 0,
    DUI6 INT DEFAULT 0,
    DUI7 INT DEFAULT 0,
    DUI8 INT DEFAULT 0,
    rata_rata FLOAT DEFAULT 0,
    status_kelulusan ENUM('Lulus', 'Tidak Lulus', 'Pending') DEFAULT 'Pending',
    periode_semester VARCHAR(50),
    tanggal_input TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $sql)) {
    echo "Tabel evaluasi berhasil direstrukturisasi menjadi 1 baris per siswa.\n";
    
    // Tambahkan data dummy untuk testing jika perlu
    mysqli_query($conn, "INSERT INTO evaluasi (id_siswa, id_pengajar, DUI1, DUI2, DUI3, DUI4, DUI5, DUI6, DUI7, DUI8, rata_rata, status_kelulusan, periode_semester) 
    VALUES (9, 1, 85, 80, 78, 90, 85, 82, 88, 84, 84, 'Lulus', 'Semester Ganjil 2025/2026')");
    
    echo "Data testing berhasil dimasukkan.\n";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
