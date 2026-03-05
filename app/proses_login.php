<?php
require_once __DIR__ . '/../config/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../public/MainLogin.php');
    exit;
}

$login_input = trim($_POST['login_input'] ?? ''); // bisa username atau email
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? '';

// validasi input kosong
if ($login_input === '' || $password === '') {

    switch ($role) {
        case '1':
            $redirect = '../public/login/logSiswa.php';
            break;
        case '2':
            $redirect = '../public/login/logCalonSiswa.php';
            break;
        case '3':
            $redirect = '../public/login/logPengajar.php';
            break;
        case '4':
            $redirect = '../public/login/logPimpinan.php';
            break;
        case '5':
            $redirect = '../public/login/logPimpinan.php';
        default:
            $redirect = '../public/MainLogin.php';
    }

        header('Location: ' . $redirect . '?error=' . urlencode('Wajib mengisi semua field'));
    exit;
}

    $query = "SELECT id_user, username, email, password, role_id FROM user WHERE username = ? LIMIT 1";

$stmt = mysqli_prepare($conn, $query);
if (!$stmt) {
    header('Location: ../public/MainLogin.php?error=' . urlencode('Server error.'));
    exit;
}

mysqli_stmt_bind_param($stmt, 's', $login_input);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// cek user ditemukan dan password cocok
if ($user && $password === $user['password']) {
    // simpan ke session
    $_SESSION['user_id'] = $user['id_user'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role_id'];

    if ($user['role_id'] == 1) {
        header('Location: ../user/siswa/dashboard_siswa.php');
    } elseif ($user['role_id'] == 2) {
        header('Location: ../user/dashboards_calon_siswa.php');
    } elseif ($user['role_id'] == 3) {
        header('Location: ../user/pengajar/dashboard_pengajar.php');
    } elseif ($user['role_id'] == 4) {
        header('Location: ../user/pimpinan/dashboard_pimpinan.php');
    } elseif ($user['role_id'] == 5) {
        header('Location: ../user/admin/dashboard_admin.php');
    } else { 
        header('Location: ../public/MainLogin.php?error=' . urlencode('Role tidak dikenali.'));
    }
    exit;

} else {
    header('Location: ../public/MainLogin.php?error=' . urlencode('Invalid username/email or password.'));
    exit;
}
?>
