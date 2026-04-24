<?php
require_once 'config/config.php';
$result = mysqli_query($conn, "SHOW TABLES");
while ($row = mysqli_fetch_row($result)) {
    echo "Table: " . $row[0] . "\n";
    $cols = mysqli_query($conn, "SHOW COLUMNS FROM " . $row[0]);
    while ($col = mysqli_fetch_assoc($cols)) {
        echo "  - " . $col['Field'] . "\n";
    }
}
?>
