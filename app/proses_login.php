<?php
require_once __DIR__ . '/../config/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../public/MainLogin.php');
    exit;
}

$login_input = trim($_POST['login_input'] ?? ''); 
$password = $_POST['password'] ?? '';
$role_from_post = $_POST['role'] ?? '';

$redirect = match($role_from_post) {
    '1' => '../public/login/logSiswa.php',
    '2' => '../public/login/logCalonSiswa.php',
    '3' => '../public/login/logPengajar.php',
    '4', '5' => '../public/login/logPimpinan.php',
    default => '../public/MainLogin.php',
};

// --- 1. Validasi Input Kosong ---
if ($login_input === '' || $password === '') {
    header('Location: ' . $redirect . '?error=' . urlencode('Wajib mengisi semua field'));
    exit;
}

// --- 2. Logika Calon Siswa (Role 2) ---
if ($role_from_post === '2') {
    $query = "SELECT * FROM pendaftaran WHERE id_pendaftaran = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $login_input);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($user && $user['token_akses'] === $password) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id_pendaftaran'];
        $_SESSION['username'] = $user['nama_cs'];
        $_SESSION['role'] = 2;
        header('Location: ../user/siswa/dashboard_calon_siswa.php');
        exit;
    } else {
        header('Location: ' . $redirect . '?error=' . urlencode('No. Pendaftaran atau Token Akses salah.'));
        exit;
    }
}

// --- 3. Logika untuk Role Lain (Tabel User) ---
if (filter_var($login_input, FILTER_VALIDATE_EMAIL)) {
    $query = "SELECT id_user, username, email, password, role_id FROM user WHERE email = ? AND role_id = 1 LIMIT 1";
} else {
    $query = "SELECT id_user, username, email, password, role_id FROM user WHERE username = ? LIMIT 1";
}

$stmt = mysqli_prepare($conn, $query);
if (!$stmt) {
    header('Location: ../public/MainLogin.php?error=' . urlencode('Server error database.'));
    exit;
}

mysqli_stmt_bind_param($stmt, 's', $login_input);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if ($user && password_verify($password, $user['password'])) {
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id_user'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role_id'];

    switch ($user['role_id']) {
        case 1:
            header('Location: ../user/siswa/dashboard_siswa.php');
            break;
        case 3:
            header('Location: ../user/pengajar/dashboard_pengajar.php');
            break;
        case 4:
            header('Location: ../user/pimpinan/dashboard_pimpinan.php');
            break;
        case 5:
            header('Location: ../user/admin/dashboard_admin.php');
            break;
        default: 
            header('Location: ../public/MainLogin.php?error=' . urlencode('Role tidak terdaftar.'));
            break;
    }
    exit;
} else {
    header('Location: ' . $redirect . '?error=' . urlencode('Username/Email atau Password salah.'));
    exit;
}
?>