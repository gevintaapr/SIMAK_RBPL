<?php
session_start();
require_once __DIR__ . '/../config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $id_pendaftaran = mysqli_real_escape_string($conn, $_POST['id_pendaftaran']);

    switch ($action) {
        case 'verifikasi_berkas':
            $status_berkas = 'valid';
            $query = "UPDATE pendaftaran SET 
                      status_berkas = '$status_berkas', 
                      status_approval = 'pending', 
                      jadwal_wawancara = NULL
                      WHERE id_pendaftaran = '$id_pendaftaran'";
            if (mysqli_query($conn, $query)) {
                echo json_encode(['status' => 'success', 'message' => 'Berkas berhasil diverifikasi. Silakan tentukan jadwal wawancara.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
            }
            break;

        case 'set_jadwal':
            $jadwal_wawancara = mysqli_real_escape_string($conn, $_POST['jadwal_wawancara']);
            $query = "UPDATE pendaftaran SET jadwal_wawancara = '$jadwal_wawancara' WHERE id_pendaftaran = '$id_pendaftaran'";
            if (mysqli_query($conn, $query)) {
                echo json_encode(['status' => 'success', 'message' => 'Jadwal wawancara berhasil ditetapkan.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
            }
            break;

        case 'selesai_wawancara':
            $query = "UPDATE pendaftaran SET status_approval = 'menunggu_pimpinan' WHERE id_pendaftaran = '$id_pendaftaran'";
            if (mysqli_query($conn, $query)) {
                echo json_encode(['status' => 'success', 'message' => 'Proses wawancara selesai. Menunggu approval pimpinan.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
            }
            break;

        case 'hasil':
            $hasil_akhir = mysqli_real_escape_string($conn, $_POST['hasil_akhir']);
            $query = "UPDATE pendaftaran SET hasil_akhir = '$hasil_akhir' WHERE id_pendaftaran = '$id_pendaftaran'";
            if (mysqli_query($conn, $query)) {
                echo json_encode(['status' => 'success', 'message' => 'Hasil akhir berhasil disimpan.']);
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
