<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 5) {
    echo json_encode(['status' => 'error', 'message' => 'Akses ditolak.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_taiwan = (int)($_POST['id_taiwan'] ?? 0);
    $status = mysqli_real_escape_string($conn, $_POST['status'] ?? '');

    if (!$id_taiwan || !$status) {
        echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap.']);
        exit;
    }

    $sql = "UPDATE program_taiwan SET status = '$status' WHERE id_taiwan = $id_taiwan";
    if (mysqli_query($conn, $sql)) {
        echo json_encode(['status' => 'success', 'message' => 'Status berhasil diperbarui.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui status: ' . mysqli_error($conn)]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metode tidak diizinkan.']);
}
?>
