<?php

session_start();


if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

if (empty($_SESSION['username']) && !empty($user_data['username'])) {
    $_SESSION['username'] = $user_data['username'];
}

require_once 'spidermandbconnection.php';
$pdo = db_connection("localhost", "spiderman", "root", "");


$user_data = [];
$username = $_SESSION['username'];

$sql = "SELECT * FROM profile WHERE username = :username LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute(['username' => $username]);
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user_data) {
    die("User not found");
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    
    if (isset($_POST['update_profile'])) {

        $name  = trim($_POST['full_name']);
        $email = trim($_POST['email']);
        $bio   = trim($_POST['bio']);

        if (empty($name) || empty($email)) {
            $error = "Name and Email are required!";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email address!";
        } else {

            $checkEmailSql = "SELECT id FROM profile 
                              WHERE email = :email AND username != :username
                              LIMIT 1";
            $checkEmailStmt = $pdo->prepare($checkEmailSql);
            $checkEmailStmt->execute([
                'email'    => $email,
                'username' => $username
            ]);

            if ($checkEmailStmt->fetch()) {
                $error = "Email already exists!";
            } else {

                $sql = "UPDATE profile
                        SET full_name = :full_name,
                            email     = :email,
                            bio       = :bio
                        WHERE username = :username";

                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'full_name' => $name,
                    'email'     => $email,
                    'bio'       => $bio,
                    'username'  => $username
                ]);

                $success_message = "Profile updated successfully!";

                $user_data['full_name'] = $name;
                $user_data['email']     = $email;
                $user_data['bio']       = $bio;
            }
        }
    }

    if (isset($_POST['add_admin'])) {

        if (!in_array($user_data['role'], ['Admin', 'Administrator'])) {
            $error = "Only administrators can add new admins!";
        } else {

            $new_admin_username = trim($_POST['new_admin_username']);
            $new_admin_name     = trim($_POST['new_admin_name']);
            $new_admin_email    = trim($_POST['new_admin_email']);
            $new_admin_password = $_POST['new_admin_password'];

            if (
                empty($new_admin_username) ||
                empty($new_admin_name) ||
                empty($new_admin_email) ||
                empty($new_admin_password)
            ) {
                $error = "Please fill all required fields!";
            }
            elseif (!filter_var($new_admin_email, FILTER_VALIDATE_EMAIL)) {
                $error = "Invalid email address!";
            }
            elseif (strlen($new_admin_password) < 6) {
                $error = "Password must be at least 6 characters!";
            }
            else {

                
                $checkUserSql = "SELECT id FROM profile WHERE username = :username LIMIT 1";
                $checkUserStmt = $pdo->prepare($checkUserSql);
                $checkUserStmt->execute(['username' => $new_admin_username]);

                
                $checkEmailSql = "SELECT id FROM profile WHERE email = :email LIMIT 1";
                $checkEmailStmt = $pdo->prepare($checkEmailSql);
                $checkEmailStmt->execute(['email' => $new_admin_email]);

                if ($checkUserStmt->fetch()) {
                    $error = "Username already exists!";
                }
                elseif ($checkEmailStmt->fetch()) {
                    $error = "Email already exists!";
                }
                else {

                    try {
                        
                        $hashedPassword = password_hash($new_admin_password, PASSWORD_DEFAULT);

                        $passSql = "INSERT INTO spider_passwords (spider_name, current_password)
                                    VALUES (:username, :password)";
                        $passStmt = $pdo->prepare($passSql);
                        $passStmt->execute([
                            'username' => $new_admin_username,
                            'password' => $hashedPassword
                        ]);

                        $password_id = $pdo->lastInsertId();

                        
                        $profileSql = "INSERT INTO profile
                            (username, full_name, email, avatar, bio, join_date, last_login, password_id, role)
                            VALUES
                            (:username, :full_name, :email,
                             'images/avatars/default.png',
                             'New administrator',
                             NOW(), NOW(),
                             :password_id,
                             'Admin')";

                        $profileStmt = $pdo->prepare($profileSql);
                        $profileStmt->execute([
                            'username'    => $new_admin_username,
                            'full_name'   => $new_admin_name,
                            'email'       => $new_admin_email,
                            'password_id' => $password_id
                        ]);

                        header("Location: profile2.php?tab=admin-management&added=1");
                        exit();

                    } catch (PDOException $e) {
                        if ($e->getCode() == 23000) {
                            $error = "Username or Email already exists!";
                        } else {
                            throw $e;
                        }
                    }
                }
            }
        }
    }
}

if (isset($_GET['added'])) {
    $success_message = "Admin added successfully!";
    $new_admin_username = $new_admin_name = $new_admin_email = '';
}



$activeTab = 'personal-info'; 

if (isset($_GET['tab'])) {
    $tab = $_GET['tab'];
    if ($tab === 'settings') {
        $activeTab = 'settings';
    } elseif ($tab === 'security') {
        $activeTab = 'security';
    } elseif ($tab === 'activity') {
        $activeTab = 'activity';
    } elseif ($tab === 'preferences') {
        $activeTab = 'preferences';
    } elseif ($tab === 'admin-management') {
        $activeTab = 'admin-management';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Spider-Man Website</title>
    <link rel="stylesheet" href="profile1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/vnd.microsoft.icon"  href="images/spiderman-tshirt-seeklogo.png">
    
</head>
<body>
   <nav>
    <div class="nav-logo">
        <a href="php.php">
            <img src="images/download__6_-removebg-preview 1.png" alt="Spider-Man logo">
        </a>
    </div>
    <ul>
        <li><a href="php.php"><i class="fas fa-home"></i> Home</a></li>
        <li><a href="admin/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="profile2.php" class="active"><i class="fas fa-user"></i> Profile</a></li>
    </ul>
    <ul>
        <?php if (!empty($user_data)): ?>
            <li class="user-menu">
                <a href="#" class="user-dropdown">
                    <i class="fas fa-user-circle"></i>
                    <?= htmlspecialchars($user_data['username']) ?>
                </a>

                <ul class="dropdown-menu">
                    <li class="dropdown-divider"></li>
                    <li>
                        <a href="logout.php">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
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

    <section class="profile-container">
        <div class="profile-sidebar">
            <div class="profile-card">
                <div class="avatar-container">
                    <img src="<?php echo $user_data['avatar']; ?>" alt="Profile Avatar" class="profile-avatar" id="profile-avatar">
                    <div class="avatar-overlay">
                    <button class="avatar-change-btn" id="change-avatar-btn"><i class="fas fa-camera"></i></button>
                </div>
            </div>

            <input type="file" id="avatar-input" accept="image/*" style="display: none;">
                
                <h2 class="profile-name"><?php echo htmlspecialchars($user_data['full_name']); ?></h2>
                <p class="profile-role"><span class="role-badge"><?php echo $user_data['role']; ?></span></p>
                
                <div class="profile-stats">
                    <div class="stat-item">
                        <i class="fas fa-calendar-alt"></i>
                        <div>
                            <span class="stat-label">Joined</span>
                            <span class="stat-value"><?php echo date('M Y', strtotime($user_data['join_date'])); ?></span>
                        </div>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-sign-in-alt"></i>
                        <div>
                            <span class="stat-label">Last Login</span>
                            <span class="stat-value"><?php echo date('M d, H:i', strtotime($user_data['last_login'])); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            
           <div class="sidebar-menu">
                <a href="#personal-info" class="menu-item <?php echo $activeTab === 'personal-info' ? 'active' : ''; ?>"><i class="fas fa-user"></i> Personal Info</a>
                <a href="#security" class="menu-item <?php echo $activeTab === 'security' ? 'active' : ''; ?>"><i class="fas fa-shield-alt"></i> Security</a>
                <a href="#admin-management" class="menu-item <?php echo $activeTab === 'admin-management' ? 'active' : ''; ?>"><i class="fas fa-user-shield"></i> Admin Management</a>

            </div>

        </div>

        <section id="admin-management" class="profile-section">

            <div class="section-header"><h2><i class="fas fa-user-plus"></i> Add New Administrator</h2></div>

            <form method="POST" class="admin-form">

            <div class="form-grid">
                <div class="form-group">
                <label>Username</label>
               <input type="text" name="new_admin_username" placeholder="Enter username" required>
            </div>

            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="new_admin_name" placeholder="Enter name" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="new_admin_email" placeholder="Enter email"required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="new_admin_password" placeholder="Enter password" required>
            </div>
        </div>

        <button type="submit" name="add_admin" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Add Admin
        </button>

    </form>

</section>


        <div class="profile-content">
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <section id="personal-info" class="profile-section <?php echo $activeTab === 'personal-info' ? 'active' : ''; ?>">
    <div class="section-header">
        <h2><i class="fas fa-user-edit"></i> Personal Information</h2>
        <button type="button" class="edit-btn" onclick="toggleEdit()"><i class="fas fa-edit"></i> Edit</button>
    </div>
    
    <form method="POST" action="profile2.php?tab=personal-info" id="profileForm">
        <div class="form-grid">
            <div class="form-group">
                <label for="username"><i class="fas fa-user-tag"></i> Username</label>
                <div class="input-with-icon">
                    <input type="text" id="username" value="<?php echo htmlspecialchars($username); ?>" readonly>
                    <i class="fas fa-lock input-icon"></i>
                </div>
                <small class="form-hint">Username cannot be changed</small>
            </div>
            
            <div class="form-group">
                <label for="name"><i class="fas fa-user"></i> Full Name</label>
                <div class="input-with-icon">
                    <input type="text" id="name" name="full_name" value="<?php echo htmlspecialchars($user_data['full_name']); ?>" readonly>
                    <i class="fas fa-signature input-icon"></i>
                </div>
            </div>
            
            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Email Address</label>
                <div class="input-with-icon">
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" readonly>
                    <i class="fas fa-at input-icon"></i>
                </div>
            </div>
            
            <div class="form-group">
                <label for="role"><i class="fas fa-user-shield"></i> Role</label>
                <div class="input-with-icon">
                    <input type="text" id="role" value="<?php echo $user_data['role']; ?>" readonly>
                    <i class="fas fa-briefcase input-icon"></i>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label for="bio"><i class="fas fa-file-alt"></i> Bio</label>
            <textarea id="bio" name="bio" rows="4" readonly><?php echo htmlspecialchars($user_data['bio']); ?></textarea>
            <small class="form-hint">Tell us about yourself</small>
        </div>
        
        <div class="form-actions" id="formActions" style="display: none;">
            <button type="button" class="btn btn-secondary" onclick="toggleEdit()">Cancel</button>
            <button type="submit" name="update_profile" class="btn btn-primary">Save Changes</button>
        </div>
    </form>
</section>


<section id="security" class="profile-section <?php echo $activeTab === 'security' ? 'active' : ''; ?>">
    <div class="section-header">
        <h2><i class="fas fa-shield-alt"></i> Security</h2>
        <button type="button" class="btn btn-primary" onclick="openPasswordModal()">
            <i class="fas fa-key"></i> Change Password
        </button>
    </div>
    

    <input type="hidden" id="currentUsername" value="<?php echo htmlspecialchars($username); ?>">
    
    <div class="security-features">
        <div class="feature-card">
            <i class="fas fa-history"></i>
            <h3>Password History</h3>
            <p>Last changed: 
                <?php 
                $stmt = $pdo->prepare("SELECT last_updated FROM spider_passwords WHERE spider_name = :username");
                $stmt->execute(['username' => $username]);
                $passData = $stmt->fetch(PDO::FETCH_ASSOC);
                echo $passData ? date('M d, Y H:i', strtotime($passData['last_updated'])) : 'Never';
                ?>
            </p>
        </div>
        
        <div class="feature-card">
            <i class="fas fa-user-check"></i>
            <h3>Session Status</h3>
            <p>Logged in as: <strong><?php echo htmlspecialchars($username); ?></strong></p>
            <p>Session ID: <?php echo session_id(); ?></p>
        </div>
    </div>
</section>
            

           
<div class="quick-stats">

    <div class="stat-card <?php echo $currentTheme === 'light' ? 'light-theme' : ''; ?>">
    <i class="fas fa-clock"></i>
    <div>
        <h3>Account Age</h3>
        <p class="stat-number" id="accountAgeLive" 
           data-join="<?php echo $user_data['join_date']; ?>">
        </p>
    </div>
</div>
    
    <div class="stat-card ">
        <i class="fas fa-check-circle"></i>
        <div>
            <h3>Account Status</h3>
            <p class="stat-badge <?php echo $currentTheme === 'light' ? 'light-badge' : 'active'; ?>">
                Active   <i class="fas fa-check-circle fa-2x" style="color: #275e18ff; font-size: 1.2rem; "></i>
            </p>
        </div>
    </div>
    
    <div class="stat-card">
        <i class="fas fa-star"></i>
        <div>
            <h3>Role</h3>
            <p class="stat-badge role-badge">
                <?php echo $user_data['role']; ?>
            </p>
        </div>
    </div>
    
    <div class="stat-card">
        <i class="fas fa-envelope"></i>
        <div>
            <h3>Email Status</h3>
            <p class="stat-badge">
                <i class="fas fa-check" style="font-size: 1.2rem; "></i> Verified
            </p>
        </div>
    </div>

</div>
</section>
    
    <div id="passwordModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-key"></i> Change Password</h2>
                <button class="close-modal" onclick="closePasswordModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="passwordForm">
                    <div class="form-group">
                        <label for="currentPassword">Current Password</label>
                        <div class="input-with-icon">
                            <input type="password" id="currentPassword" required>
                            <i class="fas fa-lock input-icon"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="newPassword">New Password</label>
                        <div class="input-with-icon">
                            <input type="password" id="newPassword" required>
                            <i class="fas fa-lock input-icon"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Confirm New Password</label>
                        <div class="input-with-icon">
                            <input type="password" id="confirmPassword" required>
                            <i class="fas fa-lock input-icon"></i>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="closePasswordModal()">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    

    <script>
       let isEditing = false;
let originalValues = {};

function toggleEdit() {
    const inputs = document.querySelectorAll('#profileForm input, #profileForm textarea');
    const formActions = document.getElementById('formActions');
    const editBtn = document.querySelector('.edit-btn');
    
    if (!isEditing) {
        isEditing = true;
        
        inputs.forEach(input => {
            originalValues[input.id || input.name] = input.value;
        });
        
        inputs.forEach(input => {
            input.removeAttribute('readonly');
            input.style.backgroundColor = '#2a2a3e';
        });
        
        if (formActions) formActions.style.display = 'flex';
        
        editBtn.innerHTML = '<i class="fas fa-times"></i> Cancel';
        editBtn.classList.add('cancel');
        
    } else {
        if (confirm('Discard all changes?')) {
            inputs.forEach(input => {
                const key = input.id || input.name;
                if (originalValues[key]) {
                    input.value = originalValues[key];
                }
                input.setAttribute('readonly', true);
                input.style.backgroundColor = '#1a1a2e';
            });
            
            if (formActions) formActions.style.display = 'none';
            
            editBtn.innerHTML = '<i class="fas fa-edit"></i> Edit';
            editBtn.classList.remove('cancel');
            
            isEditing = false;
            originalValues = {};
        }
    }
}
        
 
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                
                document.querySelectorAll('.menu-item').forEach(i => i.classList.remove('active'));
                document.querySelectorAll('.profile-section').forEach(s => s.classList.remove('active'));
                
                this.classList.add('active');
                const targetId = this.getAttribute('href').substring(1);
                document.getElementById(targetId).classList.add('active');
            });
        });
        


function updateAccountAgeInWeeks() {
    const element = document.getElementById('accountAgeDisplay');
    if (!element) return;
    
    const joinDate = new Date(element.dataset.joinDate);
    const currentDate = new Date();
    
    
    const diffTime = currentDate - joinDate;
    const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
    
    
    const weeks = Math.floor(diffDays / 7);
    const remainingDays = diffDays % 7;
    
    
    let ageText = '';
    
    if (weeks >= 1) {
        ageText = weeks + ' week' + (weeks > 1 ? 's' : '');
        if (remainingDays > 0 && weeks < 4) {
            ageText += ', ' + remainingDays + ' day' + (remainingDays > 1 ? 's' : '');
        }
    } else {
        
        if (diffDays === 0) {
            ageText = 'Today';
        } else if (diffDays === 1) {
            ageText = '1 day';
        } else {
            ageText = diffDays + ' days';
        }
    }
    
    element.textContent = ageText;
}


document.addEventListener('DOMContentLoaded', updateAccountAgeInWeeks);

  

function openPasswordModal() {
    document.getElementById('passwordModal').style.display = 'block';

    document.getElementById('currentPassword').value = '';
    document.getElementById('newPassword').value = '';
    document.getElementById('confirmPassword').value = '';
}

function closePasswordModal() {
    document.getElementById('passwordModal').style.display = 'none';
}

window.onclick = function(event) {
    const modal = document.getElementById('passwordModal');
    if (event.target == modal) {
        closePasswordModal();
    }
}


document.getElementById('passwordForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const currentPassword = document.getElementById('currentPassword').value;
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    

    const username = document.getElementById('currentUsername') ? 
                     document.getElementById('currentUsername').value : '';

    console.log('Changing password for user:', username);


    if (newPassword !== confirmPassword) {
        alert('Passwords do not match!');
        return;
    }

    if (newPassword.length < 6) {
        alert('Password must be at least 6 characters long!');
        return;
    }


    const xhr = new XMLHttpRequest();
    xhr.open("POST", "change_password.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    
    xhr.onload = function() {
        console.log('Response received:', xhr.responseText);
        
        if (xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                alert(response.message);
                
                if (response.success) {
                    closePasswordModal();

                    document.getElementById('currentPassword').value = '';
                    document.getElementById('newPassword').value = '';
                    document.getElementById('confirmPassword').value = '';
                }
            } catch (e) {
                alert('Error parsing response: ' + e.message);
                console.error('Parse error:', e, 'Response:', xhr.responseText);
            }
        } else {
            alert('Server error: ' + xhr.status);
        }
    };
    
    xhr.onerror = function() {
        alert('Network error occurred. Please check your connection.');
    };
    
    
    const formData = `currentPassword=${encodeURIComponent(currentPassword)}&newPassword=${encodeURIComponent(newPassword)}`;
    xhr.send(formData);
});
        
        document.querySelector('.avatar-change-btn').addEventListener('click', function() {
            alert('Avatar upload feature would be implemented here.');
        });
        
        document.addEventListener('DOMContentLoaded', function() {
    
            const activeTab = "<?php echo $activeTab; ?>";
    
    
        document.querySelectorAll('.menu-item').forEach(i => i.classList.remove('active'));
        document.querySelectorAll('.profile-section').forEach(s => s.classList.remove('active'));
    
    
        document.querySelector(`a[href="#${activeTab}"]`)?.classList.add('active');
        document.getElementById(activeTab)?.classList.add('active');
    
    
});

    </script>
<script>
document.getElementById('passwordForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const currentPassword = document.getElementById('currentPassword').value;
    const newPassword     = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;

    if (newPassword !== confirmPassword) {
        alert('Passwords do not match!');
        return;
    }

    if (newPassword.length < 6) {
        alert('Password must be at least 6 characters long!');
        return;
    }

    
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "change_password.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            alert(response.message);
            if(response.success){
                closePasswordModal();
            }
        }
    };
    xhr.send(`currentPassword=${encodeURIComponent(currentPassword)}&newPassword=${encodeURIComponent(newPassword)}`);
});

document.getElementById('change-avatar-btn').addEventListener('click', function() {
    document.getElementById('avatar-input').click();
});

document.getElementById('avatar-input').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const formData = new FormData();
        formData.append('avatar', file);

        
        fetch('upload_avatar.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                
                document.getElementById('profile-avatar').src = data.new_avatar + '?t=' + new Date().getTime();
                alert('تم تحديث الصورة بنجاح!');
            } else {
                alert('حدث خطأ: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء رفع الصورة.');
        });
    }
});


</script>

<div id="passwordModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-key"></i> Change Password</h2>
            <button class="close-modal" onclick="closePasswordModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="passwordForm">
                <div class="form-group">
                    <label for="currentPassword">Current Password</label>
                    <div class="input-with-icon">
                        <input type="password" id="currentPassword" required>
                        <i class="fas fa-lock input-icon"></i>
                    </div>
                </div>
                <div class="form-group">
                    <label for="newPassword">New Password</label>
                    <div class="input-with-icon">
                        <input type="password" id="newPassword" required>
                        <i class="fas fa-lock input-icon"></i>
                    </div>
                    <small class="form-hint">Minimum 6 characters</small>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm New Password</label>
                    <div class="input-with-icon">
                        <input type="password" id="confirmPassword" required>
                        <i class="fas fa-lock input-icon"></i>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closePasswordModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {

    const el = document.getElementById("accountAgeLive");
    const joinDateStr = el.dataset.join;

    if (!joinDateStr) {
        el.textContent = "N/A";
        return;
    }

    const joinDate = new Date(joinDateStr);
    const now = new Date();

    
    const diffMs = now - joinDate;
    const totalDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));

    
    if (totalDays < 14) {
        const weeks = Math.max(1, Math.floor(totalDays / 7));
        el.textContent = weeks + " week" + (weeks > 1 ? "s" : "");
        return;
    }

    
    const years  = Math.floor(totalDays / 365);
    const months = Math.floor((totalDays % 365) / 30);
    const days   = totalDays % 30;

    if (years > 0) {
        el.textContent =
            years + " year" + (years > 1 ? "s" : "") +
            (months > 0 ? ", " + months + " month" + (months > 1 ? "s" : "") : "");
        return;
    }

    if (months > 0) {
        el.textContent =
            months + " month" + (months > 1 ? "s" : "");
        return;
    }

    el.textContent =
        days + " day" + (days > 1 ? "s" : "");
});
</script>



   <footer class="profile-footer">
        <div class="footer-content">
            <p>&copy; Web Application Development Project by Maram al Zwai, Alaa Abujazia © 2025</p>
            <div class="footer-links">
                <a href="Privacy_Policy.php"><i class="fas fa-shield-alt"></i> Privacy Policy</a>
            </div>
        </div>
    </footer>

</body>
</html>