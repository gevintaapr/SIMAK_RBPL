<?php
$conn = mysqli_connect("localhost", "root", "", "simakhcts");

// Try to rename column
mysqli_query($conn, "ALTER TABLE pembayaran CHANGE COLUMN bukti_pembayaran bukti_file VARCHAR(255)");

// Try to add columns
mysqli_query($conn, "ALTER TABLE pembayaran ADD COLUMN nominal INT AFTER id_biaya_pendidikan");
mysqli_query($conn, "ALTER TABLE pembayaran ADD COLUMN keterangan TEXT AFTER status_pembayaran");

echo "Done.\n";
?>
