<?php
session_start();
require_once __DIR__ . '/../config/config.php';

function generateNoPendaftaran($conn) {
    $tahun = date("Y");
    // Find the max ID for this year
    $query = mysqli_query($conn, "SELECT MAX(CAST(SUBSTRING(id_pendaftaran, 10) AS UNSIGNED)) as max_id FROM pendaftaran WHERE id_pendaftaran LIKE 'REG-$tahun-%'");
    $data = mysqli_fetch_assoc($query);
    $next_id = ($data['max_id'] ?? 0) + 1;
    return "REG-$tahun-" . str_pad($next_id, 4, "0", STR_PAD_LEFT);
}

function generateTokenAkses() {
    $prefix = "HCTS";
    $letters = strtoupper(substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 2));
    $numbers = str_pad(rand(0, 99), 2, "0", STR_PAD_LEFT);
    return "$prefix-$letters$numbers";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // File upload handlings
    $upload_dir = __DIR__ . '/../public/uploads/';
    $files = ['ktp', 'ijazah', 'foto_siswa', 'bukti_pendaftaran', 'surat_pernyataan'];
    $saved_paths = [];

    // Validate files first
    foreach ($files as $file_field) {
        if (!isset($_FILES[$file_field]) || $_FILES[$file_field]['error'] != UPLOAD_ERR_OK) {
            die("Error: File $file_field tidak diunggah atau terjadi kesalahan.");
        }

        $file_tmp = $_FILES[$file_field]['tmp_name'];
        $file_type = mime_content_type($file_tmp);
        $file_ext = strtolower(pathinfo($_FILES[$file_field]['name'], PATHINFO_EXTENSION));

        // Validation for PDF only
        if ($file_type !== 'application/pdf' || $file_ext !== 'pdf') {
            die("Error: Format file $file_field tidak valid. Hanya PDF yang diizinkan.");
        }
    }

    // Now safely move them
    foreach ($files as $file_field) {
        $file_tmp = $_FILES[$file_field]['tmp_name'];
        $safe_filename = time() . '_' . rand(1000, 9999) . '_' . $file_field . '.pdf';
        $destination = $upload_dir . $safe_filename;
        
        if (move_uploaded_file($file_tmp, $destination)) {
            $saved_paths[$file_field] = 'public/uploads/' . $safe_filename;
        } else {
            die("Error: Gagal memindah file $file_field.");
        }
    }

    $id_pendaftaran = generateNoPendaftaran($conn);
    
    // Generate unique token
    do {
        $token = generateTokenAkses();
        $cek = mysqli_query($conn, "SELECT id_pendaftaran FROM pendaftaran WHERE token_akses='$token'");
    } while(mysqli_num_rows($cek) > 0);

    // Provide dummy values for other unused form inputs temporarily, because table requires them
    $no_wa = '0000';
    $tanggal_lahir = '2000-01-01';
    $alamat = 'N/A';
    $program = 'N/A';
    
    $ktp_path = mysqli_real_escape_string($conn, $saved_paths['ktp']);
    $ijazah_path = mysqli_real_escape_string($conn, $saved_paths['ijazah']);
    $foto_path = mysqli_real_escape_string($conn, $saved_paths['foto_siswa']);
    $bukti_path = mysqli_real_escape_string($conn, $saved_paths['bukti_pendaftaran']);
    $surat_path = mysqli_real_escape_string($conn, $saved_paths['surat_pernyataan']);

    $query = "INSERT INTO pendaftaran (
        id_pendaftaran, id_user, nama_cs, email, no_wa, tanggal_lahir, alamat,
        ktp, ijazah, foto_siswa, bukti_pendaftaran, surat_pernyataan, program,
        token_akses, status_approval
    ) VALUES (
        '$id_pendaftaran', 0, '$nama', '$email', '$no_wa', '$tanggal_lahir', '$alamat',
        '$ktp_path', '$ijazah_path', '$foto_path', '$bukti_path', '$surat_path', '$program',
        '$token', 0
    )";

    if (mysqli_query($conn, $query)) {
        $_SESSION['pendaftaran_sukses'] = true;
        $_SESSION['kode_akses'] = $id_pendaftaran;
        $_SESSION['token'] = $token;
        
        header("Location: ../user/siswa/pendaftaran_berhasil.php");
        exit();
    } else {
        echo "Query Error: " . mysqli_error($conn);
    }
}
?>
