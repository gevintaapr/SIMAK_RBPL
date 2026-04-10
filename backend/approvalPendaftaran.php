<?php
session_start();
require_once __DIR__ . '/../config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $id_pendaftaran = mysqli_real_escape_string($conn, $_POST['id_pendaftaran']);

    switch ($action) {
        case 'approve_pendaftaran':
            $status = mysqli_real_escape_string($conn, $_POST['status']); // 'disetujui' or 'ditolak'
            
            mysqli_begin_transaction($conn);
            try {
                // 1. Update status pendaftaran
                $query = "UPDATE pendaftaran SET status_approval = '$status' WHERE id_pendaftaran = '$id_pendaftaran'";
                mysqli_query($conn, $query);

                if ($status === 'disetujui') {
                    // 2. Ambil data pendaftaran
                    $res = mysqli_query($conn, "SELECT * FROM pendaftaran WHERE id_pendaftaran = '$id_pendaftaran'");
                    $p = mysqli_fetch_assoc($res);

                    if (!$p) throw new Exception("Data pendaftaran tidak ditemukan.");

                    // 3. Buat Akun User Baru (Role 1 = Siswa)
                    $nama_parts = explode(' ', strtolower(trim($p['nama_cs'])));
                    $nama_awal = preg_replace('/[^a-z]/', '', $nama_parts[0]);
                    $random_suffix = substr($id_pendaftaran, -4);
                    $email_belajar = $nama_awal . $random_suffix . ".26@hcts.ac.id";
                    $password_plain = "HCTS2026";
                    $password_hashed = password_hash($password_plain, PASSWORD_DEFAULT);

                    // Insert ke tabel user
                    $user_query = "INSERT INTO user (username, email, password, role_id, is_active, create_at) 
                                   VALUES (?, ?, ?, 1, 1, NOW())";
                    $stmt_user = mysqli_prepare($conn, $user_query);
                    mysqli_stmt_bind_param($stmt_user, 'sss', $p['nama_cs'], $email_belajar, $password_hashed);
                    mysqli_stmt_execute($stmt_user);
                    $new_user_id = mysqli_insert_id($conn);

                    // 4. Input ke tabel siswa
                    $id_siswa = "HC-" . str_replace('REG-', '', $id_pendaftaran);
                    $siswa_query = "INSERT INTO siswa (id_siswa, id_user, nama_lengkap, program_pembelajaran, id_pendaftaran, email_belajar, password) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?)";
                    $stmt_siswa = mysqli_prepare($conn, $siswa_query);
                    mysqli_stmt_bind_param($stmt_siswa, 'sisssss', $id_siswa, $new_user_id, $p['nama_cs'], $p['program'], $id_pendaftaran, $email_belajar, $password_plain);
                    mysqli_stmt_execute($stmt_siswa);

                    // 5. Update email_belajar di tabel pendaftaran
                    mysqli_query($conn, "UPDATE pendaftaran SET email_belajar = '$email_belajar', id_user = $new_user_id WHERE id_pendaftaran = '$id_pendaftaran'");
                }

                mysqli_commit($conn);
                $msg = ($status === 'disetujui') ? "Pendaftaran berhasil disetujui. Akun siswa telah dibuat." : "Pendaftaran telah ditolak.";
                echo json_encode(['status' => 'success', 'message' => $msg]);

            } catch (Exception $e) {
                mysqli_rollback($conn);
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
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
