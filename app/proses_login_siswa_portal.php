<?php
session_start();
require_once __DIR__ . '/../config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../public/login/logSiswa.php');
    exit;
}

$login_input = trim($_POST['login_input'] ?? '');
$password    = $_POST['password'] ?? '';

if ($login_input === '' || $password === '') {
    header('Location: ../public/login/logSiswa.php?error=' . urlencode('Wajib mengisi semua field'));
    exit;
}

// Query by email or username, must be role_id = 1 (siswa)
if (filter_var($login_input, FILTER_VALIDATE_EMAIL)) {
    $stmt = mysqli_prepare($conn, "SELECT id_user, username, email, password, role_id FROM user WHERE email = ? AND role_id = 1 LIMIT 1");
} else {
    $stmt = mysqli_prepare($conn, "SELECT id_user, username, email, password, role_id FROM user WHERE username = ? AND role_id = 1 LIMIT 1");
}

if (!$stmt) {
    header('Location: ../public/login/logSiswa.php?error=' . urlencode('Server error.'));
    exit;
}

mysqli_stmt_bind_param($stmt, 's', $login_input);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user   = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if ($user && password_verify($password, $user['password'])) {
    session_regenerate_id(true);
    $_SESSION['user_id']  = $user['id_user'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role']     = $user['role_id']; // int 1
    header('Location: ../user/siswa/dashboard_siswa.php');
    exit;
} else {
    header('Location: ../public/login/logSiswa.php?error=' . urlencode('Username/Email atau Password salah.'));
    exit;
}
?>
