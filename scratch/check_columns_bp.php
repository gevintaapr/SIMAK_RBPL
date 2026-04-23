<?php
$conn = mysqli_connect("localhost", "root", "", "simakhcts");
$res = mysqli_query($conn, "DESCRIBE biaya_pendidikan");
while($row = mysqli_fetch_assoc($res)) {
    echo $row['Field'] . "\n";
}
?>
