<?php
require_once __DIR__ . '/config/config.php';

$username = '12345678';
$email = 'test.instruktur@hcts.ac.id';
$password_plain = 'Hcts1234';
$password_hashed = password_hash($password_plain, PASSWORD_DEFAULT);
$role_id = 3;
$nama = 'Dosen Test';
$tgl_lahir = '1985-01-01';
$alamat = 'Gedung HCTS Lt. 2';
$no_wa = '081234567890';
$spesialisasi = 'Culinary Arts';
$create_at = date('Y-m-d');

mysqli_begin_transaction($conn);
try {
    // 1. Insert into User table
    $q1 = "INSERT INTO user (username, email, password, role_id, is_active, create_at) 
           VALUES ('$username', '$email', '$password_hashed', $role_id, 1, '$create_at')";
    if (!mysqli_query($conn, $q1)) throw new Exception(mysqli_error($conn));
    
    $id_user = mysqli_insert_id($conn);
    
    // 2. Insert into Pengajar table
    $q2 = "INSERT INTO pengajar (id_user, nip_pengajar, nama_pengajar, tanggal_lahir, alamat, noWA, spesialisasi, email_pengajar, password, profil, update_at)
           VALUES ($id_user, '$username', '$nama', '$tgl_lahir', '$alamat', '$no_wa', '$spesialisasi', '$email', '$password_hashed', '', '$create_at')";
    if (!mysqli_query($conn, $q2)) throw new Exception(mysqli_error($conn));
    
    mysqli_commit($conn);
    echo "AKUN TEST BERHASIL DIBUAT!\n";
    echo "Username/NIP : $username\n";
    echo "Password     : $password_plain\n";
} catch (Exception $e) {
    mysqli_rollback($conn);
    echo "GAGAL: " . $e->getMessage();
}
?>
