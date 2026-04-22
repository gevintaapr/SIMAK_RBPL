<?php
require_once 'c:/xampp/htdocs/SIMAK_RBPL/SIMAK_RBPL/config/config.php';

echo "Daftar 5 Siswa Terakhir:\n";
$res = mysqli_query($conn, "SELECT id_siswa, id_user, nama_lengkap, nim_siswa FROM siswa ORDER BY id_siswa DESC LIMIT 5");
while($row = mysqli_fetch_assoc($res)) {
    print_r($row);
}

echo "\nDaftar 5 User Terakhir (Role 1):\n";
$res2 = mysqli_query($conn, "SELECT id_user, username, role FROM user WHERE role = '1' ORDER BY id_user DESC LIMIT 5");
while($row = mysqli_fetch_assoc($res2)) {
    print_r($row);
}
?>
