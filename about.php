<?php
session_save_path('/tmp');
session_start();
include 'includes/header.php';
?>

<style>
    /* --- About Page Specific Styles --- */
    
    /* Hero Section */
    .about-hero {
        text-align: center;
        padding: 100px 20px;
        background: radial-gradient(circle at top, rgba(16, 185, 129, 0.15), transparent 70%);
        position: relative;
        overflow: hidden;
    }

    .about-hero h1 {
        font-size: 4rem;
        background: linear-gradient(to right, #fff, var(--primary));
        background-clip: text;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 20px;
    }

    /* Story Section (Split Layout) */
    .story-section {
        display: flex;
        align-items: center;
        gap: 50px;
        padding: 80px 0;
    }

    .story-text {
        flex: 1;
    }

    .story-img-wrapper {
        flex: 1;
        position: relative;
    }

    .story-img {
        width: 100%;
        border-radius: 20px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.5);
        border: 1px solid var(--glass-border);
        transition: 0.3s;
    }

    .story-img:hover {
        transform: scale(1.02);
        border-color: var(--primary);
    }

    /* Mission Cards */
    .mission-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        margin: 80px 0;
    }

    .mission-card {
        padding: 40px;
        text-align: center;
        border-top: 5px solid var(--primary);
    }

    .mission-icon {
        font-size: 3rem;
        color: var(--primary);
        margin-bottom: 20px;
    }

    /* Team Section */
    .team-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
        margin-top: 50px;
    }

    .team-member {
        text-align: center;
        padding: 30px;
        transition: 0.3s;
    }

    .team-member:hover {
        background: rgba(255, 255, 255, 0.05);
        transform: translateY(-10px);
    }

    .member-img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 20px;
        border: 3px solid var(--primary);
        padding: 3px;
    }

    /* Stats Banner */
    .stats-banner {
        background: linear-gradient(90deg, rgba(16, 185, 129, 0.1), transparent);
        padding: 60px 0;
        margin-top: 80px;
        border-top: 1px solid var(--glass-border);
        border-bottom: 1px solid var(--glass-border);
    }

    .stats-flex {
        display: flex;
        justify-content: space-around;
        text-align: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .stat-number {
        font-size: 3rem;
        font-weight: bold;
        color: var(--primary);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .story-section {
            flex-direction: column;
        }
        .about-hero h1 {
            font-size: 2.5rem;
        }
    }
</style>

<!-- 1. Hero Section -->
<section class="about-hero">
    <div class="container">
        <h1>Wellness for the Future</h1>
        <p style="font-size: 1.2rem; color: #a0aec0; max-width: 800px; margin: 0 auto;">
            GreenLife Wellness Center is Sri Lanka's first holistic health institute that seamlessly blends 
            ancient Ayurvedic wisdom with state-of-the-art digital healthcare technologies.
        </p>
    </div>
</section>

<!-- 2. Our Story Section -->
<div class="container">
    <div class="story-section">
        <div class="story-img-wrapper">
            <!-- Unsplash Image for Wellness/Nature -->
            <img src="https://images.unsplash.com/photo-1540555700478-4be289fbecef?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80" alt="Wellness Center" class="story-img">
        </div>
        <div class="story-text">
            <h2 style="font-size: 2.5rem; margin-bottom: 20px;">Who We Are</h2>
            <p style="color: #cbd5e0; margin-bottom: 20px; line-height: 1.8;">
                Located in the heart of Colombo, <b>GreenLife Wellness Center</b> was established with a singular vision: to create a sanctuary where the hustle of modern life meets the tranquility of nature.
            </p>
            <p style="color: #cbd5e0; margin-bottom: 20px; line-height: 1.8;">
                We recognized that while technology has advanced, personal well-being often takes a backseat. Our platform revolutionizes this by allowing clients to manage their physical and mental health digitally while receiving traditional hands-on care from expert therapists.
            </p>
            <div style="margin-top: 30px;">
                <span style="margin-right: 20px;"><i class="fas fa-check-circle" style="color: var(--primary);"></i> Certified Therapists</span>
                <span><i class="fas fa-check-circle" style="color: var(--primary);"></i> Eco-Friendly Practices</span>
            </div>
        </div>
    </div>
</div>

<!-- 3. Stats Banner -->
<div class="stats-banner">
    <div class="container stats-flex">
        <div>
            <div class="stat-number">10+</div>
            <p>Years of Experience</p>
        </div>
        <div>
            <div class="stat-number">5k+</div>
            <p>Happy Clients</p>
        </div>
        <div>
            <div class="stat-number">20+</div>
            <p>Expert Therapists</p>
        </div>
        <div>
            <div class="stat-number">100%</div>
            <p>Satisfaction Rate</p>
        </div>
    </div>
</div>

<!-- 4. Mission & Vision -->
<div class="container">
    <div class="mission-grid">
        <div class="glass mission-card">
            <i class="fas fa-bullseye mission-icon"></i>
            <h3>Our Mission</h3>
            <p style="color: #a0aec0; margin-top: 15px;">
                To provide accessible, high-quality holistic healthcare by integrating traditional healing methods with modern technological convenience.
            </p>
        </div>
        <div class="glass mission-card">
            <i class="fas fa-eye mission-icon"></i>
            <h3>Our Vision</h3>
            <p style="color: #a0aec0; margin-top: 15px;">
                To be the leading wellness provider in South Asia, fostering a healthier community through education, therapy, and digital innovation by 2030.
            </p>
        </div>
        <div class="glass mission-card">
            <i class="fas fa-heart mission-icon"></i>
            <h3>Core Values</h3>
            <p style="color: #a0aec0; margin-top: 15px;">
                Integrity, Patient-Centric Care, Innovation, and Sustainability are the pillars that hold GreenLife together.
            </p>
        </div>
    </div>
</div>

<!-- 5. Meet the Team -->
<div class="container" style="padding-bottom: 80px;">
    <h2 style="text-align: center; margin-bottom: 50px; font-size: 2.5rem;">Meet Our Experts</h2>
    
    <div class="team-grid">
        <!-- Doctor 1 -->
        <div class="glass team-member">
            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Doctor" class="member-img">
            <h3>Dr. A. Perera</h3>
            <p style="color: var(--primary); font-size: 0.9rem;">Chief Ayurvedic Consultant</p>
            <p style="font-size: 0.85rem; color: #a0aec0; margin-top: 10px;">
                Over 15 years of experience in traditional herbal medicine and pulse diagnosis.
            </p>
        </div>

        <!-- Doctor 2 -->
        <div class="glass team-member">
            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Doctor" class="member-img">
            <h3>Dr. Sarah Silva</h3>
            <p style="color: var(--primary); font-size: 0.9rem;">Yoga & Mindfulness Coach</p>
            <p style="font-size: 0.85rem; color: #a0aec0; margin-top: 10px;">
                Specializes in Hatha Yoga and mental wellness programs for stress relief.
            </p>
        </div>

        <!-- Doctor 3 -->
        <div class="glass team-member">
            <img src="https://randomuser.me/api/portraits/men/85.jpg" alt="Doctor" class="member-img">
            <h3>Mr. K. De Mel</h3>
            <p style="color: var(--primary); font-size: 0.9rem;">Senior Physiotherapist</p>
            <p style="font-size: 0.85rem; color: #a0aec0; margin-top: 10px;">
                Expert in sports injury recovery and physical rehabilitation therapies.
            </p>
        </div>
        
        <!-- Doctor 4 -->
        <div class="glass team-member">
            <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Doctor" class="member-img">
            <h3>Ms. N. Jayawardena</h3>
            <p style="color: var(--primary); font-size: 0.9rem;">Clinical Nutritionist</p>
            <p style="font-size: 0.85rem; color: #a0aec0; margin-top: 10px;">
                Helping clients achieve their health goals through personalized diet plans.
            </p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>