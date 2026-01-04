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

function get_all_admins($pdo) {
    $sql = "SELECT id, username, full_name, email, avatar, bio, join_date, last_login, role 
            FROM profile 
            WHERE role = 'admin' 
            ORDER BY id ASC";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching admins: " . $e->getMessage());
        return [];
    }
}

function count_admins($pdo) {
    $sql = "SELECT COUNT(*) as total FROM profile WHERE role = 'admin'";
    $stmt = $pdo->query($sql);
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}

$admins = get_all_admins($pdo);
$total_admins = count_admins($pdo);

if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    
    if ($delete_id != $_SESSION['user_id']) {
        $sql = "DELETE FROM profile WHERE id = ? AND role = 'admin'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$delete_id]);
        
        header("Location: admins.php?message=admin_deleted");
        exit();
    } else {
        header("Location: admins.php?error=cannot_delete_current");
        exit();
    }
}

if (isset($_POST['update_role'])) {
    $user_id = (int)$_POST['user_id'];
    $new_role = $_POST['role'];
    
    $sql = "UPDATE profile SET role = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$new_role, $user_id]);
    
    header("Location: admins.php?message=role_updated");
    exit();
}


if (isset($_POST['add_admin'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $full_name = $_POST['full_name'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO profile (username, email, full_name, password_id, role, join_date) 
            VALUES (?, ?, ?, ?, 'admin', NOW())";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username, $email, $full_name, $password]);
    
    header("Location: admins.php?message=admin_added");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Admins - Spiderman Dashboard</title>
    
    <link rel="stylesheet" href="..\php.css">
    <link rel="stylesheet" href="admins.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="icon" type="image/vnd.microsoft.icon" href="..\images/spiderman-tshirt-seeklogo.png">
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
        <h1><i class="fas fa-users-cog"></i> Manage Admins</h1>
        <p>Total Admins: <?php echo $total_admins; ?></p>
    </div>

    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-success">
            <?php 
            $messages = [
                'admin_deleted' => 'Admin deleted successfully!',
                'role_updated' => 'Role updated successfully!',
                'admin_added' => 'Admin added successfully!'
            ];
            echo $messages[$_GET['message']] ?? 'Action completed successfully!';
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            <?php 
            $errors = [
                'cannot_delete_current' => 'Cannot delete your own account!'
            ];
            echo $errors[$_GET['error']] ?? 'An error occurred!';
            ?>
        </div>
    <?php endif; ?>

    <div class="section-card">
    <button type="button" onclick="redirectToAdminManagement()" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add New Admin
    </button>
    </div>

    <!-- عرض قائمة الأدمنز -->
    <div class="section-card">
        <h2><i class="fas fa-user-shield"></i> Admin List</h2>
        
        <?php if (empty($admins)): ?>
            <p class="empty-message">No admins found.</p>
        <?php else: ?>
            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Avatar</th>
                            <th>Username</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Bio</th>
                            <th>Join Date</th>
                            <th>Last Login</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($admins as $admin): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($admin['id']); ?></td>
                            <td>
                                <?php if (!empty($admin['avatar'])): ?>
                                    <img src="../<?php echo htmlspecialchars($admin['avatar']); ?>" 
                                         alt="Avatar" 
                                         style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                                <?php else: ?>
                                    <i class="fas fa-user-circle" style="font-size: 24px;"></i>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($admin['username']); ?></td>
                            <td><?php echo htmlspecialchars($admin['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($admin['email']); ?></td>
                            <td><?php echo htmlspecialchars(substr($admin['bio'] ?? 'No bio', 0, 50)); ?>...</td>
                            <td><?php echo htmlspecialchars($admin['join_date']); ?></td>
                            <td><?php echo htmlspecialchars($admin['last_login'] ?? 'Never'); ?></td>
                            <td>
                                <span class="role-badge <?php echo $admin['role'] === 'admin' ? 'role-admin' : 'role-user'; ?>">
                                    <?php echo htmlspecialchars($admin['role']); ?>
                                </span>
                            </td>
                            <td>
                                <button type="button" onclick="toggleRoleForm(<?php echo $admin['id']; ?>)" 
                                        class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit Role
                                </button>

                                <?php if ($admin['id'] != $_SESSION['user_id']): ?>
                                    <a href="?delete_id=<?php echo $admin['id']; ?>" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Are you sure you want to delete this admin?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">Current User</span>
                                <?php endif; ?>
                                <div id="roleForm<?php echo $admin['id']; ?>" style="display: none; margin-top: 10px; padding: 10px; background: #f8f9fa;">
                                    <form method="POST" action="" style="display: flex; gap: 10px; align-items: center;">
                                        <input type="hidden" name="user_id" value="<?php echo $admin['id']; ?>">
                                        <select name="role" class="form-control" style="flex: 1;">
                                            <option value="user" <?php echo $admin['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                                            <option value="admin" <?php echo $admin['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                        </select>
                                        <button type="submit" name="update_role" class="btn btn-success btn-sm">
                                            <i class="fas fa-check"></i> Update
                                        </button>
                                        <button type="button" onclick="toggleRoleForm(<?php echo $admin['id']; ?>)" 
                                                class="btn btn-secondary btn-sm">
                                            <i class="fas fa-times"></i> Cancel
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

</div>

<script>
function toggleAddForm() {
    var form = document.getElementById('addAdminForm');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}

function toggleRoleForm(adminId) {
    var form = document.getElementById('roleForm' + adminId);
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}
</script>

<footer class="profile-footer">
    <div class="footer-content">
        <p>Web Application Development Project by Maram al Zwai, Alaa Abujazia © 2026</p>
    </div>
</footer>


<script>
function redirectToAdminManagement() {
    window.location.href = '../profile2.php#admin-management';
}

document.addEventListener('DOMContentLoaded', function() {
    const adminForm = document.getElementById('addAdminForm');
    if (adminForm) {
        adminForm.addEventListener('submit', function(e) {
        });
    }
    if (window.location.hash === '#admin-management') {
        console.log('Welcome to admins section');
    }
});
</script>
</body>
</html>