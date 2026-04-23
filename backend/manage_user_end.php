<?php
require_once __DIR__ . '/../config/config.php';

// Authentication check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 5) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit;
}

header('Content-Type: application/json');

$action = $_GET['action'] ?? ($_POST['action'] ?? null);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handling POST requests (Add, Edit, Delete)
    $input = json_decode(file_get_contents('php://input'), true);
    $action = $input['action'] ?? $action;

    switch ($action) {
        case 'add':
            $username = mysqli_real_escape_string($conn, $input['username']);
            $nama = mysqli_real_escape_string($conn, $input['nama']);
            $tgl_lahir = mysqli_real_escape_string($conn, $input['tgl_lahir']);
            $alamat = mysqli_real_escape_string($conn, $input['alamat']);
            $no_wa = mysqli_real_escape_string($conn, $input['no_wa']);
            $role_id = intval($input['role_id']);
            $spesialisasi = isset($input['spesialisasi']) ? mysqli_real_escape_string($conn, $input['spesialisasi']) : '';

            $email_raw = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $nama));
            $generated_email = $email_raw . rand(10,99) . '@hcts.ac.id';
            $generated_password = 'Hcts' . rand(1000, 9999);
            
            $password_hashed = password_hash($generated_password, PASSWORD_DEFAULT);
            $is_active = 1;
            $create_at = date('Y-m-d');

            mysqli_begin_transaction($conn);
            try {
                $query_user = "INSERT INTO user (username, email, password, role_id, is_active, create_at) 
                          VALUES ('$username', '$generated_email', '$password_hashed', $role_id, $is_active, '$create_at')";
                
                if (!mysqli_query($conn, $query_user)) {
                    throw new Exception("Gagal membuat user: " . mysqli_error($conn));
                }

                $id_user = mysqli_insert_id($conn);

                if ($role_id == 3) {
                    // Pengajar
                    $q_role = "INSERT INTO pengajar (id_user, nip_pengajar, nama_pengajar, tanggal_lahir, alamat, noWA, spesialisasi, email_pengajar, password, profil, update_at)
                               VALUES ($id_user, '$username', '$nama', '$tgl_lahir', '$alamat', '$no_wa', '$spesialisasi', '$generated_email', '$password_hashed', '', '$create_at')";
                } elseif ($role_id == 4) {
                    // Pimpinan
                    $q_role = "INSERT INTO pimpinan (id_user, nip_pimpinan, nama_pimpinan, tanggal_lahir, alamat, noWA, email_pimpinan, password, profil, update_at)
                               VALUES ($id_user, '$username', '$nama', '$tgl_lahir', '$alamat', '$no_wa', '$generated_email', '$password_hashed', '', '$create_at')";
                } elseif ($role_id == 5) {
                    // Admin
                    $q_role = "INSERT INTO admin (id_user, nip_admin, nama_admin, tanggal_lahir, alamat, noWA, email_admin, password, profil, update_at)
                               VALUES ($id_user, '$username', '$nama', '$tgl_lahir', '$alamat', '$no_wa', '$generated_email', '$password_hashed', '', '$create_at')";
                } else {
                    throw new Exception("Role tidak didukung melalui form ini.");
                }

                if (!mysqli_query($conn, $q_role)) {
                    throw new Exception("Gagal membuat profil pengguna: " . mysqli_error($conn));
                }

                mysqli_commit($conn);
                echo json_encode([
                    'status' => 'success', 
                    'message' => 'User added successfully.',
                    'email' => $generated_email,
                    'password' => $generated_password
                ]);

            } catch (Exception $e) {
                mysqli_rollback($conn);
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            }
            break;

        case 'edit':
            $id_user = intval($input['id_user']);
            $username = mysqli_real_escape_string($conn, $input['username']);
            $email = mysqli_real_escape_string($conn, $input['email']);
            $role_id = intval($input['role_id']);
            $is_active = intval($input['is_active']);

            $query = "UPDATE user SET username = '$username', email = '$email', role_id = $role_id, is_active = $is_active WHERE id_user = $id_user";
            
            if (mysqli_query($conn, $query)) {
                if (!empty($input['password'])) {
                    $password = password_hash($input['password'], PASSWORD_DEFAULT);
                    mysqli_query($conn, "UPDATE user SET password = '$password' WHERE id_user = $id_user");
                }
                echo json_encode(['status' => 'success', 'message' => 'User updated successfully.']);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
            }
            break;

        case 'delete':
            $id_user = intval($input['id_user']);
            // Check if user is deleting themselves
            if ($id_user == $_SESSION['user_id']) {
                echo json_encode(['status' => 'error', 'message' => 'You cannot delete your own account.']);
                exit;
            }
            $query = "DELETE FROM user WHERE id_user = $id_user";
            if (mysqli_query($conn, $query)) {
                echo json_encode(['status' => 'success', 'message' => 'User deleted successfully.']);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
            }
            break;

        default:
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
            break;
    }
} else {
    // Handling GET requests (Stats, List)
    switch ($action) {
        case 'stats':
            $total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM user"))['count'];
            $res = mysqli_query($conn, "SELECT r.name as role_name, COUNT(u.id_user) as jumlah 
                                        FROM roles r 
                                        LEFT JOIN user u ON r.id_role = u.role_id 
                                        GROUP BY r.id_role");
            $per_role = mysqli_fetch_all($res, MYSQLI_ASSOC);
            echo json_encode(['status' => 'success', 'data' => ['total' => $total, 'per_role' => $per_role]]);
            break;

        case 'list':
            $where = ["1=1"];
            if (!empty($_GET['role_id'])) $where[] = "u.role_id = " . intval($_GET['role_id']);
            if (isset($_GET['is_active']) && $_GET['is_active'] !== '') $where[] = "u.is_active = " . intval($_GET['is_active']);
            if (!empty($_GET['search'])) {
                $search = mysqli_real_escape_string($conn, $_GET['search']);
                $where[] = "(u.username LIKE '%$search%' OR u.email LIKE '%$search%')";
            }
            
            $where_clause = implode(" AND ", $where);
            $query = "SELECT u.*, r.name as role_name 
                      FROM user u 
                      JOIN roles r ON u.role_id = r.id_role 
                      WHERE $where_clause 
                      ORDER BY u.id_user DESC";
            
            $res = mysqli_query($conn, $query);
            $users = mysqli_fetch_all($res, MYSQLI_ASSOC);
            
            // Remove passwords from response
            foreach ($users as &$u) unset($u['password']);
            
            echo json_encode(['status' => 'success', 'data' => $users]);
            break;

        default:
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
            break;
    }
}
?>
