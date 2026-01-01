<?php
function db_connection($host, $dbname, $username, $password) {
    try {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

function selection($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM movies ORDER BY release_year");
        return $stmt->fetchAll();
    } catch(PDOException $e) {
        die("Query failed: " . $e->getMessage());
        return [];
    }
}

function vall_selection($pdo) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM villains ORDER BY id");
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error fetching villains: " . $e->getMessage());
        return [];
    }
}

function mcu_selection($pdo) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM mcu ORDER BY release_year");
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error fetching MCU: " . $e->getMessage());
        return [];
    }
}

function cast_selection($pdo) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM spiderman_cast  ORDER BY universe");
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error fetching MCU: " . $e->getMessage());
        return [];
    }
}

function insert_contact_message($pdo, $name, $email, $message) {
    try {
        $stmt = $pdo->prepare("INSERT INTO contact_us (name, email, message) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $message]);
        return true;
    } catch (PDOException $e) {
        error_log("Error inserting contact message: " . $e->getMessage());
        return false;
    }
}

function get_all_contact_messages($pdo) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM contact_us ORDER BY submission_date DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error fetching contact messages: " . $e->getMessage());
        return [];
    }
}

function get_gallery_items($pdo, $category = 'all') {
    try {
        if ($category === 'all') {
            $stmt = $pdo->prepare("SELECT * FROM gallery ORDER BY uploaded_at DESC");
            $stmt->execute();
        } else {
            $stmt = $pdo->prepare("SELECT * FROM gallery WHERE category = ? ORDER BY uploaded_at DESC");
            $stmt->execute([$category]);
        }
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error fetching gallery: " . $e->getMessage());
        return [];
    }
}

function add_gallery_item($pdo, $image_path, $title, $category, $description) {
    try {
        $stmt = $pdo->prepare("INSERT INTO gallery (image_path, title, category, description) VALUES (?, ?, ?, ?)");
        $stmt->execute([$image_path, $title, $category, $description]);
        return true;
    } catch (PDOException $e) {
        error_log("Error adding gallery item: " . $e->getMessage());
        return false;
    }
}
?>