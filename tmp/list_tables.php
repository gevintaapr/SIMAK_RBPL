<?php
require_once 'c:/xampp/htdocs/SIMAK_RBPL/SIMAK_RBPL/config/config.php';

$result = mysqli_query($conn, "SHOW TABLES");
while ($row = mysqli_fetch_row($result)) {
    echo $row[0] . "\n";
}
?>
