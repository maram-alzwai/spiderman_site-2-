<?php


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../spidermandbconnection.php';

try {
    $pdo = db_connection("localhost", "spiderman", "root", "");

    
    if (isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT * FROM villains WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $villain = $stmt->fetch();

        if ($villain) {
            http_response_code(200);
            echo json_encode($villain);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Villain not found."]);
        }
    } 
    
    else {
        $stmt = $pdo->query("SELECT * FROM villains ORDER BY id ASC");
        $villains = $stmt->fetchAll();

        http_response_code(200);
        echo json_encode([
            "count" => count($villains),
            "data" => $villains
        ]);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
?>