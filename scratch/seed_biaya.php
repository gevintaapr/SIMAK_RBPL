<?php
$conn = mysqli_connect("localhost", "root", "", "simakhcts");

// Insert default fees if table is empty
$check = mysqli_query($conn, "SELECT COUNT(*) as count FROM biaya_pendidikan");
$row = mysqli_fetch_assoc($check);

if ($row['count'] == 0) {
    mysqli_query($conn, "INSERT INTO biaya_pendidikan (nama_bp, nominal) VALUES 
        ('Pendaftaran & Seleksi', 500000),
        ('Sertifikasi OJT', 1500000),
        ('Biaya Administrasi', 250000),
        ('Pelunasan Program Hotel', 5000000),
        ('Pelunasan Program Cruise Ship', 7500000)");
    echo "Default biaya_pendidikan inserted.\n";
} else {
    echo "biaya_pendidikan already has data.\n";
}
?>
