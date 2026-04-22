<?php
require_once 'c:/xampp/htdocs/SIMAK_RBPL/SIMAK_RBPL/config/config.php';

$tables = ['pembayaran', 'biaya_pendidikan'];
foreach ($tables as $table) {
    echo "\nStructure of table: $table\n";
    $result = mysqli_query($conn, "DESCRIBE $table");
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            print_r($row);
        }
    }
}
?>
