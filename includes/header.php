<?php
session_save_path('/tmp');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Dashboard Link Logic
$dashboard_link = "client/dashboard.php";
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'admin') {
        $dashboard_link = "admin/dashboard.php";
    } elseif ($_SESSION['role'] == 'therapist') {
        $dashboard_link = "therapist/dashboard.php";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenLife Wellness Center</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* Header Base Styles */
        nav {
            position: fixed;
            top: 0; left: 0; width: 100%;
            z-index: 1000;
            padding: 15px 0;
            transition: 0.4s;
        }
        .nav-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: var(--primary);
            text-shadow: 0 0 10px rgba(16, 185, 129, 0.3);
            text-decoration: none;
        }
        .nav-links {
            list-style: none;
            display: flex;
            gap: 30px;
        }
        .nav-links a {
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
        }
        .nav-links a:hover {
            color: var(--primary);
        }
    </style>
</head>
<body>

<nav class="glass">
    <div class="container nav-content">
        <a href="index.php" class="logo">
            <i class="fas fa-leaf"></i> GreenLife
        </a>

        <!-- Hamburger Icon for Mobile -->
        <div class="menu-toggle" id="mobile-menu">
            <i class="fas fa-bars"></i>
        </div>
        
        <ul class="nav-links" id="nav-list">
            <li><a href="index.php">Home</a></li>
            <li><a href="services.php">Services</a></li>
            <li><a href="about.php">About</a></li>
            
            <?php if(isset($_SESSION['user_id'])): ?>
                <li><a href="<?php echo $dashboard_link; ?>" style="color: var(--accent);">Dashboard</a></li>
                <li><a href="logout.php" class="btn-main" style="padding: 8px 20px; font-size: 0.9rem;">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php" class="btn-main" style="padding: 8px 20px; font-size: 0.9rem;">Sign Up</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<!-- JavaScript for Mobile Menu Toggle -->
<script>
    const menuBtn = document.getElementById('mobile-menu');
    const navList = document.getElementById('nav-list');

    menuBtn.addEventListener('click', () => {
        navList.classList.toggle('active');
        
        // අයිකන් එක මාරු කිරීම (Bars <-> Times)
        const icon = menuBtn.querySelector('i');
        if (navList.classList.contains('active')) {
            icon.classList.remove('fa-bars');
            icon.classList.add('fa-times');
        } else {
            icon.classList.remove('fa-times');
            icon.classList.add('fa-bars');
        }
    });
</script>

<div style="padding-top: 80px;"></div>