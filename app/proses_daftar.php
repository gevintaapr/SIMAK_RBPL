<?php
session_start();
require_once __DIR__ . '/../config/config.php';

function generateNoPendaftaran($conn) {
    $tahun = date("Y");
    // Mencari ID terbesar dari tabel pendaftaran
    $query = mysqli_query($conn, "SELECT MAX(id_pendaftaran) as max_id FROM pendaftaran");
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
    $nama = mysqli_real_escape_string($conn, $_POST['nama'] ?? '');
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $no_wa = mysqli_real_escape_string($conn, $_POST['no_wa'] ?? '');
    // Membersihkan nomor WA dari spasi, strip, atau karakter lain kecuali angka dan +
    $no_wa = preg_replace('/[^0-9+]/', '', $no_wa);
    $tanggal_lahir = mysqli_real_escape_string($conn, $_POST['tanggal_lahir'] ?? '');
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat'] ?? '');
    $program = mysqli_real_escape_string($conn, $_POST['program'] ?? '');
    
    // File upload handlings
    $upload_dir = __DIR__ . '/../public/uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $files = ['ktp', 'ijazah', 'foto_siswa', 'bukti_pendaftaran', 'surat_pernyataan'];
    $saved_paths = [];

    $allowed_image_mimes = ['image/jpeg', 'image/png', 'image/jpg'];
    $allowed_image_exts = ['jpg', 'jpeg', 'png'];

    // Validate files first
    foreach ($files as $file_field) {
        if (!isset($_FILES[$file_field]) || $_FILES[$file_field]['error'] != UPLOAD_ERR_OK) {
            die("Error: File $file_field tidak diunggah atau terjadi kesalahan.");
        }

        $file_tmp = $_FILES[$file_field]['tmp_name'];
        $file_type = mime_content_type($file_tmp);
        $file_ext = strtolower(pathinfo($_FILES[$file_field]['name'], PATHINFO_EXTENSION));

        if ($file_field === 'foto_siswa') {
            if (!in_array($file_type, $allowed_image_mimes) || !in_array($file_ext, $allowed_image_exts)) {
                die("Error: Format file pas foto tidak valid. Hanya JPG/PNG yang diizinkan.");
            }
        } else {
            // Validation for PDF only
            if ($file_type !== 'application/pdf' || $file_ext !== 'pdf') {
                die("Error: Format file $file_field tidak valid. Hanya PDF yang diizinkan.");
            }
        }
    }

    // Now safely move them
    foreach ($files as $file_field) {
        $file_tmp = $_FILES[$file_field]['tmp_name'];
        $file_ext = strtolower(pathinfo($_FILES[$file_field]['name'], PATHINFO_EXTENSION));
        
        $safe_filename = time() . '_' . rand(1000, 9999) . '_' . $file_field . '.' . $file_ext;
        $destination = $upload_dir . $safe_filename;
        
        if (move_uploaded_file($file_tmp, $destination)) {
            $saved_paths[$file_field] = 'public/uploads/' . $safe_filename;
        } else {
            die("Error: Gagal memindah file $file_field.");
        }
    }

    $no_reg = generateNoPendaftaran($conn);
    
    // Generate unique token
    do {
        $token = generateTokenAkses();
        $cek = mysqli_query($conn, "SELECT id_pendaftaran FROM pendaftaran WHERE token_akses='$token'");
    } while(mysqli_num_rows($cek) > 0);

    // Variables are already fetched from $_POST above
    
    $ktp_path = mysqli_real_escape_string($conn, $saved_paths['ktp']);
    $ijazah_path = mysqli_real_escape_string($conn, $saved_paths['ijazah']);
    $foto_path = mysqli_real_escape_string($conn, $saved_paths['foto_siswa']);
    $bukti_path = mysqli_real_escape_string($conn, $saved_paths['bukti_pendaftaran']);
    $surat_path = mysqli_real_escape_string($conn, $saved_paths['surat_pernyataan']);

    // 1. Buat akun di tabel user terlebih dahulu (Role 2 = Calon Siswa)
    // Mencari ID user berikutnya secara manual untuk menghindari duplicate entry
    $res_id = mysqli_query($conn, "SELECT MAX(id_user) as max_id FROM user");
    $data_id = mysqli_fetch_assoc($res_id);
    $new_user_id = ($data_id['max_id'] ?? 0) + 1;

    $user_pass = $token; 
    $user_query = "INSERT INTO user (id_user, username, email, password, role_id, is_active, create_at) 
                   VALUES ($new_user_id, '$no_reg', '$email', '$user_pass', 2, 1, NOW())";
    
    if (!mysqli_query($conn, $user_query)) {
        die("Error: Gagal membuat akun pengguna. " . mysqli_error($conn));
    }
    
    $new_user_id = mysqli_insert_id($conn);

    // 2. Masukkan ke tabel pendaftaran
    $query = "INSERT INTO pendaftaran (
        id_user, nama_cs, email, no_wa, tanggal_lahir, alamat,
        ktp, ijazah, foto_siswa, bukti_pendaftaran, surat_pernyataan, program,
        token_masuk, token_akses, status_approval
    ) VALUES (
        $new_user_id, '$nama', '$email', '$no_wa', '$tanggal_lahir', '$alamat',
        '$ktp_path', '$ijazah_path', '$foto_path', '$bukti_path', '$surat_path', '$program',
        '$no_reg', '$token', 0
    )";

    if (mysqli_query($conn, $query)) {
        $id_baru = mysqli_insert_id($conn);
        
        $_SESSION['pendaftaran_sukses'] = true;
        $_SESSION['kode_akses'] = $no_reg;
        $_SESSION['token'] = $token;
        $_SESSION['siswa_id'] = $id_baru;
        $_SESSION['user_id'] = $new_user_id;
        
        header("Location: ../user/siswa/pendaftaran_berhasil.php");
        exit();
    } else {
        echo "Query Error: " . mysqli_error($conn);
    }
}
?>
