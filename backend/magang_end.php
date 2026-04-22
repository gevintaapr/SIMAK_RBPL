<?php
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = $_SESSION['user_id'];
    
    // Ambil id_siswa
    $query_siswa = mysqli_query($conn, "SELECT id_siswa FROM siswa WHERE id_user = $id_user");
    $siswa = mysqli_fetch_assoc($query_siswa);
    $id_siswa = $siswa['id_siswa'];

    $nama_perusahaan = mysqli_real_escape_string($conn, $_POST['nama_perusahaan']);
    $posisi = mysqli_real_escape_string($conn, $_POST['posisi']);
    $lokasi = mysqli_real_escape_string($conn, $_POST['lokasi']);
    $tgl_mulai = $_POST['tanggal_mulai'];
    $tgl_selesai = $_POST['tanggal_selesai'];
    $kontak = mysqli_real_escape_string($conn, $_POST['kontak_person']);
    $action = $_POST['action']; // 'draft' atau 'submit'

    $status = ($action === 'submit') ? 'pending' : 'draft';

    $sql = "INSERT INTO magang (id_siswa, nama_perusahaan, posisi, lokasi, tanggal_mulai, tanggal_selesai, kontak_person, status_magang) 
            VALUES ('$id_siswa', '$nama_perusahaan', '$posisi', '$lokasi', '$tgl_mulai', '$tgl_selesai', '$kontak', '$status')";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../user/siswa/magang_siswa.php?success=1");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
