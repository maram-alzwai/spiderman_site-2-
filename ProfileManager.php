<?php

class ProfileManager {
    private $pdo;
    private $username;
    
    public function __construct($pdo, $username) {
        $this->pdo = $pdo;
        $this->username = $username;
    }
    
    public function getUserData() {
        try {
            $stmt = $this->pdo->prepare("
                SELECT id, username, full_name AS name, email, avatar, bio, 
                       join_date, last_login, role
                FROM profile
                WHERE username = :username
                LIMIT 1
            ");
            $stmt->execute([':username' => $this->username]);
            $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $user_data ?: $this->getDefaultData();
            
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    
    private function getDefaultData() {
        return [
            'name' => ucfirst($this->username),
            'email' => $this->username . '@spiderman.com',
            'avatar' => DEFAULT_AVATAR,
            'role' => 'User',
            'join_date' => date('Y-m-d'),
            'last_login' => date('Y-m-d H:i:s'),
            'bio' => 'Spider-Man website user.'
        ];
    }
    
    public function updateProfile($data) {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE profile 
                SET full_name = :full_name, 
                    email = :email, 
                    bio = :bio,
                    updated_at = NOW()
                WHERE username = :username
            ");
            
            return $stmt->execute([
                ':full_name' => $data['name'],
                ':email' => $data['email'],
                ':bio' => $data['bio'],
                ':username' => $this->username
            ]);
            
        } catch (PDOException $e) {
            throw new Exception("Update failed: " . $e->getMessage());
        }
    }
    
    public function getAccountAge() {
        $user_data = $this->getUserData();
        $join = new DateTime($user_data['join_date']);
        $now = new DateTime();
        $diff = $now->diff($join);
        
        if ($diff->y > 0) {
            return $diff->y . ' year' . ($diff->y > 1 ? 's' : '');
        } elseif ($diff->m > 0) {
            return $diff->m . ' month' . ($diff->m > 1 ? 's' : '');
        } else {
            return $diff->d . ' day' . ($diff->d > 1 ? 's' : '');
        }
    }
}
?>