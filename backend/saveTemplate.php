<?php
session_start();
require_once __DIR__ . '/../config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 5) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = mysqli_real_escape_string($conn, $_POST['code']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $subtitle = mysqli_real_escape_string($conn, $_POST['subtitle']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    
    $field_labels = $_POST['field_labels'] ?? [];
    $field_placeholders = $_POST['field_placeholders'] ?? [];
    
    $fields = [];
    for ($i = 0; $i < count($field_labels); $i++) {
        if (!empty($field_labels[$i])) {
            $fields[] = [
                'label' => $field_labels[$i],
                'placeholder' => $field_placeholders[$i]
            ];
        }
    }
    
    $fields_json = mysqli_real_escape_string($conn, json_encode($fields));
    
    $query = "UPDATE templates SET 
              title = '$title', 
              subtitle = '$subtitle', 
              content = '$content', 
              fields = '$fields_json' 
              WHERE code = '$code'";
              
    if (mysqli_query($conn, $query)) {
        echo json_encode(['status' => 'success', 'message' => 'Template berhasil diperbarui']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui template: ' . mysqli_error($conn)]);
    }
}
?>
