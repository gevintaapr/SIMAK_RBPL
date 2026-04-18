<?php
// Pengaturan Durasi Sesi (1 Jam = 3600 detik)
ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fitur Auto-Logout jika tidak ada aktifitas selama 1 jam
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 3600)) {
    session_unset();
    session_destroy();
    header("Location: ../public/MainLogin.php?error=" . urlencode("Sesi berakhir karena tidak ada aktifitas selama 1 jam."));
    exit();
}
$_SESSION['last_activity'] = time();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "simakhcts";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}
?>