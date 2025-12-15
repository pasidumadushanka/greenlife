<?php
include 'config/db_conn.php';
include 'includes/header.php';

// Database එකෙන් Services සියල්ල ලබා ගැනීම
$sql = "SELECT * FROM services";
$result = $conn->query($sql);

// Function: සේවාවේ නම අනුව ගැලපෙන පින්තූරයක් අන්තර්ජාලයෙන් ලබා දීම
function getServiceImage($serviceName) {
    $name = strtolower($serviceName);
    
    // Unsplash Direct Links (High Quality)
    if (strpos($name, 'yoga') !== false) {
        return 'https://images.pexels.com/photos/3094215/pexels-photo-3094215.jpeg';
    } elseif (strpos($name, 'ayurved') !== false || strpos($name, 'therapy') !== false) {
        return 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?auto=format&fit=crop&w=800&q=80';
    } elseif (strpos($name, 'nutrition') !== false || strpos($name, 'diet') !== false) {
        return 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?auto=format&fit=crop&w=800&q=80';
    } elseif (strpos($name, 'physio') !== false) {
        return 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=800&q=80';
    } elseif (strpos($name, 'massage') !== false) {
        return 'https://images.unsplash.com/photo-1600334089648-b0d9d3028eb2?auto=format&fit=crop&w=800&q=80';
    } else {
        // Default Wellness Image
        return 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=800&q=80';
    }
}
?>

<style>
    /* --- Modern Services Page Styles --- */
    
    /* 1. Hero & Search Section */
    .services-hero {
        text-align: center;
        padding: 100px 20px 60px;
        background: radial-gradient(circle at top, rgba(16, 185, 129, 0.15), transparent 70%);
    }

    .services-hero h1 {
        font-size: 3.5rem;
        margin-bottom: 15px;
        background: linear-gradient(to right, #fff, var(--primary));
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Search Bar Styling */
    .search-wrapper {
        max-width: 600px;
        margin: 30px auto;
        position: relative;
    }

    .search-input {
        width: 100%;
        padding: 15px 25px;
        padding-left: 50px;
        border-radius: 50px;
        border: 1px solid var(--glass-border);
        background: rgba(255, 255, 255, 0.05);
        color: white;
        font-size: 1.1rem;
        transition: 0.3s;
        backdrop-filter: blur(10px);
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 20px rgba(16, 185, 129, 0.3);
        background: rgba(255, 255, 255, 0.1);
    }

    .search-icon {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #a0aec0;
    }

    /* 2. Grid Layout */
    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 40px;
        padding-bottom: 100px;
    }

    /* 3. Modern Card Design */
    .service-card {
        border-radius: 20px;
        overflow: hidden;
        transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        display: flex;
        flex-direction: column;
        height: 100%;
        position: relative;
        border: 1px solid var(--glass-border);
        background: rgba(248, 247, 247, 0.02);
    }

    .service-card:hover {
        transform: translateY(-10px);
        border-color: var(--primary);
        box-shadow: 0 20px 40px rgba(0,0,0,0.4);
    }

    .img-wrapper {
        height: 220px;
        overflow: hidden;
        position: relative;
    }

    .service-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .service-card:hover .service-img {
        transform: scale(1.1); /* Zoom Effect */
    }

    .service-content {
        padding: 25px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .service-title {
        font-size: 1.6rem;
        color: #fff;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .service-desc {
        color: #a0aec0;
        margin-bottom: 25px;
        font-size: 0.95rem;
        line-height: 1.6;
    }

    /* Price Pill */
    .price-pill {
        background: rgba(16, 185, 129, 0.1);
        color: var(--primary);
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: bold;
        display: inline-block;
        margin-bottom: 15px;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    /* Animation Class */
    .fade-up {
        opacity: 0;
        transform: translateY(30px);
        animation: fadeUpAnim 0.8s forwards;
    }

    @keyframes fadeUpAnim {
        to { opacity: 1; transform: translateY(0); }
    }

</style>

<!-- Hero Section with Search -->
<section class="services-hero">
    <div class="container">
        <h1>Holistic Healing Services</h1>
        <p style="color: #a0aec0; max-width: 600px; margin: 0 auto;">
            Explore our wide range of treatments designed to balance your mind, body, and spirit.
        </p>

        <!-- Search Bar -->
        <div class="search-wrapper">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="searchInput" class="search-input" placeholder="Search for treatments (e.g., Yoga, Massage)..." onkeyup="filterServices()">
        </div>
    </div>
</section>

<!-- Services Grid -->
<div class="container">
    <div class="services-grid" id="servicesContainer">
        
        <?php if ($result->num_rows > 0): ?>
            <?php 
                $delay = 0; // Animation delay සඳහා
                while($row = $result->fetch_assoc()): 
                    $image_url = getServiceImage($row['service_name']); // අන්තර්ජාලයෙන් පින්තූරය ගැනීම
                    $delay += 0.1; // කාඩ් එකින් එක පේන්න
            ?>
                
                <!-- Service Card -->
                <div class="glass service-card fade-up" style="animation-delay: <?php echo $delay; ?>s;">
                    <div class="img-wrapper">
                        <img src="<?php echo $image_url; ?>" class="service-img" alt="<?php echo $row['service_name']; ?>">
                    </div>
                    
                    <div class="service-content">
                        <div>
                            <span class="price-pill">LKR <?php echo number_format($row['price'], 2); ?></span>
                            <h3 class="service-title"><?php echo $row['service_name']; ?></h3>
                            <p class="service-desc"><?php echo $row['description']; ?></p>
                        </div>
                        
                        <div>
                            <!-- Action Button Logic -->
                            <?php if(isset($_SESSION['user_id'])): ?>
                                <a href="booking.php" class="btn-main" style="display: block; text-align: center; width: 100%;">
                                    Book Session <i class="fas fa-arrow-right" style="margin-left: 5px;"></i>
                                </a>
                            <?php else: ?>
                               <a href="login.php" class="btn-main" 
                               style="display: block; text-align: center; background: transparent; border: 1px solid var(--primary); color: var(--primary); width: 100%; font-weight: 600;">
                               Login to Book
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            <?php endwhile; ?>
        <?php else: ?>
            <div style="grid-column: 1/-1; text-align: center; padding: 50px;">
                <h3 style="color: #a0aec0;">No services currently available.</h3>
            </div>
        <?php endif; ?>

    </div>
</div>

<!-- JavaScript for Live Search -->
<script>
    function filterServices() {
        // Input අගය ලබා ගැනීම
        var input = document.getElementById("searchInput");
        var filter = input.value.toUpperCase();
        var container = document.getElementById("servicesContainer");
        var cards = container.getElementsByClassName("service-card");

        // හැම කාඩ් එකක් හරහාම ගොස් පරීක්ෂා කිරීම
        for (var i = 0; i < cards.length; i++) {
            var title = cards[i].getElementsByClassName("service-title")[0];
            var txtValue = title.textContent || title.innerText;
            
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                cards[i].style.display = ""; // ගැලපෙනවා නම් පෙන්වන්න
                cards[i].style.animation = "none"; // Search කරනකොට animation එක අයින් කරන්න (smooth වෙන්න)
            } else {
                cards[i].style.display = "none"; // නැත්නම් හංගන්න
            }
        }
    }
</script>

<?php include 'includes/footer.php'; ?>