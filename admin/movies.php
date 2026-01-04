<?php
session_start();
require_once '../spidermandbconnection.php';

if (!isset($_SESSION['user_logged_in'])) {
    header("Location: ../login.php");
    exit();
}

$pdo = db_connection("localhost", "spiderman", "root", "");
$success_msg = '';

if (isset($_POST['add_movie'])) {
    $stmt = $pdo->prepare("
        INSERT INTO movies (Title, release_year, image, Description, rating)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $_POST['Title'],
        $_POST['release_year'],
        $_POST['image'],
        $_POST['Description'],
        $_POST['rating']
    ]);
    $success_msg = "Movie has been added successfully!";
}

$delete_msg='';

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM movies WHERE ID = ?");
    $stmt->execute([$_GET['delete']]);
    $delete_msg='Movie has been deleted successfully!';

}


$movies = $pdo->query("SELECT * FROM movies ORDER BY ID DESC")->fetchAll();

$is_logged_in = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
$username = $is_logged_in ? $_SESSION['username'] : 'Guest';

?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Movies</title>
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
        <li><a href="../php.php#home" class="<?= basename($_SERVER['PHP_SELF']) == '../php.php' ? 'active' : '' ?>">Home</a></li>
        <li><a href="../profile2.php" class="<?= basename($_SERVER['PHP_SELF']) == '../profile2.php' ? 'active' : '' ?>">Profile</a></li>
        <li><a href="dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">Dashboard</a></li>
        <li><a href="villians.php" class="<?= basename($_SERVER['PHP_SELF']) == 'villians.php' ? 'active' : '' ?>">Manage Villains</a></li>
        <li><a href="movies.php" class="<?= basename($_SERVER['PHP_SELF']) == 'movies.php' ? 'active' : '' ?>">Manage Movies</a></li>
        <li><a href="gallary.php" class="<?= basename($_SERVER['PHP_SELF']) == 'gallary.php' ? 'active' : '' ?>">Gallery</a></li>
        <li><a href="admins.php" class="<?= basename($_SERVER['PHP_SELF']) == 'admins.php' ? 'active' : '' ?>">Admins</a></li>
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
        <h1>Manage Movies</h1>
        <hr>
    </header>

    <?php if ($success_msg): ?>
    <div class="success-message"><?= htmlspecialchars($success_msg) ?></div>
<?php endif; ?>

<?php if ($delete_msg): ?>
    <div class="delete-message">
        <?= htmlspecialchars($delete_msg) ?>
    </div>
<?php endif; ?>

<!-- ADD MOVIE FORM -->
<form method="POST">
    <input name="Title" placeholder="Title" required><br>
    <input name="release_year" placeholder="Release Year" required><br>
    <input name="image" placeholder="Poster filename (img.jpg)" required><br>
    <textarea name="Description" placeholder="Description"></textarea><br>
    <input name="rating" placeholder="Rating (0-10)" required><br>
    <button name="add_movie">Add Movie</button>
</form>

<hr>

<!-- MOVIES TABLE -->
<table border="1" cellpadding="10">
    <tr>
        <th>Title</th>
        <th>Year</th>
        <th>Rating</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($movies as $movie): ?>
    <tr>
        <td><?= htmlspecialchars($movie['Title']) ?></td>
        <td><?= htmlspecialchars($movie['release_year']) ?></td>
        <td><?= htmlspecialchars($movie['rating']) ?></td>
        <td class="tbt">
            <a href="editmovie.php?id=<?= $movie['ID'] ?>">Edit</a> |
            <a href="?delete=<?= $movie['ID'] ?>" onclick="return confirm('Delete this movie?')">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>


<footer class="profile-footer">
    <div class="footer-content">
        <p>Web Application Development Project by Maram al Zwai, Alaa Abujazia © 2026</p>
    </div>
</footer>
</body>
</html>
