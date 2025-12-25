<?php
// Vercel Cookie Fix applied
// session_start() අවශ්‍ය නැත.

// 1. Database Connection
include __DIR__ . '/../config/db_conn.php';

// 2. Security Check (Cookies භාවිතා කිරීම)
if (!isset($_COOKIE['user_id']) || $_COOKIE['role'] !== 'client') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_COOKIE['user_id'];
$user_name = $_COOKIE['fullname'];

// Fetch Completed Appointments (History)
$sql = "SELECT a.*, s.service_name, u.fullname as doc_name 
        FROM appointments a 
        JOIN services s ON a.service_id = s.id 
        LEFT JOIN users u ON a.therapist_id = u.id 
        WHERE a.client_id = '$user_id' AND a.status = 'completed' 
        ORDER BY a.appointment_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Records - GreenLife</title>
    
    <!-- Global CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- Dashboard Specific CSS -->
    <link rel="stylesheet" href="style.css">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* Navbar Tweaks */
        .nav-content { display: flex; justify-content: space-between; align-items: center; padding: 15px 0; }
        .logo { font-size: 1.5rem; font-weight: bold; color: var(--primary); text-decoration: none; }

        /* Mobile Toggle Button Style */
        .menu-toggle {
            display: none;
            font-size: 1.5rem;
            color: var(--primary);
            cursor: pointer;
            margin-right: 15px;
        }

        @media (max-width: 768px) {
            .menu-toggle { display: block; }
            .user-welcome-msg { display: none; } /* Mobile වල නම හංගනවා ඉඩ මදි නිසා */
        }
    </style>
</head>
<body>
    
    <!-- FIXED NAV BAR -->
    <nav class="glass" style="position: sticky; top: 0; z-index: 100; border-bottom: 1px solid rgba(255,255,255,0.1);">
        <div class="container nav-content">
            <div style="display: flex; align-items: center;">
                <!-- Mobile Menu Toggle -->
                <div class="menu-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </div>
                <a href="../index.php" class="logo"><i class="fas fa-leaf"></i> GreenLife</a>
            </div>

            <div style="display: flex; gap: 20px; align-items: center;">
                <span class="user-welcome-msg" style="color: #e2e8f0; font-size: 0.9rem;">Hello, <b><?php echo explode(' ', $user_name)[0]; ?></b></span>
                <a href="../logout.php" class="btn-main" style="padding: 8px 20px; font-size: 0.85rem;">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container dashboard-wrapper">
        
        <!-- Sidebar Navigation -->
        <aside class="glass sidebar-nav" id="sidebar">
            <div class="user-profile-section">
                <img src="https://ui-avatars.com/api/?name=<?php echo $user_name; ?>&background=10b981&color=fff" class="profile-img" alt="Profile">
                <div class="user-name"><?php echo $user_name; ?></div>
                <div class="user-role">Valued Client</div>
            </div>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php"><i class="fas fa-th-large"></i> Dashboard</a></li>
                <li><a href="../booking.php"><i class="fas fa-calendar-plus"></i> New Booking</a></li>
                <li><a href="my_profile.php"><i class="fas fa-user-edit"></i> My Profile</a></li>
                <li><a href="medical_records.php" class="active"><i class="fas fa-file-medical-alt"></i> Medical Records</a></li>
                <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <h2 class="section-title">Medical History</h2>
            
            <?php if ($result->num_rows > 0): ?>
                <div class="stats-grid" style="grid-template-columns: 1fr;"> 
                    <?php while($row = $result->fetch_assoc()): ?>
                        <div class="glass" style="padding: 25px; border-left: 5px solid var(--primary); margin-bottom: 20px;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                <div>
                                    <h3 style="margin: 0; color: #fff;"><?php echo $row['service_name']; ?></h3>
                                    <p style="color: var(--primary); margin: 5px 0;">
                                        <i class="fas fa-user-md"></i> Dr. <?php echo $row['doc_name'] ? $row['doc_name'] : 'General Therapist'; ?>
                                    </p>
                                    <small style="color: #a0aec0;"><i class="fas fa-calendar"></i> <?php echo $row['appointment_date']; ?></small>
                                </div>
                                <span class="status-badge status-completed">Completed</span>
                            </div>
                            <hr style="border-color: rgba(255,255,255,0.1); margin: 15px 0;">
                            <div>
                                <strong style="color: #a0aec0; display: block; margin-bottom: 5px;">Doctor's Note / Symptoms:</strong>
                                <p style="color: #e2e8f0; background: rgba(0,0,0,0.2); padding: 10px; border-radius: 8px;">
                                    <?php echo !empty($row['message']) ? $row['message'] : "No specific notes recorded."; ?>
                                </p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="glass" style="padding: 40px; text-align: center;">
                    <i class="fas fa-folder-open" style="font-size: 3rem; color: #a0aec0; margin-bottom: 20px;"></i>
                    <p style="margin-top: 10px; color: #a0aec0;">No medical records found.</p>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <!-- Script to Toggle Sidebar on Mobile -->
    <script>
        function toggleSidebar() {
            var sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }
    </script>

</body>
</html>