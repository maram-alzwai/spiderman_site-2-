<?php
session_start();
require_once 'spidermandbconnection.php';


$my_pdo = db_connection("localhost", "spiderman", "root", "");


$name = $email = $message = '';
$errors = [];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    
    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(trim($_POST['message'] ?? ''));
    
    
    if (empty($name)) {
        $errors[] = "Name is required";
    } elseif (strlen($name) < 2) {
        $errors[] = "Name must be at least 2 characters";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    if (empty($message)) {
        $errors[] = "Message is required";
    } elseif (strlen($message) < 10) {
        $errors[] = "Message must be at least 10 characters";
    }
    
    
    if (empty($errors)) {
        if ($my_pdo) {
            try {
                $stmt = $my_pdo->prepare("INSERT INTO contact_us (name, email, message) VALUES (?, ?, ?)");
                $insert_result = $stmt->execute([$name, $email, $message]);
                
                if ($insert_result) {
                    
                    $_SESSION['form_success'] = true;
                    $_SESSION['submitted_name'] = $name;
                    
                    
                    $name = $email = $message = '';
                    
                    
                    header("Location: php.php#contact");
                    exit();
                } else {
                    $errors[] = "Failed to save your message. Please try again.";
                }
            } catch (PDOException $e) {
                $errors[] = "Database error: " . $e->getMessage();
            }
        } else {
            $errors[] = "Database connection failed. Please try again later.";
        }
    }
}


if (!empty($errors)) {
    $_SESSION['contact_errors'] = $errors;
    $_SESSION['contact_name'] = $name;
    $_SESSION['contact_email'] = $email;
    $_SESSION['contact_message'] = $message;
    header("Location: php.php#contact");
    exit();
}
?>