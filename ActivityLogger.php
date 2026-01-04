<?php

class ActivityLogger {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function logActivity($userId, $username, $activityType, $description, $itemId = null, $itemType = null) {
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        $sql = "INSERT INTO activity_logs (user_id, username, activity_type, description, item_id, item_type, ip_address, user_agent) 
                VALUES (:user_id, :username, :activity_type, :description, :item_id, :item_type, :ip_address, :user_agent)";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'user_id' => $userId,
            'username' => $username,
            'activity_type' => $activityType,
            'description' => $description,
            'item_id' => $itemId,
            'item_type' => $itemType,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent
        ]);
        
        return true;
    }
    
    public function getUserActivities($userId, $limit = 50) {
        $sql = "SELECT * FROM activity_logs 
                WHERE user_id = :user_id 
                ORDER BY created_at DESC 
                LIMIT :limit";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAllActivities($limit = 100) {
        $sql = "SELECT al.*, p.full_name, p.avatar 
                FROM activity_logs al
                LEFT JOIN profile p ON al.user_id = p.id
                ORDER BY al.created_at DESC 
                LIMIT :limit";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>