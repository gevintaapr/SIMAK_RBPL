<?php
require_once 'c:/xampp/htdocs/SIMAK_RBPL/SIMAK_RBPL/config/config.php';

$username = '124240114';
$query = "SELECT * FROM user WHERE username = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 's', $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if ($user) {
    echo "User Found:\n";
    print_r($user);
    $test_pass = 'inicoba';
    if (password_verify($test_pass, $user['password'])) {
        echo "Password Verify: SUCCESS\n";
    } else {
        echo "Password Verify: FAILED\n";
    }
} else {
    echo "User $username NOT found in database.\n";
}
?>
