<?php
require_once '../config/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_magang = $_POST['id_magang'];
    $status = $_POST['status'];

    $sql = "UPDATE magang SET status_magang = '$status' WHERE id_magang = $id_magang";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(['status' => 'success', 'message' => 'Status magang berhasil diperbarui.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metode akses tidak valid.']);
}
?>
