<?php
require_once 'c:/xampp/htdocs/SIMAK_RBPL/SIMAK_RBPL/config/config.php';

// Pastikan table siswa menggunakan id_program
$check = mysqli_query($conn, "SHOW COLUMNS FROM siswa LIKE 'program_pembelajaran'");
if (mysqli_num_rows($check) > 0) {
    mysqli_query($conn, "ALTER TABLE siswa DROP COLUMN program_pembelajaran");
    echo "Kolom program_pembelajaran dihapus dari tabel siswa.\n";
}

// Kolom id_program sudah ada di tabel siswa (berdasarkan DESCRIBE sebelumnya), 
// tapi kita pastikan foreign key-nya ada.
$sql_fk = "ALTER TABLE siswa ADD CONSTRAINT fk_siswa_program FOREIGN KEY (id_program) REFERENCES program(id_program)";
mysqli_query($conn, $sql_fk);

echo "Sinkronisasi tabel siswa selesai.\n";
?>
