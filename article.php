<?php
include 'includes/header.php';

// ලිපි වල දත්ත (Data) මෙතන ගබඩා කර ඇත (Database එකක් වෙනුවට Array එකක් භාවිතා කරමු)
$articles = [
    1 => [
        'category' => 'YOGA & FITNESS',
        'title' => '5 Benefits of Morning Yoga',
        'date' => 'October 12, 2025',
        'author' => 'Dr. Sarah Silva',
        'image' => 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80',
        'video' => 'https://www.youtube.com/embed/sTANio_2E0Q', // Morning Yoga Video
        'content' => '
            <p>Starting your day with yoga can set a positive tone for the rest of your day. It balances your breath, body, and mind before you step out into the hustle of daily life.</p>
            <h3>1. Boosts Metabolism</h3>
            <p>Morning yoga warms up your digestive system and gets nutrients moving through the body more efficiently. Twisting poses are especially good for this.</p>
            <h3>2. Improves Focus</h3>
            <p>Controlled breathing and meditation help clear the mind, allowing you to focus better on your daily tasks without feeling overwhelmed.</p>
            <h3>3. Increases Flexibility</h3>
            <p>After sleeping, muscles can be stiff. Gentle stretching in the morning loosens joints and prevents injuries throughout the day.</p>
            <p>Whether you are a beginner or an expert, dedicating just 15 minutes a day can transform your health.</p>
        '
    ],
    2 => [
        'category' => 'HEALTHY DIET',
        'title' => 'Superfoods for Immunity',
        'date' => 'November 05, 2025',
        'author' => 'Ms. N. Jayawardena',
        'image' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80',
        'video' => 'https://youtu.be/gnudf5IjfFQ?si=pMthbIsdPCAsXCqS', // Healthy Food Video
        'content' => '
            <p>Your immune system is your body’s defense against infections. What you eat plays a significant role in keeping you healthy.</p>
            <h3>Citrus Fruits</h3>
            <p>Vitamin C is thought to increase the production of white blood cells, which are key to fighting infections. Grapefruit, oranges, and lemons are great choices.</p>
            <h3>Ginger & Turmeric</h3>
            <p>These ancient roots have strong anti-inflammatory properties. They help reduce sore throats and inflammatory illnesses. Turmeric, in particular, contains curcumin, which is a powerful immune booster.</p>
            <h3>Green Tea</h3>
            <p>Packed with flavonoids, green tea serves as a powerful antioxidant that cleanses the body and improves immune function.</p>
        '
    ],
    3 => [
        'category' => 'MENTAL WELLNESS',
        'title' => 'The Art of Mindfulness',
        'date' => 'December 01, 2025',
        'author' => 'Mr. K. De Mel',
        'image' => 'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80',
        'video' => 'https://www.youtube.com/embed/inpok4MKVLM', // Meditation Video
        'content' => '
            <p>Mindfulness is the basic human ability to be fully present, aware of where we are and what we’re doing, and not overly reactive or overwhelmed by what’s going on around us.</p>
            <h3>Why Practice It?</h3>
            <p>Studies show that mindfulness reduces stress, lowers blood pressure, and improves sleep. It allows you to respond to situations rather than react impulsively.</p>
            <h3>How to Start?</h3>
            <ul>
                <li>Find a quiet place.</li>
                <li>Set a time limit (5-10 minutes).</li>
                <li>Notice your body and breathing.</li>
                <li>When your mind wanders, gently return your focus to your breath.</li>
            </ul>
            <p>It sounds simple, but it requires patience. Start small and see the change in your life.</p>
        '
    ]
];

// URL එකෙන් ID එක ලබා ගැනීම (Default = 1)
$article_id = isset($_GET['id']) ? $_GET['id'] : 1;
$data = isset($articles[$article_id]) ? $articles[$article_id] : $articles[1];
?>

<style>
    /* Article Page Styles */
    .article-header {
        height: 60vh;
        background: url('<?php echo $data['image']; ?>') no-repeat center center/cover;
        position: relative;
        display: flex;
        align-items: flex-end;
    }

    .overlay {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: linear-gradient(to top, var(--bg-color), transparent 80%);
    }

    .header-content {
        position: relative;
        z-index: 2;
        padding-bottom: 50px;
        width: 100%;
    }

    .meta-tag {
        background: var(--primary);
        color: #000;
        padding: 5px 15px;
        border-radius: 20px;
        font-weight: bold;
        font-size: 0.8rem;
        text-transform: uppercase;
    }

    .article-title {
        font-size: 3.5rem;
        margin-top: 15px;
        line-height: 1.1;
    }

    .article-meta {
        color: #a0aec0;
        margin-top: 10px;
        font-size: 1rem;
    }

    /* Content Area */
    .content-wrapper {
        max-width: 800px;
        margin: -50px auto 50px;
        position: relative;
        z-index: 10;
        padding: 40px;
    }

    .video-container {
        position: relative;
        padding-bottom: 56.25%; /* 16:9 aspect ratio */
        height: 0;
        overflow: hidden;
        border-radius: 20px;
        margin-bottom: 40px;
        border: 1px solid var(--glass-border);
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }

    .video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .article-text {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #e2e8f0;
    }

    .article-text h3 {
        color: var(--primary);
        font-size: 1.8rem;
        margin-top: 30px;
        margin-bottom: 15px;
    }

    .article-text p {
        margin-bottom: 20px;
    }

    .article-text ul {
        margin-bottom: 20px;
        padding-left: 20px;
    }

    .article-text li {
        margin-bottom: 10px;
    }

    .back-btn {
        display: inline-block;
        margin-bottom: 20px;
        color: #a0aec0;
        font-weight: 600;
    }
    
    .back-btn:hover { color: var(--primary); }

    @media (max-width: 768px) {
        .article-title { font-size: 2.5rem; }
        .content-wrapper { padding: 20px; margin-top: 0; }
        .article-header { height: 50vh; }
    }
</style>

<!-- Header Image Section -->
<header class="article-header">
    <div class="overlay"></div>
    <div class="container header-content">
        <a href="index.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Home</a><br>
        <span class="meta-tag"><?php echo $data['category']; ?></span>
        <h1 class="article-title"><?php echo $data['title']; ?></h1>
        <div class="article-meta">
            <span><i class="far fa-calendar"></i> <?php echo $data['date']; ?></span>
            <span style="margin: 0 15px;">|</span>
            <span><i class="far fa-user"></i> By <?php echo $data['author']; ?></span>
        </div>
    </div>
</header>

<!-- Main Content -->
<section class="content-wrapper glass">
    
    <!-- Embedded Video -->
    <div class="video-container">
        <iframe src="<?php echo $data['video']; ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>

    <!-- Article Text -->
    <div class="article-text">
        <?php echo $data['content']; ?>
    </div>

    <hr style="border-color: rgba(255,255,255,0.1); margin: 40px 0;">

    <div style="text-align: center;">
        <h3 style="margin-bottom: 20px;">Ready to start your journey?</h3>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="booking.php" class="btn-main">Book an Appointment</a>
        <?php else: ?>
            <a href="register.php" class="btn-main">Join GreenLife Today</a>
        <?php endif; ?>
    </div>

</section>

<?php include 'includes/footer.php'; ?>