<?php
require_once 'c:/xampp/htdocs/SIMAK_RBPL/SIMAK_RBPL/config/config.php';
$sql = "ALTER TABLE evaluasi ADD COLUMN catatan_pengajar TEXT AFTER status_kelulusan";
if (mysqli_query($conn, $sql)) {
    echo "Kolom catatan_pengajar berhasil ditambahkan.\n";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
