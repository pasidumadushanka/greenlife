<?php include 'includes/header.php'; ?>

<style>
    /* --- GLOBAL ANIMATIONS & UTILS --- */
    :root {
        --glass-high-contrast: rgba(22, 27, 34, 0.85);
    }

    /* Scroll Reveal Animation */
    .reveal {
        opacity: 0;
        transform: translateY(50px);
        transition: all 0.8s ease-out;
    }
    .reveal.active {
        opacity: 1;
        transform: translateY(0);
    }

    .section-padding {
        padding: 100px 0;
    }
    
    .text-center { text-align: center; }
    .mb-20 { margin-bottom: 20px; }
    .mb-40 { margin-bottom: 40px; }
    .text-muted { color: #a0aec0; }
    .text-primary { color: var(--primary); }

    /* --- 1. HERO SECTION (VIDEO) --- */
    .hero-section {
        position: relative;
        height: 100vh;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .video-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: -2;
        filter: brightness(0.35) contrast(1.1); /* වීඩියෝව වඩාත් පැහැදිලි නමුත් අඳුරු කිරීමට */
    }

    .hero-content {
        text-align: center;
        z-index: 1;
        padding: 20px;
        animation: fadeInUp 1.2s ease-out;
    }

    .hero-title {
        font-size: 5rem; /* අකුරු ප්‍රමාණය වැඩි කළා */
        font-weight: 700;
        margin-bottom: 20px;
        background: linear-gradient(to right, #ffffff, #a7f3d0);
        background-clip: text;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        line-height: 1.1;
        text-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }

    .hero-subtitle {
        font-size: 1.4rem;
        color: #e2e8f0;
        margin-bottom: 40px;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
        font-weight: 300;
    }

    /* Scroll Down Arrow Animation */
    .scroll-down {
        position: absolute;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        color: white;
        animation: bounce 2s infinite;
        cursor: pointer;
        opacity: 0.7;
    }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {transform: translateY(0) translateX(-50%);}
        40% {transform: translateY(-10px) translateX(-50%);}
        60% {transform: translateY(-5px) translateX(-50%);}
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(40px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* --- 2. STATS BAR --- */
    .stats-wrapper {
        margin-top: -50px; /* Hero එක උඩින් පෙන්වීමට */
        position: relative;
        z-index: 10;
        padding: 0 20px;
    }
    
    .stats-bar {
        background: var(--glass-high-contrast);
        backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        padding: 40px;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        box-shadow: 0 20px 50px rgba(0,0,0,0.4);
    }

    .stat-item {
        text-align: center;
        border-right: 1px solid rgba(255,255,255,0.1);
    }
    .stat-item:last-child { border-right: none; }
    
    .stat-num { font-size: 2.5rem; font-weight: bold; color: var(--primary); display: block; }
    .stat-label { font-size: 0.9rem; color: #cbd5e0; text-transform: uppercase; letter-spacing: 1px; }

    /* --- 3. ABOUT SECTION --- */
    .about-grid {
        display: grid;
        grid-template-columns: 1.2fr 0.8fr;
        gap: 60px;
        align-items: center;
    }

    .about-img {
        width: 100%;
        border-radius: 20px;
        box-shadow: -20px 20px 0px rgba(16, 185, 129, 0.1); /* Decoration box */
        border: 1px solid var(--glass-border);
    }

    /* --- 4. HOW IT WORKS (STEPS) --- */
    .steps-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        margin-top: 50px;
    }

    .step-card {
        padding: 40px 20px;
        text-align: center;
        position: relative;
    }

    .step-number {
        font-size: 4rem;
        font-weight: bold;
        color: rgba(255,255,255,0.03);
        position: absolute;
        top: 0;
        right: 20px;
        z-index: 0;
    }

    .step-icon {
        font-size: 2.5rem;
        color: var(--accent);
        margin-bottom: 20px;
        position: relative;
        z-index: 1;
    }

    /* --- 5. SERVICES CARDS --- */
    .service-box {
        height: 400px;
        border-radius: 20px;
        overflow: hidden;
        position: relative;
        cursor: pointer;
    }

    .service-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .service-box:hover img { transform: scale(1.1); }

    .service-content {
        position: absolute;
        bottom: 0; left: 0; width: 100%;
        background: linear-gradient(to top, #000 10%, transparent);
        padding: 30px;
    }

    /* --- 6. TESTIMONIALS --- */
    .testimonial-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
    }
    
    .testimonial-card {
        padding: 30px;
        font-style: italic;
    }
    
    .user-info {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-top: 20px;
        font-style: normal;
    }
    
    .user-avatar {
        width: 50px; height: 50px;
        border-radius: 50%;
        object-fit: cover;
    }

    /* --- CTA PARALLAX --- */
    .cta-section {
        background-image: url('https://images.unsplash.com/photo-1545205597-3d9d02c29597?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
        background-attachment: fixed;
        background-size: cover;
        padding: 120px 0;
        text-align: center;
        position: relative;
    }

    /* Mobile Responsive */
    @media (max-width: 900px) {
        .hero-title { font-size: 3rem; }
        .stats-bar { grid-template-columns: 1fr 1fr; gap: 20px; }
        .about-grid, .steps-grid { grid-template-columns: 1fr; }
        .stat-item { border-right: none; }
    }
</style>

<!-- 1. HERO SECTION -->
<header class="hero-section">
    <!-- VIDEO BACKGROUND -->
    <video autoplay muted loop playsinline class="video-bg">
        <source src="assets/hero (2).mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <div class="container hero-content">
        <span style="color: var(--accent); letter-spacing: 3px; text-transform: uppercase; font-weight: 600; background: rgba(0,0,0,0.3); padding: 5px 15px; border-radius: 50px;">Welcome to GreenLife</span>
        
        <h1 class="hero-title">Wellness Reimagined <br> for the Digital Age</h1>
        
        <p class="hero-subtitle">
            Experience Sri Lanka's first AI-integrated holistic health platform. We combine traditional Ayurvedic care with modern convenience to bring balance to your life.
        </p>
        
        <div style="margin-top: 40px;">
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="booking.php" class="btn-main" style="padding: 18px 45px; font-size: 1.1rem; box-shadow: 0 0 20px rgba(16,185,129,0.5);">Book Appointment</a>
            <?php else: ?>
                <a href="register.php" class="btn-main" style="padding: 18px 45px; font-size: 1.1rem; box-shadow: 0 0 20px rgba(16,185,129,0.5);">Start Your Journey</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Scroll Down Indicator -->
    <div class="scroll-down" onclick="window.scrollTo({top: 800, behavior: 'smooth'});">
        <p style="font-size: 0.8rem; letter-spacing: 2px;">SCROLL DOWN</p>
        <i class="fas fa-chevron-down"></i>
    </div>
</header>

<!-- 2. STATS BAR (Overlap) -->
<div class="container stats-wrapper reveal">
    <div class="stats-bar">
        <div class="stat-item">
            <span class="stat-num">15+</span>
            <span class="stat-label">Years Exp.</span>
        </div>
        <div class="stat-item">
            <span class="stat-num">5k+</span>
            <span class="stat-label">Happy Clients</span>
        </div>
        <div class="stat-item">
            <span class="stat-num">25+</span>
            <span class="stat-label">Specialists</span>
        </div>
        <div class="stat-item">
            <span class="stat-num">4.9</span>
            <span class="stat-label">Rating</span>
        </div>
    </div>
</div>

<!-- 3. ABOUT INTRODUCTION -->
<section class="container section-padding reveal">
    <div class="about-grid">
        <!-- Content -->
        <div>
            <span class="text-primary" style="font-weight: 600;">WHO WE ARE</span>
            <h2 style="font-size: 3rem; margin: 15px 0 25px;">Connecting Nature with Technology</h2>
            <p class="text-muted" style="line-height: 1.8; margin-bottom: 20px;">
                In a fast-paced world, prioritizing health can be difficult. GreenLife Wellness Center bridges the gap by offering a seamless digital platform to manage your holistic well-being.
            </p>
            <p class="text-muted" style="line-height: 1.8; margin-bottom: 30px;">
                From booking traditional Ayurvedic therapies to consulting nutritionists online, we ensure that expert care is just a click away.
            </p>
            
            <div style="display: flex; gap: 30px; margin-bottom: 30px;">
                <div>
                    <i class="fas fa-check-circle text-primary"></i> <span style="color: #e2e8f0;">Certified Experts</span>
                </div>
                <div>
                    <i class="fas fa-check-circle text-primary"></i> <span style="color: #e2e8f0;">Personalized Care</span>
                </div>
            </div>

            <a href="about.php" style="color: var(--primary); font-weight: bold; text-decoration: none; border-bottom: 2px solid var(--primary);">Read Our Story</a>
        </div>

        <!-- Image -->
        <div>
            <img src="https://images.unsplash.com/photo-1600334089648-b0d9d3028eb2?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Meditation" class="about-img">
        </div>
    </div>
</section>

<!-- 4. HOW IT WORKS (New Section) -->
<section class="section-padding reveal" style="background: linear-gradient(180deg, var(--bg-color) 0%, #060911 100%);">
    <div class="container">
        <div class="text-center mb-40">
            <span class="text-primary">EASY PROCESS</span>
            <h2 style="font-size: 2.5rem;">How It Works</h2>
        </div>

        <div class="steps-grid">
            <div class="glass step-card">
                <span class="step-number">01</span>
                <i class="fas fa-user-plus step-icon"></i>
                <h3>Register Online</h3>
                <p class="text-muted">Create your secure account to access our digital health portal and manage your records.</p>
            </div>
            <div class="glass step-card">
                <span class="step-number">02</span>
                <i class="fas fa-calendar-alt step-icon"></i>
                <h3>Book Appointment</h3>
                <p class="text-muted">Browse our services and schedule a session with your preferred therapist instantly.</p>
            </div>
            <div class="glass step-card">
                <span class="step-number">03</span>
                <i class="fas fa-spa step-icon"></i>
                <h3>Relax & Heal</h3>
                <p class="text-muted">Visit our center for your treatment or join an online consultation from home.</p>
            </div>
        </div>
    </div>
</section>

<!-- 5. SERVICES SHOWCASE -->
<section class="container section-padding reveal">
    <div class="text-center mb-40">
        <span class="text-primary">OUR SERVICES</span>
        <h2 style="font-size: 2.5rem;">Holistic Treatments</h2>
    </div>

    <div class="grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
        <!-- Card 1 -->
        <div class="service-box">
            <img src="https://images.unsplash.com/photo-1519824145371-296894a0daa9?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Massage">
            <div class="service-content">
                <h3>Ayurvedic Therapy</h3>
                <p class="text-muted">Traditional oil treatments for deep relaxation.</p>
            </div>
        </div>
        <!-- Card 2 -->
        <div class="service-box">
            <img src="assets/pexels-lucaspezeta-2035066.jpg" alt="Yoga">
            <div class="service-content">
                <h3>Yoga & Meditation</h3>
                <p class="text-muted">Guided mindfulness sessions for mental clarity.</p>
            </div>
        </div>
        <!-- Card 3 -->
        <div class="service-box">
            <img src="https://images.unsplash.com/photo-1490645935967-10de6ba17061?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Diet">
            <div class="service-content">
                <h3>Clinical Nutrition</h3>
                <p class="text-muted">Diet plans tailored to your body type.</p>
            </div>
        </div>
    </div>

    <div class="text-center" style="margin-top: 50px;">
        <a href="services.php" class="btn-main" style="background: transparent; border: 1px solid var(--primary); color: var(--primary);">View All Services</a>
    </div>
</section>

<!-- 6. TESTIMONIALS (New Section) -->
<section class="section-padding reveal" style="background: rgba(255,255,255,0.02);">
    <div class="container">
        <h2 class="text-center mb-40" style="font-size: 2.5rem;">What Our Clients Say</h2>
        
        <div class="testimonial-grid">
            <div class="glass testimonial-card">
                <div class="mb-20" style="color: #fbbf24;">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <p class="text-muted">"The online booking system is a lifesaver! I booked my Yoga session in seconds. The facility is world-class."</p>
                <div class="user-info">
                    <img src="https://randomuser.me/api/portraits/women/44.jpg" class="user-avatar">
                    <div>
                        <h5 style="margin: 0;">Sarah Jenkins</h5>
                        <small class="text-muted">Yoga Enthusiast</small>
                    </div>
                </div>
            </div>

            <div class="glass testimonial-card">
                <div class="mb-20" style="color: #fbbf24;">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                </div>
                <p class="text-muted">"Dr. Perera's Ayurvedic consultation was spot on. I feel so much better after just two weeks of treatment."</p>
                <div class="user-info">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" class="user-avatar">
                    <div>
                        <h5 style="margin: 0;">David Miller</h5>
                        <small class="text-muted">IT Professional</small>
                    </div>
                </div>
            </div>

            <div class="glass testimonial-card">
                <div class="mb-20" style="color: #fbbf24;">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <p class="text-muted">"Finally, a wellness center that uses technology correctly. Accessing my medical records online is so convenient."</p>
                <div class="user-info">
                    <img src="https://randomuser.me/api/portraits/women/68.jpg" class="user-avatar">
                    <div>
                        <h5 style="margin: 0;">Amara Silva</h5>
                        <small class="text-muted">Teacher</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 7. LATEST ARTICLES (Blog Preview) -->
<section class="container section-padding reveal">
    <div class="text-center mb-40">
        <h2 style="font-size: 2.5rem;">Wellness Tips</h2>
        <p class="text-muted">Latest insights from our experts</p>
    </div>

    <div class="grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
        <div class="glass" style="padding: 0; overflow: hidden; border-radius: 15px;">
            <img src="https://images.unsplash.com/photo-1506126613408-eca07ce68773?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" style="width: 100%; height: 200px; object-fit: cover;">
            <div style="padding: 20px;">
                <small class="text-primary">YOGA</small>
                <h4 style="margin: 10px 0;">5 Benefits of Morning Yoga</h4>
                <a href="yogha.php" style="font-size: 0.9rem; color: #a0aec0;">Read More &rarr;</a>
            </div>
        </div>
        <div class="glass" style="padding: 0; overflow: hidden; border-radius: 15px;">
            <img src="https://images.unsplash.com/photo-1512621776951-a57141f2eefd?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" style="width: 100%; height: 200px; object-fit: cover;">
            <div style="padding: 20px;">
                <small class="text-primary">DIET</small>
                <h4 style="margin: 10px 0;">Superfoods for Immunity</h4>
                <a href="Diet.php" style="font-size: 0.9rem; color: #a0aec0;">Read More &rarr;</a>
            </div>
        </div>
        <div class="glass" style="padding: 0; overflow: hidden; border-radius: 15px;">
            <img src="https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" style="width: 100%; height: 200px; object-fit: cover;">
            <div style="padding: 20px;">
                <small class="text-primary">MIND</small>
                <h4 style="margin: 10px 0;">The Art of Mindfulness</h4>
                <a href="mind.php" style="font-size: 0.9rem; color: #a0aec0;">Read More &rarr;</a>
            </div>
        </div>
    </div>
</section>

<!-- 8. CTA PARALLAX -->
<section class="cta-section">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7);"></div>
    <div class="container" style="position: relative; z-index: 2;">
        <h2 style="font-size: 3.5rem; margin-bottom: 20px; font-weight: 700;">Your Wellness Awaits</h2>
        <p style="font-size: 1.2rem; color: #e2e8f0; margin-bottom: 40px; max-width: 600px; margin-left: auto; margin-right: auto;">
            Don't wait to prioritize your health. Join the GreenLife family today and experience the difference.
        </p>
        <a href="register.php" class="btn-main" style="padding: 15px 50px; font-size: 1.2rem; box-shadow: 0 0 30px rgba(16, 185, 129, 0.6);">Create Free Account</a>
    </div>
</section>

<!-- SCROLL REVEAL SCRIPT -->
<script>
    window.addEventListener('scroll', reveal);

    function reveal() {
        var reveals = document.querySelectorAll('.reveal');

        for (var i = 0; i < reveals.length; i++) {
            var windowheight = window.innerHeight;
            var revealtop = reveals[i].getBoundingClientRect().top;
            var revealpoint = 150;

            if (revealtop < windowheight - revealpoint) {
                reveals[i].classList.add('active');
            }
        }
    }
</script>

<?php include 'includes/footer.php'; ?>