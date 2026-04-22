<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 5) {
    echo json_encode(['status' => 'error', 'message' => 'Akses ditolak.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['action'])) {
    echo json_encode(['status' => 'error', 'message' => 'Permintaan tidak valid.']);
    exit;
}

$action    = $_POST['action'];
$magang_id = (int)($_POST['magang_id'] ?? 0);

if (!$magang_id) {
    echo json_encode(['status' => 'error', 'message' => 'ID magang tidak valid.']);
    exit;
}

if ($action === 'verifikasi') {
    $status  = $_POST['status'] ?? '';
    $catatan = mysqli_real_escape_string($conn, $_POST['catatan'] ?? '');

    if (!in_array($status, ['diterima', 'ditolak'])) {
        echo json_encode(['status' => 'error', 'message' => 'Status verifikasi tidak valid.']);
        exit;
    }
    if ($status === 'ditolak' && empty($catatan)) {
        echo json_encode(['status' => 'error', 'message' => 'Catatan wajib diisi jika menolak.']);
        exit;
    }

    // Jika diterima → set status_magang ke 'berlangsung'
    $status_magang = ($status === 'diterima') ? 'berlangsung' : 'belum_mulai';
    $status_esc = mysqli_real_escape_string($conn, $status);

    $sql = "UPDATE magang SET status_verifikasi = '$status_esc', catatan_admin = '$catatan', status_magang = '$status_magang' WHERE id = $magang_id";
    if (mysqli_query($conn, $sql)) {
        echo json_encode(['status' => 'success', 'message' => 'Verifikasi berhasil disimpan.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan verifikasi: ' . mysqli_error($conn)]);
    }

} elseif ($action === 'selesai') {
    $sql = "UPDATE magang SET status_magang = 'selesai' WHERE id = $magang_id AND status_magang = 'berlangsung'";
    if (mysqli_query($conn, $sql) && mysqli_affected_rows($conn) > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Program magang berhasil diselesaikan.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyelesaikan magang atau status tidak sesuai.']);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Aksi tidak dikenal.']);
}
?>
