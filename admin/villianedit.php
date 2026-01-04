<?php
session_start();
require_once '../spidermandbconnection.php';

// Check login
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: ../login.php");
    exit();
}

$pdo = db_connection("localhost", "spiderman", "root", "");

// Get ID
$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: villians.php");
    exit();
}

// Fetch villain
$stmt = $pdo->prepare("SELECT * FROM villains WHERE id = ?");
$stmt->execute([$id]);
$villain = $stmt->fetch();

if (!$villain) {
    header("Location: villians.php");
    exit();
}

$success_msg = '';

if (isset($_POST['update_villain'])) {
    $stmt = $pdo->prepare("
        UPDATE villains 
        SET villain_name=?, real_name=?, description=?, first_appearance=?, powers=?, image_path=?
        WHERE id=?
    ");
    $stmt->execute([
        $_POST['villain_name'],
        $_POST['real_name'],
        $_POST['description'],
        $_POST['first_appearance'],
        $_POST['powers'],
        $_POST['image_path'],
        $id
    ]);

    $success_msg = "Villain updated successfully!";
    
    $stmt = $pdo->prepare("SELECT * FROM villains WHERE id = ?");
    $stmt->execute([$id]);
    $villain = $stmt->fetch();
}


$is_logged_in = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
$username = $is_logged_in ? $_SESSION['username'] : 'Guest';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Villain</title>
    <link rel="stylesheet" href="../php.css">
    <link rel="stylesheet" href="movies.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" type="admin/download__6_-removebg-preview 1.png"  href="images/spiderman-tshirt-seeklogo.png">
</head>
<body>
<nav>
    <div class="nav-logo">
        <a href="..\php.php#home">
            <img src="..\images/download__6_-removebg-preview 1.png" alt="Spider-Man logo">
        </a>
    </div>

    <ul>
    <li><a href="../php.php#home">Home</a></li>
    <li><a href="../profile2.php">Profile</a></li>
    <li><a href="dashboard.php">Dashboard</a></li>
    <li><a href="villians.php" class="active" >Manage Villians</a></li>
    <li><a href="movies.php">Manage Movies</a></li>
    <li><a href="gallary.php">Gallery</a></li>
    <li><a href="admins.php">admins.php</a></li>
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
                    <li><a href="..\?logout=1"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
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

   <header>
        <h1>Edit Villain</h1>
        <hr>
    </header>

<?php if ($success_msg): ?>
    <div class="success-message"><?= htmlspecialchars($success_msg) ?></div>
<?php endif; ?>

<form method="POST">
    <input name="villain_name" value="<?= htmlspecialchars($villain['villain_name']) ?>" required>
    <input name="real_name" value="<?= htmlspecialchars($villain['real_name']) ?>" required>
    <textarea name="description"><?= htmlspecialchars($villain['description']) ?></textarea>
    <input name="first_appearance" value="<?= htmlspecialchars($villain['first_appearance']) ?>" required>
    <input name="powers" value="<?= htmlspecialchars($villain['powers']) ?>" required>
    <input name="image_path" value="<?= htmlspecialchars($villain['image_path']) ?>">

    <div class="actions">
        <button name="update_villain" class="edit">Update Villain</button>
        <a href="villians.php" class="cl">Cancel</a>
    </div>
</form>
<footer class="profile-footer">
    <div class="footer-content">
        <p>Web Application Development Project by Maram al Zwai, Alaa Abujazia © 2026</p>
    </div>
</footer>

</body>
</html>

