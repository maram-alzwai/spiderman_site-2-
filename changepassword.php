<?php
session_start();
require_once 'spidermandbconnection.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

error_log("SESSION DATA: " . print_r($_SESSION, true));

$pdo = db_connection("localhost", "spiderman", "root", "");

if (!isset($_SESSION['username']) && !isset($_SESSION['user_logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in. Session: ' . session_id()]);
    exit();
}

$username = $_SESSION['username'] ?? '';

if (empty($username)) {
    echo json_encode(['success' => false, 'message' => 'Username not found in session']);
    exit();
}

$currentPassword = $_POST['currentPassword'] ?? '';
$newPassword = $_POST['newPassword'] ?? '';

if (empty($currentPassword) || empty($newPassword)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit();
}

if (strlen($newPassword) < 6) {
    echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters']);
    exit();
}

try {
    $stmt = $pdo->prepare("
        SELECT current_password 
        FROM spider_passwords 
        WHERE spider_name = :username
        LIMIT 1
    ");
    
    $stmt->execute(['username' => $username]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo json_encode(['success' => false, 'message' => 'User password record not found']);
        exit();
    }

    if (!password_verify($currentPassword, $row['current_password'])) {
        echo json_encode(['success' => false, 'message' => 'Current password is incorrect']);
        exit();
    }

    $newHashed = password_hash($newPassword, PASSWORD_DEFAULT);

    $update = $pdo->prepare("
        UPDATE spider_passwords 
        SET current_password = :pass,
            last_updated = NOW()
        WHERE spider_name = :username
    ");
    
    $result = $update->execute([
        'pass' => $newHashed,
        'username' => $username
    ]);

    if ($result) {
        
        $updateProfile = $pdo->prepare("
            UPDATE profile 
            SET last_login = NOW()
            WHERE username = :username
        ");
        $updateProfile->execute(['username' => $username]);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Password updated successfully!'
        ]);
    } else {
        echo json_encode([
            'success' => false, 
            'message' => 'Failed to update password'
        ]);
    }

} catch (PDOException $e) {
    echo json_encode([
        'success' => false, 
        'message' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false, 
        'message' => 'Error: ' . $e->getMessage()
    ]);
}

exit();
?>