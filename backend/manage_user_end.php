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
            $email = mysqli_real_escape_string($conn, $input['email']);
            $password = password_hash($input['password'], PASSWORD_DEFAULT);
            $role_id = intval($input['role_id']);
            $is_active = 1;
            $create_at = date('Y-m-d');

            $query = "INSERT INTO user (username, email, password, role_id, is_active, create_at) 
                      VALUES ('$username', '$email', '$password', $role_id, $is_active, '$create_at')";
            
            if (mysqli_query($conn, $query)) {
                echo json_encode(['status' => 'success', 'message' => 'User added successfully.']);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
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
