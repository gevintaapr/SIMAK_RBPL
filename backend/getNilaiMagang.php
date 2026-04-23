<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/config.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], [1, 5])) {
    echo json_encode(['status' => 'error', 'message' => 'Akses ditolak.']);
    exit;
}

$magang_id = (int)($_GET['magang_id'] ?? 0);
if (!$magang_id) {
    echo json_encode(['status' => 'error', 'message' => 'ID magang tidak valid.']);
    exit;
}

$query = mysqli_query($conn, "SELECT * FROM nilai_magang WHERE id_magang = $magang_id LIMIT 1");
$nilai = mysqli_fetch_assoc($query);

if ($nilai) {
    echo json_encode(['status' => 'success', 'data' => $nilai]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Nilai tidak ditemukan.']);
}
