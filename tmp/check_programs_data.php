<?php
require_once 'c:/xampp/htdocs/SIMAK_RBPL/SIMAK_RBPL/config/config.php';

echo "Data di tabel Program:\n";
$query = mysqli_query($conn, "SELECT * FROM program");
while($row = mysqli_fetch_assoc($query)) {
    print_r($row);
}
?>
