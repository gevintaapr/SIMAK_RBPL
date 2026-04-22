<?php
require_once __DIR__ . '/../config/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'fetch':
            $query = "SELECT j.*, u.username as nama_pengajar 
                      FROM jadwal j 
                      LEFT JOIN user u ON j.id_pengajar = u.id_user 
                      ORDER BY j.hari ASC, j.jam_mulai ASC";
            $result = mysqli_query($conn, $query);
            $data = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
            echo json_encode(['status' => 'success', 'data' => $data]);
            break;

        case 'add':
            $program = mysqli_real_escape_string($conn, $_POST['program']);
            $hari = mysqli_real_escape_string($conn, $_POST['hari']);
            $jam_mulai = mysqli_real_escape_string($conn, $_POST['jam_mulai']);
            $jam_selesai = mysqli_real_escape_string($conn, $_POST['jam_selesai']);
            $materi = mysqli_real_escape_string($conn, $_POST['materi']);
            $id_pengajar = mysqli_real_escape_string($conn, $_POST['id_pengajar']);
            $ruangan = mysqli_real_escape_string($conn, $_POST['ruangan']);

            $query = "INSERT INTO jadwal (program, hari, jam_mulai, jam_selesai, materi, id_pengajar, ruangan) 
                      VALUES ('$program', '$hari', '$jam_mulai', '$jam_selesai', '$materi', '$id_pengajar', '$ruangan')";
            
            if (mysqli_query($conn, $query)) {
                echo json_encode(['status' => 'success', 'message' => 'Jadwal berhasil ditambahkan.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
            }
            break;

        case 'edit':
            $id_jadwal = mysqli_real_escape_string($conn, $_POST['id_jadwal']);
            $program = mysqli_real_escape_string($conn, $_POST['program']);
            $hari = mysqli_real_escape_string($conn, $_POST['hari']);
            $jam_mulai = mysqli_real_escape_string($conn, $_POST['jam_mulai']);
            $jam_selesai = mysqli_real_escape_string($conn, $_POST['jam_selesai']);
            $materi = mysqli_real_escape_string($conn, $_POST['materi']);
            $id_pengajar = mysqli_real_escape_string($conn, $_POST['id_pengajar']);
            $ruangan = mysqli_real_escape_string($conn, $_POST['ruangan']);

            $query = "UPDATE jadwal SET 
                      program = '$program', 
                      hari = '$hari', 
                      jam_mulai = '$jam_mulai', 
                      jam_selesai = '$jam_selesai', 
                      materi = '$materi', 
                      id_pengajar = '$id_pengajar', 
                      ruangan = '$ruangan' 
                      WHERE id_jadwal = '$id_jadwal'";
            
            if (mysqli_query($conn, $query)) {
                echo json_encode(['status' => 'success', 'message' => 'Jadwal berhasil diperbarui.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
            }
            break;

        case 'delete':
            $id_jadwal = mysqli_real_escape_string($conn, $_POST['id_jadwal']);
            $query = "DELETE FROM jadwal WHERE id_jadwal = '$id_jadwal'";
            
            if (mysqli_query($conn, $query)) {
                echo json_encode(['status' => 'success', 'message' => 'Jadwal berhasil dihapus.']);
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
