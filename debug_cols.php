<?php
require_once __DIR__ . '/config/config.php';
$res = mysqli_query($conn, 'DESCRIBE siswa');
$cols = [];
while($row = mysqli_fetch_assoc($res)) $cols[] = $row['Field'];
file_put_contents('siswa_cols.txt', implode("\n", $cols));

$res2 = mysqli_query($conn, 'DESCRIBE pendaftaran');
$cols2 = [];
while($row = mysqli_fetch_assoc($res2)) $cols2[] = $row['Field'];
file_put_contents('pendaftaran_cols.txt', implode("\n", $cols2));
?>
