<?php
require_once 'c:/xampp/htdocs/SIMAK_RBPL/SIMAK_RBPL/config/config.php';
$res = mysqli_query($conn, "DESCRIBE user");
while($row = mysqli_fetch_assoc($res)) {
    print_r($row);
}
?>
