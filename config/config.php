<?php
if (session_status() === PHP_SESSION_NONE) {
    // Pengaturan Durasi Sesi (1 Jam = 3600 detik)
    ini_set('session.gc_maxlifetime', 3600);
    session_set_cookie_params(3600);
    session_start();
}

// Fitur Auto-Logout jika tidak ada aktifitas selama 1 jam
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 3600)) {
    session_unset();
    session_destroy();
    
    // Redirect menggunakan path relatif yang aman atau link login utama
    echo "<script>
        alert('Sesi Anda telah berakhir karena tidak ada aktifitas selama 1 jam. Silakan login kembali.');
        window.location.href = '/SIMAK_RBPL/SIMAK_RBPL/public/MainLogin.php';
    </script>";
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