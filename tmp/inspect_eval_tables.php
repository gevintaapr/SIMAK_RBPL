<?php
require_once 'c:/xampp/htdocs/SIMAK_RBPL/SIMAK_RBPL/config/config.php';

echo "=== Struktur Tabel Evaluasi ===\n";
$res1 = mysqli_query($conn, "DESCRIBE evaluasi");
while($row = mysqli_fetch_assoc($res1)) print_r($row);

echo "\n=== Data Mata Pelajaran ===\n";
$res2 = mysqli_query($conn, "SELECT * FROM mata_pelajaran");
while($row = mysqli_fetch_assoc($res2)) print_r($row);
?>
