<?php
// 1. Vercel සඳහා Session Path සැකසීම
session_save_path('/tmp');

// Session එක දැනටමත් Start වී නැත්නම් පමණක් Start කරන්න
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Dashboard Link එක Role එක අනුව වෙනස් කිරීම
$dashboard_link = "client/dashboard.php"; // Default

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'admin') {
        $dashboard_link = "admin/dashboard.php";
    } elseif ($_SESSION['role'] == 'therapist') {
        $dashboard_link = "therapist/dashboard.php";
    } else {
        $dashboard_link = "client/dashboard.php";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenLife Wellness Center</title>
    <!-- Stylesheet -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* Header Specific Styles */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
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
            text-decoration: none; /* Link යට ඉරි ඉවත් කිරීම */
            font-weight: 500;
        }
        .nav-links a:hover {
            color: var(--primary);
            text-shadow: 0 0 8px var(--primary);
        }
    </style>
</head>
<body>

<!-- Glassmorphism Navbar -->
<nav class="glass">
    <div class="container nav-content">
        <a href="index.php" class="logo">
            <i class="fas fa-leaf"></i> GreenLife
        </a>
        
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="services.php">Services</a></li>
            <li><a href="about.php">About</a></li>
            
            <?php if(isset($_SESSION['user_id'])): ?>
                <!-- Logged In නම්: Dashboard Link එක Role එක අනුව යයි -->
                <li><a href="<?php echo $dashboard_link; ?>" style="color: var(--accent);">Dashboard</a></li>
                <li><a href="logout.php" class="btn-main" style="padding: 8px 20px; font-size: 0.9rem;">Logout</a></li>
            <?php else: ?>
                <!-- Log In වී නැත්නම් -->
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php" class="btn-main" style="padding: 8px 20px; font-size: 0.9rem;">Sign Up</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
<!-- Body content starts with padding to avoid overlap with fixed header -->
<div style="padding-top: 80px;"></div>