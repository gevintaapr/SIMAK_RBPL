<?php
$conn = mysqli_connect("localhost", "root", "", "simakhcts");
$res = mysqli_query($conn, "DESCRIBE pembayaran");
while($row = mysqli_fetch_assoc($res)) {
    echo $row['Field'] . "\n";
}
?>
