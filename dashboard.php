<?php
session_start();

if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
</head>
<body>

<h1>Welcome Admin: <?= htmlspecialchars($_SESSION['admin_name']) ?></h1>

<p>This page is protected.</p>

<a href="logout.php">Logout</a>

</body>
</html>


