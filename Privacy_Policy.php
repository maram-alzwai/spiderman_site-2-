
<?php
session_start();
require_once 'spidermandbconnection.php';
$my_pdo = db_connection("localhost", "spiderman", "root", "");

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
    <title>Privacy Policy</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="profile1.css">
    <link rel="icon" type="image/vnd.microsoft.icon"  href="images/spiderman-tshirt-seeklogo.png">

</head>
<body>
    <nav>
    <div class="nav-logo">
        <a href="#home">
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
        <li><a href="contact_us.php">Contact Us</a></li>
    </ul>

    <ul> 
        <?php if ($is_logged_in): ?>
            <li class="user-menu">
                <a href="#" class="user-dropdown">
                    <i class="fas fa-user-circle"></i>
                    <?= htmlspecialchars($username) ?>
                </a>

                <ul class="dropdown-menu">
                    <li><a href="profile2.php" class="dropdown-item"><i class="fas fa-user"></i> <span>My Profile</span></a></li>
                    <li><a href="profile2.php?tab=settings" class="dropdown-item"><i class="fas fa-cog"></i> <span>Settings</span></a></li>
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


<section class="profile-head">
    <div class="profile-ban">
        <div class="banner-overlay">
            <h1>
                <i class="fas fa-user-shield"></i> Privacy Policy
            </h1>
            <p>Your privacy is important to us</p>
        </div>
    </div>
</section>


<section class="profile-container">
    <section class="profile-content">

        <div class="profile-section active">

            <div class="section-header">
                <h2><i class="fas fa-info-circle"></i> Introduction</h2>
            </div>

            <p>
                This Privacy Policy explains how we collect, use, and protect your personal information
                when you use our website and services.
            </p>

            <div class="section-header">
                <h2><i class="fas fa-database"></i> Information We Collect</h2>
            </div>

            <ul>
                <li>Personal information such as name, email address, and profile data</li>
                <li>Login and activity information</li>
                <li>Technical data such as browser type and IP address</li>
            </ul>

            <div class="section-header">
                <h2><i class="fas fa-cogs"></i> How We Use Your Information</h2>
            </div>

            <ul>
                <li>To provide and manage user accounts</li>
                <li>To improve our website and services</li>
                <li>To ensure security and prevent unauthorized access</li>
            </ul>

            <div class="section-header">
                <h2><i class="fas fa-lock"></i> Data Protection</h2>
            </div>

            <p>
                We implement appropriate security measures to protect your personal data from
                unauthorized access, alteration, disclosure, or destruction.
            </p>

            <div class="section-header">
                <h2><i class="fas fa-user-check"></i> Your Rights</h2>
            </div>

            <ul>
                <li>You have the right to access your personal data</li>
                <li>You may request correction or deletion of your information</li>
                <li>You can update your account details at any time</li>
            </ul>

            <div class="section-header">
                <h2><i class="fas fa-envelope"></i> Contact Us</h2>
            </div>

            <p>
                If you have any questions about this Privacy Policy, please contact us
                through the website support page.
            </p>

            <p class="form-hint">
                Last updated: <?php echo date("F Y"); ?>
            </p>

        </div>

    </section>
</section>

<footer class="profile-footer">
        <div class="footer-content">
            <p>&copy; Web Application Development Project by Maram al Zwai, Alaa Abujazia © 2026</p>
            <div class="footer-links">
                <a href="contact_us.php"><i class="fas fa-comments"></i> Contact Us</a>
            </div>
        </div>
</footer>

</body>
</html>
