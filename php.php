
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

if ($my_pdo) {
    $villains = vall_selection($my_pdo);
    $movies = selection($my_pdo);
    $MCU = mcu_selection($my_pdo); 
    $allCast=cast_selection($my_pdo);
    $gallery_items = get_gallery_items($my_pdo);
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="spiderman.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" type="image/vnd.microsoft.icon"  href="images/spiderman-tshirt-seeklogo.png">
    <title>Spider-Man Website</title>
</head>
<body>

    <section class="spidy" id="home">
       <nav>
    <div class="nav-logo">
        <a href="#home">
            <img src="images/download__6_-removebg-preview 1.png" alt="Spider-Man logo">
        </a>
    </div>

    <ul>
        <li><a href="#home">Home</a></li>
        <li><a href="#about">About</a></li>
        <li><a href="#movies">Movies</a></li>
        <li><a href="#mcu">MCU</a></li>
        <li><a href="#villains">Villains</a></li>
        <li><a href="#actors">Actors</a></li>
        <li><a href="#gallary">Gallery</a></li>
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


        <div class="hero-conant">
            <div class="hero-vid">
                <!-- <img src="images/download (10).jpg" alt="Spider-man"> -->
                <video src="videos/IMG_1018 (online-video-cutter.com).mp4" loop muted autoplay></video>
            </div>
            <div class="text">
                <p>Your Friendly Neighborhood Hero</p>
                <a href="#about" class="explore-btn">Explore</a>
            </div>
        </div>
    </section>


    <section class="About" id="about">
        <div class="about-img">
            <img src="images/marvels-spider-man-3840x2160-12906-removebg-preview.png" alt="Spider-Man">
            <div class="about-text">
                <h2>Who is Spider-Man?</h2>
                <p>Peter Parker is a teenager who gained spider-like powers after being bitten by a radioactive spider. Guided by responsibility, he protects New York while balancing normal life. His powers include superhuman strength, agility, the ability to cling to surfaces, a "spider-sense" that warns him of danger, and the ability to shoot webs from mechanical web-shooters he created.</p>
            </div>
        </div>
    </section>
    
    <section class="Movies" id="movies">
        <h2>Movies Review</h2>
        <div class="slider-container" >
    <button class="slide-btn left" onclick="slide('movieSlider', 'left')">❮</button>

    <div class="movie-slider" id="movieSlider">
        <?php foreach ($movies as $movie): ?>
            <div class="movie main">
                <img src="<?= $movie['Image'] ?>">
                <h3><?= $movie['Title'] ?> <br><span style="color: #e62429; font-size: 0.9em;">(<?= $movie['release_year'] ?>) <br><span style="color: #ffffffff; font-size: 0.9em;"> <?= $movie['stars']?> <?= $movie['rating']?></h3>
            </div>
        <?php endforeach; ?>
    </div>

    <button class="slide-btn right" onclick="slide('movieSlider', 'right')">❯</button>
</div>


</section>

    <!-- MCU SECTION -->
   <section class="Mcu" id="mcu">
    <h2>MCU Appearances</h2>
    <div class="slider-container">
        <button class="slide-btn left" onclick="slide('mcuSlider', 'left')">❮</button>

        <div class="movie-slider" id="mcuSlider">
            <?php if (!empty($MCU)): ?>
                <?php foreach ($MCU as $mcu): ?>
                    <div class="apperrance">
                        <img src="<?= $mcu['image_path'] ?>" alt="<?= $mcu['title'] ?>">
                        <h3><?= $mcu['title'] ?> <br><span style="color: #e62429; font-size: 0.9em;"> (<?= $mcu['release_year'] ?>) <br><span style="color: #ffffffff; font-size: 0.9em;">  <?= $mcu['stars']?> <?= $mcu['rating']?></h3>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No MCU appearances found.</p>
            <?php endif; ?>
        </div>

        <button class="slide-btn right" onclick="slide('mcuSlider', 'right')">❯</button>
    </div>
</section>

<section class="Villains" id="villains">
    <h2>Villains</h2>

    <div class="slider-container">
        <button class="slide-btn left" onclick="slide('villainsSlider', 'left')">❮</button>

        <div class="movie-slider" id="villainsSlider">
            <?php if (!empty($villains)): ?>
                <?php foreach ($villains as $villain): ?>
                    <div class="vln">
                        <img 
                            src="<?= $villain['image_path'] ?>" 
                            alt="<?= $villain['villain_name'] ?> (<?= $villain['real_name'] ?>)"
                        >
                        <h3>
                            <span style="color: #e62429;"> <?= $villain['villain_name'] ?> 
                            <?php if (!empty($villain['real_name'])): ?>
                                <p style="font-size: 0.8em; color: #ccc;">
                                    <?= $villain['powers'] ?>
                                </p>
                            <?php endif; ?>
                        </h3>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No villains found.</p>
            <?php endif; ?>
        </div>

        <button class="slide-btn right" onclick="slide('villainsSlider', 'right')">❯</button>
    </div>
</section>

<section class="Actors" id="actors">
    <h2>Actors</h2>

    <div class="slider-container">
        <button class="slide-btn left" onclick="slide('castSlider', 'left')">❮</button>

        <div class="movie-slider" id="castSlider">
            <?php if (!empty($allCast)): ?>
                <?php foreach ($allCast as $cast): ?>
                    <div class="cst">
                        <img 
                            src="<?= $cast['image_path'] ?>" 
                            alt="<?= $cast['actor_name'] ?> (<?= $cast['character_name'] ?>)"
                        >
                        <h3>
                            <?= $cast['actor_name'] ?>
                            <?php if (!empty($cast['character_name'])): ?>
                                <br><span style="color: #e62429; font-size: 0.9em;"><?= $cast['character_name'] ?></span>
                            <?php endif; ?>
                        </h3>
                        <p style="font-size: 0.8em; color: #ccc;">
                            <?= $cast['universe'] ?> Universe • <?= $cast['role_type'] ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No cast found.</p>
            <?php endif; ?>
        </div>

        <button class="slide-btn right" onclick="slide('castSlider', 'right')">❯</button>
    </div>
</section>

<section class="Gallary" id="gallary">
    <h2>Spider-Man Gallery</h2>

    <div class="grid" id="Grid">
        <?php 
        if (empty($gallery_items)):
            $visible_count = 0;
        ?>
            <p class="no-items">No gallery items found.</p>
        <?php 
        else: 
            $visible_count = 6; 
            $total_items = count($gallery_items);
            
            for ($i = 0; $i < min($visible_count, $total_items); $i++): 
                $item = $gallery_items[$i];
        ?>
                <div class="gallery-item always-visible">
                    <img src="<?php echo $item['image_path']; ?>" 
                         alt="<?php echo htmlspecialchars($item['title']); ?>"
                         loading="lazy">
                    
                    <div class="gallery-overlay">
                        <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                        <?php if (!empty($item['description'])): ?>
                            <p><?php echo htmlspecialchars($item['description']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
        <?php 
            endfor; 
            
            
            for ($i = $visible_count; $i < $total_items; $i++): 
                $item = $gallery_items[$i];
        ?>
                <div class="gallery-item extra" style="display: none;">
                    <img src="<?php echo $item['image_path']; ?>" 
                         alt="<?php echo htmlspecialchars($item['title']); ?>"
                         loading="lazy">
                    
                    <div class="gallery-overlay">
                        <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                        <?php if (!empty($item['description'])): ?>
                            <p><?php echo htmlspecialchars($item['description']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
        <?php 
            endfor; 
        endif; 
        ?>
    </div>

 
    <?php if ($total_items > $visible_count): ?>
        <div class="show-more-container">
            <button class="show-more-btn" id="showMoreBtn">
                Show More 
                <span class="count">(<?php echo $total_items - $visible_count; ?> more)</span>
                <i class="fas fa-chevron-down"></i>
            </button>
            <button class="show-less-btn" id="showLessBtn" style="display: none;">
                Show Less
                <i class="fas fa-chevron-up"></i>
            </button>
        </div>
    <?php endif; ?>

    <div class="total">
        <div class="stat">
            <span class="stat-number"><?php echo $total_items ?? 0; ?></span>
            <span class="stat-label">Total Images</span>
        </div>
    </div>
</section>
    

<footer>
    Web Application Development Project by Maram al Zwai, Alaa Abujazia © 2025
</footer>



<script>
function slide(sliderId, direction) {
    const slider = document.getElementById(sliderId);
    const amount = 300;

    slider.scrollBy({
        left: direction === 'right' ? amount : -amount,
        behavior: 'smooth'
    });
}
</script>


<script>
document.addEventListener('DOMContentLoaded', () => {

    const showMoreBtn = document.getElementById('showMoreBtn');
    const showLessBtn = document.getElementById('showLessBtn');
    const visibleCount = document.getElementById('visibleCount');

    const allItems = document.querySelectorAll('.gallery-item');
    const extraItems = document.querySelectorAll('.gallery-item.extra');
    const alwaysVisible = document.querySelectorAll('.gallery-item.always-visible');

    function toggleGallery(showAll) {
        extraItems.forEach(item => {
            item.style.display = showAll ? 'block' : 'none';
        });

        showMoreBtn.style.display = showAll ? 'none' : 'inline-flex';
        showLessBtn.style.display = showAll ? 'inline-flex' : 'none';

        if (visibleCount) {
            visibleCount.textContent = showAll
                ? allItems.length
                : alwaysVisible.length;
        }

        if (!showAll) {
            showMoreBtn.scrollIntoView({ behavior: 'smooth' });
        }
    }

    showMoreBtn?.addEventListener('click', () => toggleGallery(true));
    showLessBtn?.addEventListener('click', () => toggleGallery(false));

});
</script>

<script>

document.addEventListener('DOMContentLoaded', function() {
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('nav ul li a[href^="#"]');
    
    
    function removeActiveClasses() {
        navLinks.forEach(link => {
            link.classList.remove('active');
        });
    }
    
    
    function setActiveLink() {
        let current = '';
        const scrollPos = window.scrollY + 100; 
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            
            if (scrollPos >= sectionTop && scrollPos < sectionTop + sectionHeight) {
                current = section.getAttribute('id');
            }
        });
        
        
        removeActiveClasses();
        
       
        if (current) {
            document.querySelector(`nav ul li a[href="#${current}"]`)?.classList.add('active');
        } else {
           
            document.querySelector('nav ul li a[href="#home"]')?.classList.add('active');
        }
    }
    

    window.addEventListener('scroll', setActiveLink);

    setActiveLink();
    

    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {

            if (this.getAttribute('href').startsWith('#')) {
         
                removeActiveClasses();
           
                this.classList.add('active');
                
          
                const targetId = this.getAttribute('href').substring(1);
                const targetSection = document.getElementById(targetId);
                if (targetSection) {
                    e.preventDefault();
                    window.scrollTo({
                        top: targetSection.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            }
        });
    });
});
</script>
</body>
</html>
