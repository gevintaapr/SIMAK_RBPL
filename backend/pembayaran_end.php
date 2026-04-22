require_once '../config/config.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? ($_GET['action'] ?? '');

if ($action === 'upload') {
    // Siswa Upload Bukti Pembayaran
    $user_id = $_SESSION['user_id'] ?? null;
    if (!$user_id) {
        header("Location: ../user/siswa/dashboard_siswa.php?error=Sesi berakhir. Silakan login kembali.");
        exit;
    }

    // Get id_siswa from user_id using mysqli
    $stmt_get_siswa = mysqli_prepare($conn, "SELECT id_siswa FROM siswa WHERE id_user = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt_get_siswa, "i", $user_id);
    mysqli_stmt_execute($stmt_get_siswa);
    $res_siswa = mysqli_stmt_get_result($stmt_get_siswa);
    $siswa = mysqli_fetch_assoc($res_siswa);
    
    if (!$siswa) {
        header("Location: ../user/siswa/pembayaranSiswa.php?error=Data profil siswa tidak ditemukan.");
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
            $stmt = mysqli_prepare($conn, "INSERT INTO pembayaran (id_siswa, id_biaya_pendidikan, nominal, bukti_file, deskripsi, tanggal_pembayaran, status_pembayaran) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "iiissss", $id_siswa, $id_bp, $nominal, $db_file_path, $deskripsi, $tanggal, $status);
            mysqli_stmt_execute($stmt);
            
            // Link ke Logika Dashboard: Jika yang dibayar adalah DP (id_bp = 1), update status_pembayaran di tabel siswa jadi 'pending'
            if ($id_bp == 1) {
                $upd_siswa = mysqli_prepare($conn, "UPDATE siswa SET status_pembayaran = 'pending' WHERE id_siswa = ?");
                mysqli_stmt_bind_param($upd_siswa, "i", $id_siswa);
                mysqli_stmt_execute($upd_siswa);
            }

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
        // Ambil data pembayaran sebelum update untuk cek id_bp dan id_siswa
        $stmt_check = mysqli_prepare($conn, "SELECT id_siswa, id_biaya_pendidikan FROM pembayaran WHERE id_pembayaran = ?");
        mysqli_stmt_bind_param($stmt_check, "i", $id_pembayaran);
        mysqli_stmt_execute($stmt_check);
        $pay_data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_check));

        $stmt = mysqli_prepare($conn, "UPDATE pembayaran SET status_pembayaran = ?, keterangan = ? WHERE id_pembayaran = ?");
        mysqli_stmt_bind_param($stmt, "ssi", $status_verif, $keterangan, $id_pembayaran);
        mysqli_stmt_execute($stmt);
        
        // Link ke Logika Dashboard: Jika verifikasi sukses dan jenisnya DP (id_bp = 1)
        if ($pay_data && $pay_data['id_biaya_pendidikan'] == 1) {
            $new_siswa_status = ($status_verif === 'diterima') ? 'lunas_dp' : 'belum_bayar';
            $upd_siswa = mysqli_prepare($conn, "UPDATE siswa SET status_pembayaran = ? WHERE id_siswa = ?");
            mysqli_stmt_bind_param($upd_siswa, "si", $new_siswa_status, $pay_data['id_siswa']);
            mysqli_stmt_execute($upd_siswa);
        }

        echo json_encode(['status' => 'success', 'message' => 'Status pembayaran berhasil diperbarui.']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui status: ' . $e->getMessage()]);
    }
    exit;

} else {
    echo json_encode(['status' => 'error', 'message' => 'Aksi tidak dikenali.']);
}
?>
