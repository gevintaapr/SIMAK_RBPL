<?php
$conn = mysqli_connect("localhost", "root", "", "simakhcts");
$res = mysqli_query($conn, "SELECT id_pembayaran, id_siswa FROM pembayaran LIMIT 5");
while($row = mysqli_fetch_assoc($res)) {
    print_r($row);
}
?>
