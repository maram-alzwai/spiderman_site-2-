<?php
session_start();
require_once "spidermandbconnection.php";

$my_pdo = db_connection("localhost", "spiderman", "root", "");

error_reporting(E_ALL);
ini_set('display_errors', 1);


$name = $email = $message = '';
$errors = [];
$showSuccess = false;


if (isset($_GET['new_message']) || isset($_GET['new'])) {
    unset($_SESSION['form_success']);
    unset($_SESSION['submitted_name']);
    $name = $email = $message = '';
    $errors = [];
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    

    $name = trim($_POST['name'] ?? '');
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
     
            $insert_result = insert_contact_message($my_pdo, $name, $email, $message);
            
            if ($insert_result) {
                $_SESSION['form_success'] = true;
                $_SESSION['submitted_name'] = $name;
                
               
                $name = $email = $message = '';
                
                header('Location: contact_us.php?success=1');
                exit();
            } else {
                $errors[] = "Failed to save your message. Please try again.";
            }
        } else {
            $errors[] = "Database connection failed. Please try again later.";
        }
    }
}


if (isset($_GET['success']) && $_GET['success'] == '1') {
    $showSuccess = isset($_SESSION['form_success']) && $_SESSION['form_success'];
}

if (!empty($errors) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['contact_errors'] = $errors;
    $_SESSION['contact_name'] = $name;
    $_SESSION['contact_email'] = $email;
    $_SESSION['contact_message'] = $message;
    header('Location: contact_us.php');
    exit();
}

if (isset($_SESSION['contact_errors'])) {
    $errors = $_SESSION['contact_errors'];
    $name = $_SESSION['contact_name'] ?? '';
    $email = $_SESSION['contact_email'] ?? '';
    $message = $_SESSION['contact_message'] ?? '';

    unset($_SESSION['contact_errors']);
    unset($_SESSION['contact_name']);
    unset($_SESSION['contact_email']);
    unset($_SESSION['contact_message']);
}

if (isset($_GET['logout'])) {
    session_destroy();
    setcookie('remember_user', '', time() - 3600, "/");
    header("Location: login.php");
    exit();
}

$is_logged_in = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
$username = $is_logged_in ? $_SESSION['username'] : 'Guest';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="spiderman.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="icon" type="image/vnd.microsoft.icon" href="images/spiderman-tshirt-seeklogo.png">
    <title>Contact Us - Spider-Man Website</title>
</head>
<body>
    <section class="spidy" id="home">
        <nav>
            <div class="nav-logo">
                <a href="php.php">
                    <img src="images/download__6_-removebg-preview 1.png" alt="Spider-Man logo">
                </a>
            </div>

            <ul>
                <li><a href="php.php">Home</a></li>
                <li><a href="php.php#about">About</a></li>
                <li><a href="php.php#movies">Movies</a></li>
                <li><a href="php.php#mcu">MCU</a></li>
                <li><a href="php.php#villains">Villains</a></li>
                <li><a href="php.php#actors">Actors</a></li>
                <li><a href="php.php#gallary">Gallery</a></li>
                <li><a href="contact_us.php" class="active">Contact Us</a></li>
            </ul>

            <ul> 
        <?php if ($is_logged_in): ?>
            <li class="user-menu">
                <a href="#" class="user-dropdown">
                    <i class="fas fa-user-circle"></i>
                    <?= htmlspecialchars($username) ?>
                </a>

                <ul class="dropdown-menu">
                    <li><a href="?logout=1"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </li>
        <?php else: ?>
            <li>
                <a href="login.php" class="login-link">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
            </li>
        <?php endif; ?>
    </ul>
        </nav>

        <section class="Contact" id="contact">
            <h2>Contact Us</h2>
            <p class="contact-subtitle">Get in touch with the Spider-Man team</p>
            
            <?php if ($showSuccess): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle success-icon"></i>
                    <h3>Thank you, <?php echo htmlspecialchars($_SESSION['submitted_name'] ?? ''); ?>!</h3>
                    <p>Your message has been received successfully.</p>
                    <a href="contact_us.php?new=1" class="anth">
                        <i class="fas fa-plus"></i> Send another message
                    </a>
                </div>

                <?php
                    
                    if (isset($_GET['new'])) {
                        unset($_SESSION['form_success']);
                        unset($_SESSION['submitted_name']);
                    }
                ?>

            <?php else: ?>

                <?php if (!empty($errors)): ?>
                    <div class="error-box">
                        <strong><i class="fas fa-exclamation-triangle"></i> Please correct the following errors:</strong>
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" class="cnt" id="contact">
                    <div class="form-group">
                        <input type="text" name="name" placeholder="Your Name" required 
                               value="<?php echo htmlspecialchars($name); ?>"
                               class="<?php echo (!empty($errors) && empty($name)) ? 'invalid' : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Your Email" required 
                               value="<?php echo htmlspecialchars($email); ?>"
                               class="<?php echo (!empty($errors) && empty($email)) ? 'invalid' : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <textarea name="message" rows="6" placeholder="Your Message" required
                                  class="<?php echo (!empty($errors) && empty($message)) ? 'invalid' : ''; ?>"><?php echo htmlspecialchars($message); ?></textarea>
                    </div>
                    
                    <button type="submit" class="send-btn">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>

                <div class="contact-info" style="margin-top: 30px; text-align: center; color: #aaa; font-size: 14px;">
                    <p><i class="fas fa-info-circle"></i> We usually respond within 24 hours</p>
                </div>

            <?php endif; ?>
        </section>
    </section>

<script>
document.addEventListener('DOMContentLoaded', () => {

    const currentPage = location.pathname.split('/').pop();
    document.querySelectorAll('nav a').forEach(link => {
        if (link.getAttribute('href').includes(currentPage)) {
            link.classList.add('active');
        }
    });

    const form = document.getElementById('contactForm');
    if (!form) return;

    form.addEventListener('submit', e => {
        const name = form.name.value.trim();
        const email = form.email.value.trim();
        const message = form.message.value.trim();

        let errors = [];

        form.querySelectorAll('.invalid').forEach(el => el.classList.remove('invalid'));

        if (name.length < 2) {
            errors.push('Name must be at least 2 characters');
            form.name.classList.add('invalid');
        }

        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            errors.push('Please enter a valid email address');
            form.email.classList.add('invalid');
        }

        if (message.length < 10) {
            errors.push('Message must be at least 10 characters');
            form.message.classList.add('invalid');
        }

        if (errors.length) {
            e.preventDefault();

            document.querySelector('.error-box')?.remove();

            const box = document.createElement('div');
            box.className = 'error-box';
            box.innerHTML = `
                <strong><i class="fas fa-exclamation-triangle"></i> Errors:</strong>
                <ul>${errors.map(e => `<li>${e}</li>`).join('')}</ul>
            `;

            form.before(box);
            box.scrollIntoView({ behavior: 'smooth' });
        }
    });

    form.querySelectorAll('input, textarea').forEach(el => {
        el.addEventListener('input', () => el.classList.remove('invalid'));
    });

});
</script>

</body>
</html>