<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    echo json_encode(['status' => 'error', 'message' => 'Akses ditolak.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $magang_id = (int)($_POST['magang_id'] ?? 0);

    if (!$magang_id) {
        echo json_encode(['status' => 'error', 'message' => 'ID magang tidak valid.']);
        exit;
    }

    // Handle File Upload
    $file_name = null;
    if (isset($_FILES['file_laporan']) && $_FILES['file_laporan']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['file_laporan']['tmp_name'];
        $ext = pathinfo($_FILES['file_laporan']['name'], PATHINFO_EXTENSION);
        $file_name = 'laporan_' . $magang_id . '_' . time() . '.' . $ext;
        $upload_dir = __DIR__ . '/../assets/uploads/laporan/';
        
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        move_uploaded_file($file_tmp, $upload_dir . $file_name);
    }

    // Update file and set status_laporan to pending
    $sql = "UPDATE magang SET 
            file_laporan = IF('$file_name' != '', '$file_name', file_laporan),
            status_laporan = 'pending' 
            WHERE id_magang = $magang_id";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../user/siswa/magang_siswa.php?success=upload");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Metode tidak diizinkan.";
}
