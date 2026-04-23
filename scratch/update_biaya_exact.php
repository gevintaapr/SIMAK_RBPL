<?php
$conn = mysqli_connect("localhost", "root", "", "simakhcts");

// Clear and reset biaya_pendidikan for the new specific requirements
mysqli_query($conn, "TRUNCATE TABLE biaya_pendidikan");
mysqli_query($conn, "INSERT INTO biaya_pendidikan (nama_bp, nominal) VALUES 
    ('DP Pertama', 5000000),
    ('Pelunasan', 10000000)");

echo "biaya_pendidikan updated with DP Pertama and Pelunasan.\n";
?>
