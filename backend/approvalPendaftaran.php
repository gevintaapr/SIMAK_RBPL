<?php
session_start();
require_once __DIR__ . '/../config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $id_pendaftaran = mysqli_real_escape_string($conn, $_POST['id_pendaftaran']);

    switch ($action) {
        case 'approve_pendaftaran':
            $status = mysqli_real_escape_string($conn, $_POST['status']); // 'disetujui' or 'ditolak'
            
            $query = "UPDATE pendaftaran SET status_approval = '$status' WHERE id_pendaftaran = '$id_pendaftaran'";
            
            if (mysqli_query($conn, $query)) {
                $msg = ($status === 'disetujui') ? "Pendaftaran berhasil disetujui." : "Pendaftaran telah ditolak.";
                echo json_encode(['status' => 'success', 'message' => $msg]);
            } else {
                echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
            }
            break;

        default:
            echo json_encode(['status' => 'error', 'message' => 'Aksi tidak valid.']);
            break;
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Permintaan tidak valid.']);
}
?>
