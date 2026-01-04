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

function get_all_gallery_images($pdo) {
    $sql = "SELECT id, image_path, title, description, uploaded_at 
            FROM gallery 
            ORDER BY uploaded_at DESC";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching gallery images: " . $e->getMessage());
        return [];
    }
}

function count_gallery_images($pdo) {
    $sql = "SELECT COUNT(*) as total FROM gallery";
    $stmt = $pdo->query($sql);
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}

$images = get_all_gallery_images($pdo);
$total_images = count_gallery_images($pdo);

// حذف صورة
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    
    $sql = "SELECT image_path FROM gallery WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$delete_id]);
    $image = $stmt->fetch();
    
    if ($image) {
        $file_path = '../' . $image['image_path'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        
        $sql = "DELETE FROM gallery WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$delete_id]);
        
        header("Location: gallary.php?message=image_deleted");
        exit();
    } else {
        header("Location: gallary.php?error=image_not_found");
        exit();
    }
}

if (isset($_POST['add_image'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $file_type = $_FILES['image']['type'];
        
        if (in_array($file_type, $allowed_types)) {
            $upload_dir = '../uploads/gallery/';
            
            
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $file_name = uniqid() . '_' . time() . '.' . $file_extension;
            $file_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $file_path)) {

                $relative_path = 'uploads/gallery/' . $file_name;
                
                $sql = "INSERT INTO gallery (image_path, title, description) 
                        VALUES (?, ?, ?)";
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$relative_path, $title, $description]);
                
                header("Location: gallary.php?message=image_added");
                exit();
            } else {
                header("Location: gallary.php?error=upload_error");
                exit();
            }
        } else {
            header("Location: gallary.php?error=invalid_type");
            exit();
        }
    } else {
        header("Location: gallary.php?error=no_file");
        exit();
    }
}

// تحديث صورة
if (isset($_POST['update_image'])) {
    $image_id = (int)$_POST['image_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    
    $sql = "UPDATE gallery SET title = ?, description = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$title, $description, $image_id]);
    
    header("Location: gallary.php?message=image_updated");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Gallery - Spider-Man Dashboard</title>
    
    <link rel="stylesheet" href="../php.css">
    <link rel="stylesheet" href="admins.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="icon" type="image/vnd.microsoft.icon" href="../images/spiderman-tshirt-seeklogo.png">
</head>
<body>

<nav>
    <div class="nav-logo">
        <a href="../php.php#home">
            <img src="../images/download__6_-removebg-preview 1.png" alt="Spider-Man logo">
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
                    <li><a href="../profile2.php" class="dropdown-item"><i class="fas fa-user"></i> <span>My Profile</span></a></li>
                    <li><a href="../profile2.php?tab" class="dropdown-item"><i class="fas fa-cog"></i> <span>Settings</span></a></li>
                    <li><a href="?logout=1"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </li>
        <?php else: ?>
            <li>
                <a href="../login.php" class="login-link">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>

<div class="dashboard-container">
    
    <div class="admin-header">
        <h1><i class="fas fa-images"></i> Manage Gallery</h1>
        <p>Total Images: <?php echo $total_images; ?></p>
    </div>

    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-success">
            <?php 
            $messages = [
                'image_deleted' => 'Image deleted successfully!',
                'image_added' => 'Image added successfully!',
                'image_updated' => 'Image updated successfully!'
            ];
            echo $messages[$_GET['message']] ?? 'Action completed successfully!';
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            <?php 
            $errors = [
                'upload_error' => 'Error uploading image! Please try again.',
                'invalid_type' => 'Invalid image type! Please use JPG, PNG, GIF, or WebP.',
                'no_file' => 'Please select an image to upload.',
                'image_not_found' => 'Image not found!'
            ];
            echo $errors[$_GET['error']] ?? 'An error occurred!';
            ?>
        </div>
    <?php endif; ?>

    <!-- زر إضافة صورة جديدة -->
    <div class="section-card">
        <button type="button" onclick="toggleAddForm()" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Image
        </button>
        
        <div id="addImageForm" style="display: none; margin-top: 20px; padding: 20px; background: rgba(255, 255, 255, 0.05); border-radius: 10px;">
            <h3><i class="fas fa-upload"></i> Upload New Image</h3>
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Image:</label>
                    <input type="file" name="image" accept="image/*" required class="form-control" style="padding: 8px;">
                    <small class="text-muted">Allowed types: JPG, PNG, GIF, WebP (Max 5MB)</small>
                </div>
                <div class="form-group">
                    <label>Title:</label>
                    <input type="text" name="title" required class="form-control" placeholder="Enter image title">
                </div>
                <div class="form-group">
                    <label>Description:</label>
                    <textarea name="description" rows="3" class="form-control" placeholder="Enter description (optional)"></textarea>
                </div>
                <div class="form-buttons">
                    <button type="submit" name="add_image" class="btn btn-success">
                        <i class="fas fa-save"></i> Upload Image
                    </button>
                    <button type="button" onclick="toggleAddForm()" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- عرض صور الجاليري -->
    <div class="section-card">
        <h2><i class="fas fa-photo-video"></i> Gallery Images</h2>
        
        <?php if (empty($images)): ?>
            <p class="empty-message">No images found in gallery. Add your first image!</p>
        <?php else: ?>
            <div class="gallery-grid">
                <?php foreach ($images as $image): ?>
                <div class="gallery-item">
                    <div class="gallery-image-container">
                        <img src="../<?php echo htmlspecialchars($image['image_path']); ?>" 
                             alt="<?php echo htmlspecialchars($image['title']); ?>"
                             class="gallery-image"
                             onerror="this.src='../images/default-image.jpg'">
                        <div class="gallery-overlay">
                            <div class="gallery-actions">
                                <button type="button" onclick="toggleEditForm(<?php echo $image['id']; ?>)" 
                                        class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <a href="?delete_id=<?php echo $image['id']; ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Are you sure you want to delete this image?')">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="gallery-info">
                        <h4><?php echo htmlspecialchars($image['title']); ?></h4>
                        <?php if (!empty($image['description'])): ?>
                            <p class="description"><?php echo htmlspecialchars($image['description']); ?></p>
                        <?php endif; ?>
                        <small class="upload-date">
                            <i class="far fa-calendar"></i> 
                            <?php echo date('M d, Y', strtotime($image['uploaded_at'])); ?>
                        </small>
                    </div>
                    
                    <!-- نموذج التعديل -->
                    <div id="editForm<?php echo $image['id']; ?>" class="edit-form-container" style="display: none;">
                        <form method="POST" action="" class="edit-form">
                            <input type="hidden" name="image_id" value="<?php echo $image['id']; ?>">
                            <div class="form-group">
                                <label>Title:</label>
                                <input type="text" name="title" value="<?php echo htmlspecialchars($image['title']); ?>" 
                                       class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Description:</label>
                                <textarea name="description" rows="2" class="form-control"><?php echo htmlspecialchars($image['description'] ?? ''); ?></textarea>
                            </div>
                            <div class="form-buttons">
                                <button type="submit" name="update_image" class="btn btn-success btn-sm">
                                    <i class="fas fa-check"></i> Update
                                </button>
                                <button type="button" onclick="toggleEditForm(<?php echo $image['id']; ?>)" 
                                        class="btn btn-secondary btn-sm">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</div>

<script>
function toggleAddForm() {
    var form = document.getElementById('addImageForm');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}

function toggleEditForm(imageId) {
    var form = document.getElementById('editForm' + imageId);
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}

// التحقق من حجم الملف قبل الرفع
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.querySelector('input[name="image"]');
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // الحد الأقصى للحجم: 5MB
                const maxSize = 5 * 1024 * 1024;
                if (file.size > maxSize) {
                    alert('File size exceeds 5MB limit. Please choose a smaller file.');
                    e.target.value = '';
                }
                
                // عرض معاينة للصورة
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('imagePreview');
                    if (!preview) {
                        const previewDiv = document.createElement('div');
                        previewDiv.id = 'imagePreview';
                        previewDiv.style.margin = '10px 0';
                        previewDiv.innerHTML = '<p>Preview:</p><img src="' + e.target.result + '" style="max-width: 200px; max-height: 200px; border-radius: 5px;">';
                        fileInput.parentNode.appendChild(previewDiv);
                    } else {
                        preview.querySelector('img').src = e.target.result;
                    }
                }
                reader.readAsDataURL(file);
            }
        });
    }
    
    // إخفاء معاينة الصورة عند إغلاق النموذج
    const cancelBtn = document.querySelector('button[onclick="toggleAddForm()"]');
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function() {
            const preview = document.getElementById('imagePreview');
            if (preview) {
                preview.remove();
            }
        });
    }
});
</script>

<footer class="profile-footer">
    <div class="footer-content">
        <p>Web Application Development Project by Maram al Zwai, Alaa Abujazia © 2026</p>
    </div>
</footer>
</body>
</html>