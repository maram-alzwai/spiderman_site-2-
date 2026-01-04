<?php
session_start();
require_once '../spidermandbconnection.php';


if (!isset($_SESSION['user_logged_in'])) {
    header("Location: ../login.php");
    exit();
}

$pdo = db_connection("localhost", "spiderman", "root", "");
$success_msg = '';

if (isset($_POST['add_villain'])) {
    $stmt = $pdo->prepare("
        INSERT INTO villains (villain_name, real_name, description, first_appearance, powers, image_path)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $_POST['villain_name'],
        $_POST['real_name'],
        $_POST['description'],
        $_POST['first_appearance'],
        $_POST['powers'],
        $_POST['image_path']
    ]);
    $success_msg = "villain has been added successfully!";
}

$delete_msg='';

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM villains WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    $delete_msg='villain has been deleted successfully!';
}


$villains = $pdo->query("SELECT * FROM villains ORDER BY id DESC")->fetchAll();

$is_logged_in = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
$username = $is_logged_in ? $_SESSION['username'] : 'Guest';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Villains</title>
    <link rel="stylesheet" href="../php.css">
    <link rel="stylesheet" href="movies.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" type="..\image/vnd.microsoft.icon"  href="..\images/spiderman-tshirt-seeklogo.png">

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
                    <li><a href="profile2.php" class="dropdown-item"><i class="fas fa-user"></i> <span>My Profile</span></a></li>
                    <li><a href="profile2.php?tab" class="dropdown-item"><i class="fas fa-cog"></i> <span>Settings</span></a></li>
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

    <header>
        <h1>Manage Villains</h1>
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

<form method="POST">
    <input name="villain_name" placeholder="Villain Name" required>
    <input name="real_name" placeholder="Real Name" required>
    <textarea name="description" placeholder="Description"></textarea>
    <input name="first_appearance" placeholder="First Appearance" required>
    <input name="powers" placeholder="Powers" required>
    <input name="image_path" placeholder="Image filename (img.jpg)">
    <button name="add_villain" class="add">Add Villain</button>
</form>

<hr>


<table border="1" cellpadding="10">
    <tr>
        <th>Villain Name</th>
        <th>Real Name</th>
        <th>First Appearance</th>
        <th>Powers</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($villains as $v): ?>
    <tr>
        <td><?= htmlspecialchars($v['villain_name']) ?></td>
        <td><?= htmlspecialchars($v['real_name']) ?></td>
        <td><?= htmlspecialchars($v['first_appearance']) ?></td>
        <td><?= htmlspecialchars($v['powers']) ?></td>
        <td class="tbt">
            <a href="villianedit.php?id=<?= $v['id'] ?>" class="edit">Edit</a> |
            <a href="?delete=<?= $v['id'] ?>" onclick="return confirm('Delete this villain?')" class="delete">Delete</a>
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

