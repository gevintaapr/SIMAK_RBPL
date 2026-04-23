<?php
require_once 'config/config.php';
echo "\n--- nilai_magang table ---\n";
$res = mysqli_query($conn, 'DESCRIBE nilai_magang');
while($row = mysqli_fetch_assoc($res)) {
    echo $row['Field'] . " (" . $row['Type'] . ")\n";
}

echo "\n--- laporan_harian table ---\n";
$res = mysqli_query($conn, 'DESCRIBE laporan_harian');
while($row = mysqli_fetch_assoc($res)) {
    echo $row['Field'] . " (" . $row['Type'] . ")\n";
}
