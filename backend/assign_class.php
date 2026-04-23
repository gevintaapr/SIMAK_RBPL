<?php
require_once __DIR__ . '/../config/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_siswa'])) {
    $id_siswa = mysqli_real_escape_string($conn, $_POST['id_siswa']);
    $id_kelas_list = isset($_POST['id_kelas']) ? $_POST['id_kelas'] : [];

    // Begin Transaction or simple sequence
    // First, delete current assignments
    mysqli_query($conn, "DELETE FROM kelas_siswa WHERE Id_siswa = '$id_siswa'");

    if (!empty($id_kelas_list)) {
        // Insert new ones
        foreach ($id_kelas_list as $id_k) {
            $id_k = mysqli_real_escape_string($conn, $id_k);
            mysqli_query($conn, "INSERT INTO kelas_siswa (id_kelas, Id_siswa) VALUES ('$id_k', '$id_siswa')");
        }
    }

    echo json_encode(['status' => 'success', 'message' => 'Penempatan kelas berhasil diperbarui.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Permintaan tidak valid.']);
}
?>
