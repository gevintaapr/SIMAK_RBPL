<?php
require_once 'c:/xampp/htdocs/SIMAK_RBPL/SIMAK_RBPL/config/config.php';

$res = mysqli_query($conn, "SELECT * FROM user WHERE id_user = 111");
print_r(mysqli_fetch_assoc($res));
?>
