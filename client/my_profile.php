<?php
session_start();
include '../config/db_conn.php';

if (!isset($_SESSION['user_id'])) { header("Location: ../login.php"); exit(); }

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['fullname']; // Sidebar එකට නම පෙන්වන්න
$msg = "";

// Update Profile Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    $sql = "UPDATE users SET fullname='$fullname', phone='$phone' WHERE id='$user_id'";
    if ($conn->query($sql)) {
        $_SESSION['fullname'] = $fullname; 
        $user_name = $fullname; // Update variable immediately
        $msg = "<div style='color: #34d399; margin-bottom: 15px; background: rgba(16,185,129,0.1); padding: 10px; border-radius: 5px;'>Profile updated successfully!</div>";
    } else {
        $msg = "<div style='color: #f87171; margin-bottom: 15px;'>Error updating profile.</div>";
    }
}

// Fetch Current User Data
$user = $conn->query("SELECT * FROM users WHERE id='$user_id'")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile - GreenLife</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Nav Bar Specific Fix */
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
                <li><a href="my_profile.php" class="active"><i class="fas fa-user-edit"></i> My Profile</a></li>
                <li><a href="medical_records.php"><i class="fas fa-file-medical-alt"></i> Medical Records</a></li>
                <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <h2 class="section-title">Edit Profile</h2>
            <div class="glass" style="padding: 40px; max-width: 600px;">
                <?php echo $msg; ?>
                <form method="POST">
                    <div class="form-group">
                        <label style="color: #a0aec0;">Full Name</label>
                        <input type="text" name="fullname" class="form-control" value="<?php echo $user['fullname']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label style="color: #a0aec0;">Phone Number</label>
                        <input type="text" name="phone" class="form-control" value="<?php echo $user['phone']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label style="color: #a0aec0;">Email (Cannot be changed)</label>
                        <input type="email" class="form-control" value="<?php echo $user['email']; ?>" disabled style="opacity: 0.6; cursor: not-allowed;">
                    </div>
                    <button type="submit" class="btn-main" style="margin-top: 10px;">Save Changes</button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>