<?php
session_start();
require_once '../config/database.php';

$db = new Database();
$conn = $db->getConnection();

header('Content-Type: application/json');

$action = $_POST['action'] ?? ($_GET['action'] ?? '');

if ($action === 'upload') {
    // Siswa Upload Bukti Pembayaran
    $user_id = $_SESSION['user_id'] ?? null;
    if (!$user_id) {
        header("Location: ../user/siswa/dashboard_siswa.php?error=Sesi berakhir. Silakan login kembali.");
        exit;
    }

    // Get id_siswa from user_id
    $stmt_get_siswa = $conn->prepare("SELECT id_siswa FROM siswa WHERE id_user = ? LIMIT 1");
    $stmt_get_siswa->execute([$user_id]);
    $siswa = $stmt_get_siswa->fetch();
    
    if (!$siswa) {
        header("Location: ../user/siswa/pembayaranSiswa.php?error=Data siswa tidak ditemukan.");
        exit;
    }
    $id_siswa = $siswa['id_siswa'];

    $id_bp = $_POST['id_bp'] ?? 0;
    $deskripsi = $_POST['deskripsi'] ?? 'Pembayaran Biaya Pendidikan';
    $nominal = $_POST['nominal'] ?? 0;
    $catatan = $_POST['catatan'] ?? '';
    $tanggal = date('Y-m-d H:i:s');
    $status = 'pending';

    // File Upload Handling
    $target_dir = "../public/uploads/pembayaran/";
    // Ensure directory exists
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_extension = pathinfo($_FILES["bukti_file"]["name"], PATHINFO_EXTENSION);
    $file_name = "PAY_" . time() . "_" . $id_siswa . "." . $file_extension;
    $target_file = $target_dir . $file_name;
    $db_file_path = "/public/uploads/pembayaran/" . $file_name;

    if (move_uploaded_file($_FILES["bukti_file"]["tmp_name"], $target_file)) {
        try {
            $stmt = $conn->prepare("INSERT INTO pembayaran (id_siswa, id_biaya_pendidikan, nominal, bukti_file, deskripsi, tanggal_pembayaran, status_pembayaran) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$id_siswa, $id_bp, $nominal, $db_file_path, $deskripsi, $tanggal, $status]);
            
            header("Location: ../user/siswa/pembayaranSiswa.php?success=1");
            exit;
        } catch (Exception $e) {
            header("Location: ../user/siswa/pembayaranSiswa.php?error=" . urlencode("Database Error: " . $e->getMessage()));
            exit;
        }
    } else {
        header("Location: ../user/siswa/pembayaranSiswa.php?error=Gagal mengunggah berkas bukti pembayaran.");
        exit;
    }

} elseif ($action === 'verifikasi') {
    // Admin/Akademik Verify Payment
    $id_pembayaran = $_POST['id'] ?? null;
    $status_verif = $_POST['status_verif'] ?? ''; // 'diterima' or 'ditolak'
    $keterangan = $_POST['keterangan'] ?? '';

    if (!$id_pembayaran || !$status_verif) {
        echo json_encode(['status' => 'error', 'message' => 'Data verifikasi tidak lengkap.']);
        exit;
    }

    try {
        $stmt = $conn->prepare("UPDATE pembayaran SET status_pembayaran = ?, keterangan = ? WHERE id_pembayaran = ?");
        $stmt->execute([$status_verif, $keterangan, $id_pembayaran]);
        
        echo json_encode(['status' => 'success', 'message' => 'Status pembayaran berhasil diperbarui.']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui status: ' . $e->getMessage()]);
    }
    exit;

} else {
    echo json_encode(['status' => 'error', 'message' => 'Aksi tidak dikenali.']);
}
?>
