<?php




header("Access-Control-Allow-Origin: *");

header("Content-Type: application/json; charset=UTF-8");



require_once '../spidermandbconnection.php';

try {
    $pdo = db_connection("localhost", "spiderman", "root", "");

    
    if (isset($_GET['id'])) {
        
        $stmt = $pdo->prepare("SELECT * FROM movies WHERE ID = ?");
        $stmt->execute([$_GET['id']]);
        $movie = $stmt->fetch();

        if ($movie) {
            http_response_code(200); 
            echo json_encode($movie);
        } else {
            http_response_code(404); 
            echo json_encode(["message" => "Movie not found."]);
        }
    } 
    
    else {
        $stmt = $pdo->query("SELECT * FROM movies ORDER BY release_year DESC");
        $movies = $stmt->fetchAll();

        
        http_response_code(200);
        echo json_encode([
            "count" => count($movies),
            "data" => $movies
        ]);
    }

} catch (PDOException $e) {
    
    http_response_code(500); 
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
?>