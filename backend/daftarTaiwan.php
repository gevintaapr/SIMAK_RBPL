<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    echo json_encode(['status' => 'error', 'message' => 'Akses ditolak.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_siswa = (int)($_POST['id_siswa'] ?? 0);

    if (!$id_siswa) {
        echo json_encode(['status' => 'error', 'message' => 'ID Siswa tidak ditemukan.']);
        exit;
    }

    // Cek apakah sudah terdaftar
    $check = mysqli_query($conn, "SELECT id_taiwan FROM program_taiwan WHERE id_siswa = $id_siswa");
    if (mysqli_num_rows($check) > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Anda sudah menyatakan minat untuk program ini.']);
        exit;
    }

    // Insert minat
    $sql = "INSERT INTO program_taiwan (id_siswa, status) VALUES ($id_siswa, 'berminat')";
    if (mysqli_query($conn, $sql)) {
        echo json_encode(['status' => 'success', 'message' => 'Minat Anda telah dicatat.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal mencatat data: ' . mysqli_error($conn)]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metode tidak diizinkan.']);
}
?>
