<?php

header("Access-Control-Allow-Origin: localhost");
header("Content-Type: application/json; charset=UTF-8");

session_start();


if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    http_response_code(401); 
    echo json_encode(["message" => "Access denied."]);
    exit();
}

include_once '../spidermandbconnection.php';
include_once '../ActivityLogger.php'; 

try {
    $pdo = db_connection("localhost", "spiderman", "root", "");
    
    $logger = new ActivityLogger($pdo);
    
    
    
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 100;
    
    $logs = $logger->getAllActivities($limit);

    http_response_code(200);
    echo json_encode([
        "count" => count($logs),
        "data" => $logs
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>