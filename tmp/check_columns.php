<?php
require_once 'config/config.php';
$result = mysqli_query($conn, "SHOW COLUMNS FROM pendaftaran");
while ($row = mysqli_fetch_assoc($result)) {
    echo $row['Field'] . "\n";
}
?>
