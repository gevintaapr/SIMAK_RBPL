<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    echo json_encode(['status' => 'error', 'message' => 'Akses ditolak.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['action'])) {
    echo json_encode(['status' => 'error', 'message' => 'Permintaan tidak valid.']);
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$action  = $_POST['action'];

if ($action === 'ajukan') {
    $nama_tempat   = mysqli_real_escape_string($conn, $_POST['nama_tempat'] ?? '');
    $alamat_tempat = mysqli_real_escape_string($conn, $_POST['alamat_tempat'] ?? '');

    if (empty($nama_tempat) || empty($alamat_tempat)) {
        echo json_encode(['status' => 'error', 'message' => 'Semua kolom wajib diisi.']);
        exit;
    }

    $sql = "INSERT INTO magang (user_id, nama_tempat, alamat_tempat, status_pengajuan, status_verifikasi, status_magang) 
            VALUES ($user_id, '$nama_tempat', '$alamat_tempat', 'pending', 'pending', 'belum_mulai')";
    
    if (mysqli_query($conn, $sql)) {
        echo json_encode(['status' => 'success', 'message' => 'Pengajuan magang berhasil dikirim!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal mengirim pengajuan: ' . mysqli_error($conn)]);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Aksi tidak dikenal.']);
}
?>
