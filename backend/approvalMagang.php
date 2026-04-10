<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'approve') {
    $db = new Database();
    $conn = $db->getConnection();

    $magang_id = $_POST['magang_id'];
    $status = $_POST['status'];
    $catatan = $_POST['catatan'] ?? '';

    try {
        $stmt = $conn->prepare("UPDATE magang SET status_pengajuan = ?, catatan_pimpinan = ? WHERE id = ?");
        $stmt->execute([$status, $catatan, $magang_id]);

        echo json_encode(['status' => 'success', 'message' => 'Status magang berhasil diperbarui.']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui status: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Aksi tidak valid.']);
}
?>
