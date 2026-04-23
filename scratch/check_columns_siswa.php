<?php
$conn = mysqli_connect("localhost", "root", "", "simakhcts");
$res = mysqli_query($conn, "DESCRIBE siswa");
while($row = mysqli_fetch_assoc($res)) {
    echo $row['Field'] . "\n";
}
?>
