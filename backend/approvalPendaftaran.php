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

                    // 3. Update Akun User (Dari Role 2 ke Role 1)
                    $nama_parts = explode(' ', strtolower(trim($p['nama_cs'])));
                    $nama_awal = preg_replace('/[^a-z]/', '', $nama_parts[0]);
                    $random_suffix = substr($id_pendaftaran, -4);
                    
                    // Generate NIM dan Email Belajar
                    $nim_siswa = "HC-" . str_replace('REG-', '', $p['token_masuk']);
                    $email_belajar = $nama_awal . $random_suffix . ".26@hcts.ac.id";
                    
                    $password_plain = "HCTS2026";
                    $password_hashed = password_hash($password_plain, PASSWORD_DEFAULT);
                    $existing_user_id = $p['id_user'];

                    // Update ke tabel user (Username diubah jadi NIM)
                    $user_query = "UPDATE user SET username = ?, email = ?, password = ?, role_id = 1, create_at = NOW() WHERE id_user = ?";
                    $stmt_user = mysqli_prepare($conn, $user_query);
                    mysqli_stmt_bind_param($stmt_user, 'sssi', $nim_siswa, $email_belajar, $password_hashed, $existing_user_id);
                    mysqli_stmt_execute($stmt_user);
                    $new_user_id = $existing_user_id;

                    // 4. Input ke tabel siswa
                    $siswa_query = "INSERT INTO siswa (id_user, nim_siswa, nama_lengkap, program_pembelajaran, id_pendaftaran, email_belajar, password) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?)";
                    $stmt_siswa = mysqli_prepare($conn, $siswa_query);
                    mysqli_stmt_bind_param($stmt_siswa, 'issssss', $new_user_id, $nim_siswa, $p['nama_cs'], $p['program'], $id_pendaftaran, $email_belajar, $password_plain);
                    mysqli_stmt_execute($stmt_siswa);

                    // 5. Update email_belajar dan token_expired di tabel pendaftaran
                    // Token expired diset 2 hari dari sekarang
                    $token_expired = date('Y-m-d H:i:s', strtotime('+2 days'));
                    mysqli_query($conn, "UPDATE pendaftaran SET email_belajar = '$email_belajar', id_user = $new_user_id, token_expired = '$token_expired' WHERE id_pendaftaran = '$id_pendaftaran'");
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
