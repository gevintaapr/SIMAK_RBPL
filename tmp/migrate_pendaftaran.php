<?php
require_once 'c:/xampp/htdocs/SIMAK_RBPL/SIMAK_RBPL/config/config.php';

// 1. Tambah kolom id_program ke tabel pendaftaran
$sql_add = "ALTER TABLE pendaftaran ADD COLUMN id_program INT(11) AFTER id_user";
if (mysqli_query($conn, $sql_add)) {
    echo "Kolom id_program berhasil ditambahkan ke pendaftaran.\n";
} else {
    echo "Gagal menambah kolom (mungkin sudah ada): " . mysqli_error($conn) . "\n";
}

// 2. Set foreign key (optional but recommended)
$sql_fk = "ALTER TABLE pendaftaran ADD CONSTRAINT fk_pendaftaran_program FOREIGN KEY (id_program) REFERENCES program(id_program)";
mysqli_query($conn, $sql_fk);

// 3. Hapus kolom program lama
$sql_drop = "ALTER TABLE pendaftaran DROP COLUMN program";
if (mysqli_query($conn, $sql_drop)) {
    echo "Kolom program lama berhasil dihapus.\n";
} else {
    echo "Gagal menghapus kolom program: " . mysqli_error($conn) . "\n";
}
?>
