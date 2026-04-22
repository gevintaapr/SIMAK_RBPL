<?php
require_once 'c:/xampp/htdocs/SIMAK_RBPL/SIMAK_RBPL/config/config.php';
session_start();

echo "Session User ID: " . ($_SESSION['user_id'] ?? 'NULL') . "\n";
echo "Session Role: " . ($_SESSION['role'] ?? 'NULL') . "\n";

if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];
    $res = mysqli_query($conn, "SELECT * FROM user WHERE id_user = $uid");
    echo "\nUser Table Data:\n";
    print_r(mysqli_fetch_assoc($res));

    $res2 = mysqli_query($conn, "SELECT * FROM siswa WHERE id_user = $uid");
    echo "\nSiswa Table Data:\n";
    $siswa = mysqli_fetch_assoc($res2);
    if ($siswa) {
        print_r($siswa);
    } else {
        echo "TIDAK DITEMUKAN di tabel siswa dengan id_user = $uid\n";
    }
}
?>
