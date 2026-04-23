<?php
$conn = mysqli_connect("localhost", "root", "", "simakhcts");
$sql = "ALTER TABLE pembayaran 
        ADD COLUMN IF NOT EXISTS nominal INT AFTER id_biaya_pendidikan, 
        ADD COLUMN IF NOT EXISTS keterangan TEXT AFTER status_pembayaran, 
        CHANGE COLUMN bukti_pembayaran bukti_file VARCHAR(255)";
if (mysqli_query($conn, $sql)) {
    echo "Table 'pembayaran' updated successfully\n";
} else {
    echo "Error updating table: " . mysqli_error($conn) . "\n";
}

$sql_bp = "SELECT * FROM biaya_pendidikan";
$res = mysqli_query($conn, $sql_bp);
if (!$res) {
    // If table doesn't exist or is empty, maybe create it or insert dummy
    echo "Checking biaya_pendidikan...\n";
}
?>
