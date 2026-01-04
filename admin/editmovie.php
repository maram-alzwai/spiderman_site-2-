<?php
session_start();
require_once '../spidermandbconnection.php';

if (!isset($_SESSION['user_logged_in'])) {
    header("Location: ../login.php");
    exit();
}

$pdo = db_connection("localhost", "spiderman", "root", "");

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: movies.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM movies WHERE ID = ?");
$stmt->execute([$id]);
$movie = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$movie) {
    header("Location: movies.php");
    exit();
}

$success_msg = '';

if (isset($_POST['update_movie'])) {
    $stmt = $pdo->prepare("
        UPDATE movies 
        SET Title = ?, release_year = ?, image = ?, Description = ?, rating = ?
        WHERE ID = ?
    ");
    $stmt->execute([
        $_POST['Title'],
        $_POST['release_year'],
        $_POST['image'],
        $_POST['Description'],
        $_POST['rating'],
        $id
    ]);

    $success_msg = "Movie updated successfully!";
}
$is_logged_in = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
$username = $is_logged_in ? $_SESSION['username'] : 'Guest';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Movie</title>
    <link rel="stylesheet" href="../php.css">
    <link rel="stylesheet" href="movies.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" type="..\image/vnd.microsoft.icon"  href="images/spiderman-tshirt-seeklogo.png">

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
    <li><a href="villians.php">Manage Villians</a></li>
    <li><a href="movies.php" class="active" >Manage Movies</a></li>
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
        <h1>Edit Movie</h1>
        <hr>
    </header>
<?php if ($success_msg): ?>
    <div class="success-message"><?= htmlspecialchars($success_msg) ?></div>
<?php endif; ?>

<form method="POST">
    <input type="text" name="Title" value="<?= htmlspecialchars($movie['Title']) ?>" required>
    <input type="number" name="release_year" value="<?= htmlspecialchars($movie['release_year']) ?>" required>
    <input type="text" name="image" value="<?= htmlspecialchars($movie['image']) ?>" required>
    <textarea name="Description" rows="5"><?= htmlspecialchars($movie['Description']) ?></textarea>
    <input type="number" step="0.1" name="rating" value="<?= htmlspecialchars($movie['rating']) ?>" required>
    <div class="actions">
        <button name="update_movie" class="edit">Update Movie</button>
        <a href="movies.php" class="cl">Cancel</a>
    </div>
</form>

<footer class="profile-footer">
    <div class="footer-content">
        <p>Web Application Development Project by Maram al Zwai, Alaa Abujazia © 2026</p>
    </div>
</footer>

</body>
</html>
