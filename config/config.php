<?php
if (session_status() === PHP_SESSION_NONE) {
    // Pengaturan Durasi Sesi (1 Jam = 3600 detik)
    ini_set('session.gc_maxlifetime', 3600);
    session_set_cookie_params(3600);
    session_start();
}

// Database Credentials from Environment Variables (Railway Support)
$servername = getenv('MYSQLHOST') ?: "localhost";
$username = getenv('MYSQLUSER') ?: "root";
$password = getenv('MYSQLPASSWORD') ?: "";
$dbname = getenv('MYSQLDATABASE') ?: "simakhcts";
$port = getenv('MYSQLPORT') ?: "3306";

$conn = mysqli_connect($servername, $username, $password, $dbname, $port);

if (!$conn) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}

// Fitur Auto-Logout jika tidak ada aktifitas selama 1 jam
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 3600)) {
    session_unset();
    session_destroy();
    
    echo "<script>
        alert('Sesi Anda telah berakhir karena tidak ada aktifitas selama 1 jam. Silakan login kembali.');
        window.location.href = '/public/MainLogin.php';
    </script>";
    exit();
}
$_SESSION['last_activity'] = time();
?>