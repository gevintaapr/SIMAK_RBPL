<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 5) {
    echo json_encode(['status' => 'error', 'message' => 'Akses ditolak.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['action'])) {
    echo json_encode(['status' => 'error', 'message' => 'Permintaan tidak valid.']);
    exit;
}

$action    = $_POST['action'];
$magang_id = (int)($_POST['magang_id'] ?? 0);

if (!$magang_id) {
    echo json_encode(['status' => 'error', 'message' => 'ID magang tidak valid.']);
    exit;
}

if ($action === 'verifikasi') {
    $status  = $_POST['status'] ?? '';
    $catatan = mysqli_real_escape_string($conn, $_POST['catatan'] ?? '');

    if (!in_array($status, ['diterima', 'ditolak'])) {
        echo json_encode(['status' => 'error', 'message' => 'Status verifikasi tidak valid.']);
        exit;
    }
    if ($status === 'ditolak' && empty($catatan)) {
        echo json_encode(['status' => 'error', 'message' => 'Catatan wajib diisi jika menolak.']);
        exit;
    }

    // Jika diterima → set status_magang ke 'berlangsung'
    $status_magang = ($status === 'diterima') ? 'berlangsung' : 'ditolak';
    $status_esc = mysqli_real_escape_string($conn, $status);

    $sql = "UPDATE magang SET status_admin = '$status_esc', catatan_admin = '$catatan', status_magang = '$status_magang' WHERE id_magang = $magang_id";
    if (mysqli_query($conn, $sql)) {
        echo json_encode(['status' => 'success', 'message' => 'Verifikasi berhasil disimpan.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan verifikasi: ' . mysqli_error($conn)]);
    }

} elseif ($action === 'simpan_nilai') {
    $job_knowledge       = (float)($_POST['job_knowledge'] ?? 0);
    $quantity_of_work    = (float)($_POST['quantity_of_work'] ?? 0);
    $quality_of_work     = (float)($_POST['quality_of_work'] ?? 0);
    $character_val       = (float)($_POST['character_val'] ?? 0);
    $personality         = (float)($_POST['personality'] ?? 0);
    $courtesy            = (float)($_POST['courtesy'] ?? 0);
    $personal_appearance = (float)($_POST['personal_appearance'] ?? 0);
    $attendance          = (float)($_POST['attendance'] ?? 0);
    
    $evaluasi = mysqli_real_escape_string($conn, $_POST['evaluasi_laporan'] ?? '');
    $admin_id = $_SESSION['user_id'];

    // Cek apakah sudah ada nilai
    $check = mysqli_query($conn, "SELECT id_nilai FROM nilai_magang WHERE id_magang = $magang_id");
    if (mysqli_num_rows($check) > 0) {
        $sql = "UPDATE nilai_magang SET 
                job_knowledge = $job_knowledge,
                quantity_of_work = $quantity_of_work,
                quality_of_work = $quality_of_work,
                character_val = $character_val,
                personality = $personality,
                courtesy = $courtesy,
                personal_appearance = $personal_appearance,
                attendance = $attendance,
                evaluasi_laporan = '$evaluasi',
                dinilai_oleh = $admin_id
                WHERE id_magang = $magang_id";
    } else {
        $sql = "INSERT INTO nilai_magang (id_magang, job_knowledge, quantity_of_work, quality_of_work, character_val, personality, courtesy, personal_appearance, attendance, evaluasi_laporan, dinilai_oleh) 
                VALUES ($magang_id, $job_knowledge, $quantity_of_work, $quality_of_work, $character_val, $personality, $courtesy, $personal_appearance, $attendance, '$evaluasi', $admin_id)";
    }

    if (mysqli_query($conn, $sql)) {
        echo json_encode(['status' => 'success', 'message' => 'Nilai dan evaluasi berhasil disimpan.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan nilai: ' . mysqli_error($conn)]);
    }

} elseif ($action === 'verifikasi_laporan') {
    $status   = $_POST['status'] ?? ''; // 'disetujui' or 'ditolak'
    $evaluasi = mysqli_real_escape_string($conn, $_POST['evaluasi'] ?? '');

    if (!in_array($status, ['disetujui', 'ditolak'])) {
        echo json_encode(['status' => 'error', 'message' => 'Status tidak valid.']);
        exit;
    }
    if ($status === 'ditolak' && empty($evaluasi)) {
        echo json_encode(['status' => 'error', 'message' => 'Catatan wajib diisi jika menolak laporan.']);
        exit;
    }

    // Update status_laporan di tabel magang
    $status_magang_update = ($status === 'disetujui') ? ", status_magang = 'selesai'" : ", status_magang = 'berlangsung'";
    $sql_magang = "UPDATE magang SET status_laporan = '$status' $status_magang_update WHERE id_magang = $magang_id";
    mysqli_query($conn, $sql_magang);

    // Update atau Insert evaluasi di tabel nilai_magang
    $check = mysqli_query($conn, "SELECT id_nilai FROM nilai_magang WHERE id_magang = $magang_id");
    if (mysqli_num_rows($check) > 0) {
        $sql_nilai = "UPDATE nilai_magang SET evaluasi_laporan = '$evaluasi' WHERE id_magang = $magang_id";
    } else {
        $admin_id = $_SESSION['user_id'];
        $sql_nilai = "INSERT INTO nilai_magang (id_magang, evaluasi_laporan, dinilai_oleh) VALUES ($magang_id, '$evaluasi', $admin_id)";
    }
    
    if (mysqli_query($conn, $sql_nilai)) {
        echo json_encode(['status' => 'success', 'message' => 'Status laporan berhasil diperbarui.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui evaluasi: ' . mysqli_error($conn)]);
    }

} elseif ($action === 'selesai') {
    $sql = "UPDATE magang SET status_magang = 'selesai' WHERE id_magang = $magang_id AND status_magang = 'berlangsung'";
    if (mysqli_query($conn, $sql) && mysqli_affected_rows($conn) > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Program magang berhasil diselesaikan.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyelesaikan magang atau status tidak sesuai.']);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Aksi tidak dikenal.']);
}
?>
