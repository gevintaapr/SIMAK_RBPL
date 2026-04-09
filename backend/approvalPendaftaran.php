<?php
session_start();
require_once __DIR__ . '/../config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $id_pendaftaran = mysqli_real_escape_string($conn, $_POST['id_pendaftaran']);

    switch ($action) {
        case 'approve':
            $status_approval = mysqli_real_escape_string($conn, $_POST['status_approval']);
            
            $check = mysqli_query($conn, "SELECT status_berkas FROM pendaftaran WHERE id_pendaftaran = '$id_pendaftaran'");
            $data = mysqli_fetch_assoc($check);
            
            if ($data && $data['status_berkas'] === 'valid') {
                $query = "UPDATE pendaftaran SET status_approval = '$status_approval' WHERE id_pendaftaran = '$id_pendaftaran'";
                if (mysqli_query($conn, $query)) {
                    echo json_encode(['status' => 'success', 'message' => "Pendaftaran $status_approval."]);
                } else {
                    echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Berkas belum divalidasi oleh Admin.']);
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
