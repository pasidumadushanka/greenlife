<?php
session_save_path('/tmp'); // මේ පේළිය අලුතින් දැම්මා
session_start();
include '../config/db_conn.php';

if (!isset($_SESSION['user_id'])) { header("Location: ../login.php"); exit(); }
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['fullname'];
$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_pass = $_POST['new_pass'];
    $confirm_pass = $_POST['confirm_pass'];

    if ($new_pass !== $confirm_pass) {
        $msg = "<div style='color: #f87171; margin-bottom: 15px; background: rgba(239,68,68,0.1); padding: 10px; border-radius: 5px;'>Passwords do not match!</div>";
    } else {
        $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
        $conn->query("UPDATE users SET password='$hashed_pass' WHERE id='$user_id'");
        $msg = "<div style='color: #34d399; margin-bottom: 15px; background: rgba(16,185,129,0.1); padding: 10px; border-radius: 5px;'>Password changed successfully!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings - GreenLife</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .nav-content { display: flex; justify-content: space-between; align-items: center; padding: 15px 0; }
        .logo { font-size: 1.5rem; font-weight: bold; color: var(--primary); text-decoration: none; }
    </style>
</head>
<body>
    
    <!-- FIXED NAV BAR -->
    <nav class="glass" style="position: sticky; top: 0; z-index: 100; border-bottom: 1px solid rgba(255,255,255,0.1);">
        <div class="container nav-content">
            <a href="../index.php" class="logo"><i class="fas fa-leaf"></i> GreenLife</a>
            <div style="display: flex; gap: 20px; align-items: center;">
                <span style="color: #e2e8f0; font-size: 0.9rem;">Hello, <b><?php echo explode(' ', $user_name)[0]; ?></b></span>
                <a href="../logout.php" class="btn-main" style="padding: 8px 20px; font-size: 0.85rem;">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container dashboard-wrapper">
        <aside class="glass sidebar-nav">
            <div class="user-profile-section">
                <img src="https://ui-avatars.com/api/?name=<?php echo $user_name; ?>&background=10b981&color=fff" class="profile-img">
                <div class="user-name"><?php echo $user_name; ?></div>
            </div>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php"><i class="fas fa-th-large"></i> Dashboard</a></li>
                <li><a href="../booking.php"><i class="fas fa-calendar-plus"></i> New Booking</a></li>
                <li><a href="my_profile.php"><i class="fas fa-user-edit"></i> My Profile</a></li>
                <li><a href="medical_records.php"><i class="fas fa-file-medical-alt"></i> Medical Records</a></li>
                <li><a href="settings.php" class="active"><i class="fas fa-cog"></i> Settings</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <h2 class="section-title">Account Settings</h2>
            
            <div class="glass" style="padding: 40px; max-width: 600px;">
                <h3 style="margin-bottom: 20px;">Change Password</h3>
                <?php echo $msg; ?>
                
                <form method="POST">
                    <div class="form-group">
                        <label style="color: #a0aec0;">New Password</label>
                        <input type="password" name="new_pass" class="form-control" placeholder="Enter new password" required>
                    </div>
                    <div class="form-group">
                        <label style="color: #a0aec0;">Confirm Password</label>
                        <input type="password" name="confirm_pass" class="form-control" placeholder="Re-enter new password" required>
                    </div>
                    <button type="submit" class="btn-main" style="background: var(--accent); color: #000; margin-top: 10px;">Update Password</button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>