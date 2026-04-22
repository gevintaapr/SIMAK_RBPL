<?php
require_once 'c:/xampp/htdocs/SIMAK_RBPL/SIMAK_RBPL/config/config.php';

$username = '124240114';
$password = 'inicoba';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$query = "UPDATE user SET password = ? WHERE username = ?";
$stmt = mysqli_prepare($conn, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, 'ss', $hashed_password, $username);
    if (mysqli_stmt_execute($stmt)) {
        echo "Successfully updated password for user: $username\n";
    } else {
        echo "Error updating password: " . mysqli_error($conn) . "\n";
    }
    mysqli_stmt_close($stmt);
} else {
    echo "Error preparing statement: " . mysqli_error($conn) . "\n";
}
?>
