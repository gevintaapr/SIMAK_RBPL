<?php
$conn = mysqli_connect("localhost", "root", "", "simakhcts");
$res = mysqli_query($conn, "SELECT id_siswa, id_user, nama_lengkap FROM siswa LIMIT 5");
while($row = mysqli_fetch_assoc($res)) {
    print_r($row);
}
?>
