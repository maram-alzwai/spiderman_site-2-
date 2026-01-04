<?php
session_start();


if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header("Location: php.php");
    exit();
}

require_once 'spidermandbconnection.php';

$error_message = "";
$success_message = "";
$username = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $password = $_POST['password']; 
    $remember_me = isset($_POST['remember_me']);

    if (empty($username) || empty($password)) {
        $error_message = "Please enter both username and password";
    } else {

        $pdo = db_connection("localhost", "spiderman", "root", "");

        if ($pdo) {
            try {

                $stmt = $pdo->prepare("SELECT * FROM spider_passwords WHERE spider_name = ?");
                $stmt->execute([$username]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                    
                    if (password_verify($password, $user['current_password'])) {

                        
                        session_regenerate_id(true);

                        
                        $_SESSION['user_logged_in'] = true;
                        $_SESSION['username'] = $username;
                        $_SESSION['user_id'] = $user['id']; 
                        $_SESSION['login_time'] = time();

                        
                        
                        if (file_exists('ActivityLogger.php')) {
                            require_once 'ActivityLogger.php';
                            $logger = new ActivityLogger($pdo);
                            
                            $logger->logActivity($user['id'], $username, 'LOGIN', 'User logged in successfully');
                        }
                        

                        
                        if ($remember_me) {
                            setcookie(
                                'remember_user',
                                $username,
                                time() + (86400 * 30), 
                                "/",
                                "",
                                false,
                                true
                            );
                        }

                        
                        header("Location: php.php#contact");
                        exit();

                    } else {
                        $error_message = "Invalid password";
                    }

                } else {
                    $error_message = "Username not found";
                }

            } catch (PDOException $e) {
                $error_message = "Something went wrong. Please try again later.";
                error_log($e->getMessage());
            }
        } else {
            $error_message = "Database connection failed";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Spider-Man Website</title>
    <link rel="stylesheet" href="log.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/vnd.microsoft.icon"  href="images/spiderman-tshirt-seeklogo.png">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <img src="images/download__6_-removebg-preview 1.png" alt="Spider-Man Logo" class="spider-logo">
                <h1>Spider-Man Admin</h1>
                <p>Enter your credentials to access the site</p>
            </div>
            
            <?php if ($error_message): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success_message): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle"></i>
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" id="loginForm">
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i> Username</label>
                    <div class="input-with-icon">
                        <input type="text" id="username" name="username" 
                               value="<?php echo htmlspecialchars($username); ?>" 
                               required autofocus placeholder="Enter your username">
                        <i class="fas fa-user input-icon"></i>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password</label>
                    <div class="input-with-icon">
                        <input type="password" id="password" name="password" 
                               required placeholder="Enter your password">
                        <i class="fas fa-lock input-icon"></i>
                        <button type="button" class="password-toggle" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <div class="login-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember_me" id="remember_me">
                        Remember me
                    </label>
                </div>
                
                <button type="submit" class="login-btn" id="loginButton">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
            
            <div class="back-home">
                <a href="php.php">
                    <i class="fas fa-arrow-left"></i> Back to Spider-Man Website
                </a>
            </div>

        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                const eyeIcon = this.querySelector('i');
                eyeIcon.classList.toggle('fa-eye');
                eyeIcon.classList.toggle('fa-eye-slash');
            });
            
            const loginForm = document.getElementById('loginForm');
            const loginButton = document.getElementById('loginButton');
            
            loginForm.addEventListener('submit', function() {
                
                loginButton.style.opacity = '0.8';
                loginButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging in...';
            });
            
            
            document.getElementById('username').focus();
            
            
            function getCookie(name) {
                const value = `; ${document.cookie}`;
                const parts = value.split(`; ${name}=`);
                if (parts.length === 2) return parts.pop().split(';').shift();
            }
            
            const rememberedUser = getCookie('remember_user');
            if (rememberedUser) {
                document.getElementById('username').value = rememberedUser;
                document.getElementById('remember_me').checked = true;
            }
        });
    </script>
</body>
</html>