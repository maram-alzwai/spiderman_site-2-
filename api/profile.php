<?php




header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

session_start(); 



if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    http_response_code(401); 
    echo json_encode(["success" => false, "message" => "Access denied. You must be logged in."]);
    exit();
}



require_once '../spidermandbconnection.php';
require_once '../ProfileManager.php';

try {
    $pdo = db_connection("localhost", "spiderman", "root", "");
    
    
    
    $username = $_SESSION['username'];
    
    
    $manager = new ProfileManager($pdo, $username);
    
    
    $userData = $manager->getUserData();

    if ($userData) {
        
        http_response_code(200);
        echo json_encode([
            "success" => true,
            "data" => $userData
        ]);
    } else {
        
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "Profile not found."]);
    }

} catch (Exception $e) {
    http_response_code(500); 
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
?>