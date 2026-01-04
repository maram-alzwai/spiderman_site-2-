<?php
session_start();
require_once 'spidermandbconnection.php';

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized. Please login.']);
    exit();
}

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'User not recognized.']);
    exit();
}

$username = $_SESSION['username'];
$pdo = db_connection("localhost", "spiderman", "root", "");


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatar'])) {
    $upload_dir = 'images/avatars/';
    
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    $file = $_FILES['avatar'];
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo json_encode([
            'success' => false, 
            'message' => 'Upload error: ' . $file['error']
        ]);
        exit();
    }
    
    $max_size = 5 * 1024 * 1024;
    if ($file['size'] > $max_size) {
        echo json_encode([
            'success' => false, 
            'message' => 'File is too large. Maximum size is 5MB.'
        ]);
        exit();
    }
    
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    if (!in_array($file_extension, $allowed_extensions)) {
        echo json_encode([
            'success' => false, 
            'message' => 'File type not supported. Please upload an image (JPEG, PNG, GIF, WebP).'
        ]);
        exit();
    }
    
    $new_filename = 'avatar_' . $username . '_' . time() . '.' . $file_extension;
    $upload_path = $upload_dir . $new_filename;
    
    $stmt = $pdo->prepare("SELECT avatar FROM profile WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $old_avatar = $result['avatar'] ?? '';
    
    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
        
                $update_stmt = $pdo->prepare("UPDATE profile SET avatar = :avatar WHERE username = :username");
        $update_success = $update_stmt->execute([
            'avatar' => $upload_path,
            'username' => $username
        ]);
        
        if ($update_success) {
                if (!empty($old_avatar) && 
                $old_avatar !== 'images/avatars/default.png' && 
                file_exists($old_avatar)) {
                @unlink($old_avatar);
            }
            
            echo json_encode([
                'success' => true,
                'message' => 'Profile picture updated successfully!',
                'new_avatar' => $upload_path
            ]);
        } else {
            @unlink($upload_path);
            echo json_encode([
                'success' => false, 
                'message' => 'Failed to update database.'
            ]);
        }
        
    } else {
        echo json_encode([
            'success' => false, 
            'message' => 'Failed to upload file.'
        ]);
    }
    
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Invalid request.'
    ]);
}
?>