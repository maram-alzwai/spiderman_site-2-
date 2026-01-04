<?php
session_start();

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: ../login.php");
    exit();
}

$is_logged_in = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
$username = $is_logged_in ? $_SESSION['username'] : 'Guest';

require_once __DIR__ . '/../spidermandbconnection.php';


$pdo = db_connection("localhost", "spiderman", "root", "");

$messages = get_all_contact_messages($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Spiderman</title>

   
    <link rel="stylesheet" href="..\php.css"> 
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" type="..\image/vnd.microsoft.icon"  href="..\images/spiderman-tshirt-seeklogo.png">
</head>
<body>

        <section class="spidy" id="home">
       <nav>
    <div class="nav-logo">
        <a href="..\php.php#home">
            <img src="..\images/download__6_-removebg-preview 1.png" alt="Spider-Man logo">
        </a>
    </div>
    <ul>
        <li><a href="..\php.php"><i class="fas fa-home"></i> Home</a></li>
        <li><a href="dashboard.php"class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="..\profile2.php" ><i class="fas fa-user"></i> Profile</a></li>
        
    </ul>

    <ul> 
        <?php if ($is_logged_in): ?>
            <li class="user-menu">
                <a href="#" class="user-dropdown">
                    <i class="fas fa-user-circle"></i>
                    <?= htmlspecialchars($username) ?>
                </a>

                <ul class="dropdown-menu">
                    <li><a href="..\profile2.php" class="dropdown-item"><i class="fas fa-user"></i> <span>My Profile</span></a></li>
                    <li><a href="..\profile2.php?tab" class="dropdown-item"><i class="fas fa-cog"></i> <span>Settings</span></a></li>
                    <li><a href="?logout=1"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </li>
        <?php else: ?>
            <li>
                <a href="..\login.php" class="login-link">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>

    <div class="dashboard-container">
        
        <div class="admin-header">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
        </div>

        <div class="section-card">
            <h2><i class="fas fa-envelope"></i> Contact Messages</h2>
            
            <?php if (empty($messages)): ?>
                <p class="empty-message">No messages received yet.</p>
            <?php else: ?>
                <div style="overflow-x:auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Message</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($messages as $msg): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($msg['name']); ?></td>
                                <td><?php echo htmlspecialchars($msg['email']); ?></td>
                                <td><?php echo htmlspecialchars($msg['message']); ?></td>
                                <td><?php echo htmlspecialchars($msg['submission_date']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

       <div class="section-card">
          <h2><i class="fas fa-edit"></i> Manage Content</h2>
            <ul class="manage-links">
           <li><a href="movies.php"><i class="fas fa-film"></i> Movies</a></li>
           <li><a href="villians.php"><i class="fas fa-skull-crossbones"></i> Villains</a></li>
           <li><a href="gallary.php"><i class="fas fa-images"></i> Gallery</a></li>
           <li><a href="admins.php"><i class="fas fa-users-cog"></i> Admins</a></li>
    </ul>
</div>
    </div>


    <footer class="profile-footer">
    <div class="footer-content">
        <p>Web Application Development Project by Maram al Zwai, Alaa Abujazia © 2026</p>
    </div>
</footer>
</body>
</html>


