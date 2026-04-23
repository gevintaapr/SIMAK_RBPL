<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 5) {
    echo json_encode(['status' => 'error', 'message' => 'Akses ditolak.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $magang_id = (int)($_POST['magang_id'] ?? 0);
    $no_sertifikat = mysqli_real_escape_string($conn, $_POST['no_sertifikat'] ?? '');

    if (!$magang_id || !$no_sertifikat) {
        echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap.']);
        exit;
    }

    $sql = "UPDATE magang SET no_sertifikat = '$no_sertifikat' WHERE id_magang = $magang_id";
    if (mysqli_query($conn, $sql)) {
        echo json_encode(['status' => 'success', 'message' => 'Sertifikat berhasil diterbitkan.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menerbitkan sertifikat: ' . mysqli_error($conn)]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metode tidak diizinkan.']);
}
