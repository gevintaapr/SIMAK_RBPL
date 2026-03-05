<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function require_auth($allowed_roles = []) {
    if (!isset($_SESSION['role'])) {
        header('Location: /php/public/login.php');
        exit();
    }

    $user_id = $_SESSION['id_user'];
    $user_role = $_SESSION['role_id'];
    $username = $_SESSION['username'];

    if (!empty($allowed_roles)){
        if (!in_array($user_role, $allowed_roles)) {
            if ($user_role === 'admin') {
                header('Location: /dashboard/admin/index.php'); 
            } else if ($user_role === 'leader') {
                header('Location: /php/dashboard/editor/index.php'); 
            } else if ($user_role === 'user') {
                header('Location: /dashboard/user/index.php'); 
            }
             exit();
        }
    }

    return [
        'id' => $user_id,
        'role' => $user_role,
        'username' => $username
    ];
}

?>