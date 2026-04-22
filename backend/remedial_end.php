<?php
require_once '../config/config.php';
header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

if ($action === 'ajukan_remedial') {
    // Siswa mengajukan remedial (Bisa multiple)
    $id_siswa = $_POST['id_siswa'];
    $id_evaluasi = $_POST['id_evaluasi'];
    $mapel_list = $_POST['mapel_kode'] ?? []; // Ini akan jadi array
    $alasan = mysqli_real_escape_string($conn, $_POST['alasan'] ?? '');

    if (empty($mapel_list)) {
        echo json_encode(['status' => 'error', 'message' => 'Pilih minimal satu mata pelajaran.']);
        exit;
    }

    $success_count = 0;
    foreach ($mapel_list as $mapel_info) {
        // mapel_info dikirim dalam format "KODE|NILAI"
        $parts = explode('|', $mapel_info);
        $kode = $parts[0];
        $nilai_lama = $parts[1];

        $sql = "INSERT INTO pengajuan_remedial (id_siswa, id_evaluasi, mapel_kode, alasan, nilai_lama, status_remedial) 
                VALUES ('$id_siswa', '$id_evaluasi', '$kode', '$alasan', '$nilai_lama', 'pending')";
        
        if (mysqli_query($conn, $sql)) {
            $success_count++;
        }
    }

    if ($success_count > 0) {
        echo json_encode(['status' => 'success', 'message' => "$success_count pengajuan remedial berhasil dikirim."]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal mengirim pengajuan.']);
    }
} 

elseif ($action === 'update_nilai_remedial') {
    // Pengajar mengupdate nilai setelah remedial selesai
    $id_remedial = $_POST['id_remedial'];
    $nilai_baru = (int)$_POST['nilai_baru'];
    $catatan = mysqli_real_escape_string($conn, $_POST['catatan'] ?? '');

    // 1. Ambil info pengajuan
    $query_rem = mysqli_query($conn, "SELECT * FROM pengajuan_remedial WHERE id_remedial = $id_remedial");
    $data_rem = mysqli_fetch_assoc($query_rem);
    
    if ($data_rem) {
        $id_eval = $data_rem['id_evaluasi'];
        $mapel_kode = $data_rem['mapel_kode'];
        $id_siswa = $data_rem['id_siswa'];

        // 2. Update nilai di tabel evaluasi (Kolom DUIx)
        $sql_upd_eval = "UPDATE evaluasi SET $mapel_kode = $nilai_baru WHERE id_evaluasi = $id_eval";
        mysqli_query($conn, $sql_upd_eval);

        // 3. Hitung Ulang Rata-rata & Status di tabel evaluasi
        $query_new_eval = mysqli_query($conn, "SELECT * FROM evaluasi WHERE id_evaluasi = $id_eval");
        $eval = mysqli_fetch_assoc($query_new_eval);
        
        $total = $eval['DUI1'] + $eval['DUI2'] + $eval['DUI3'] + $eval['DUI4'] + $eval['DUI5'] + $eval['DUI6'] + $eval['DUI7'] + $eval['DUI8'];
        $avg = $total / 8;
        
        $remedial_count = 0;
        foreach(['DUI1','DUI2','DUI3','DUI4','DUI5','DUI6','DUI7','DUI8'] as $key) {
            if ($eval[$key] < 80) $remedial_count++;
        }
        
        $new_status = ($avg >= 80 && $remedial_count <= 1) ? 'Lulus' : 'Tidak Lulus';
        
        // Update Ringkasan Evaluasi
        mysqli_query($conn, "UPDATE evaluasi SET rata_rata = $avg, status_kelulusan = '$new_status' WHERE id_evaluasi = $id_eval");

        // 4. Tandai pengajuan remedial sebagai Selesai
        mysqli_query($conn, "UPDATE pengajuan_remedial SET nilai_baru = $nilai_baru, catatan_pengajar = '$catatan', status_remedial = 'selesai' WHERE id_remedial = $id_remedial");

        echo json_encode(['status' => 'success', 'message' => 'Nilai berhasil diperbarui. Status kelulusan siswa telah dihitung ulang.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Data remedial tidak ditemukan.']);
    }
}
?>
