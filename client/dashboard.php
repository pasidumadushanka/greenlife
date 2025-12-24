<?php
session_save_path('/tmp'); // Vercel Session Fix
session_start();
include __DIR__ . '/../config/db_conn.php';

// User Log වී ඇත්ද සහ ඔහු Client කෙනෙක්ද යන්න තහවුරු කරගැනීම
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['fullname'];

// --- DATA FETCHING (දත්ත ලබා ගැනීම) ---

// 1. ඉදිරි Appointment එක (Upcoming)
$sql_upcoming = "SELECT a.*, s.service_name, s.price 
                 FROM appointments a 
                 JOIN services s ON a.service_id = s.id 
                 WHERE a.client_id = $user_id AND a.appointment_date >= CURDATE() 
                 ORDER BY a.appointment_date ASC LIMIT 1";
$res_upcoming = $conn->query($sql_upcoming);
$upcoming = $res_upcoming->fetch_assoc();

// 2. පසුගිය Appointments (History)
$sql_history = "SELECT a.*, s.service_name 
                FROM appointments a 
                JOIN services s ON a.service_id = s.id 
                WHERE a.client_id = $user_id 
                ORDER BY a.appointment_date DESC LIMIT 5";
$res_history = $conn->query($sql_history);

// 3. මුළු Appointments ගණන
$sql_count = "SELECT COUNT(*) as total FROM appointments WHERE client_id = $user_id";
$total_apps = $conn->query($sql_count)->fetch_assoc()['total'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard - GreenLife</title>
    
    <!-- Global CSS (Main Theme) -->
    <link rel="stylesheet" href="../assets/css/style.css">
    
    <!-- Client Dashboard Specific CSS -->
    <link rel="stylesheet" href="style.css">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        /* Navbar Tweaks for Dashboard */
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

    <!-- Top Navigation Bar -->
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
                <span class="user-welcome-msg" style="color: var(--text-color);">Hello, <b><?php echo explode(' ', $user_name)[0]; ?></b></span>
                <!-- Logout Link Fixed -->
                <a href="../logout.php" class="btn-main" style="padding: 8px 20px; font-size: 0.85rem;">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container dashboard-wrapper">
        
        <!-- Sidebar Navigation -->
        <aside class="glass sidebar-nav" id="sidebar">
            <!-- User Profile Section -->
            <div class="user-profile-section">
                <img src="https://ui-avatars.com/api/?name=<?php echo $user_name; ?>&background=10b981&color=fff" class="profile-img" alt="Profile">
                <div class="user-name"><?php echo $user_name; ?></div>
                <div class="user-role">Valued Client</div>
            </div>
            
            <!-- Menu Links -->
            <ul class="sidebar-menu">
                <li><a href="dashboard.php" class="active"><i class="fas fa-th-large"></i> Dashboard</a></li>
                <li><a href="../booking.php"><i class="fas fa-calendar-plus"></i> New Booking</a></li>
                <li><a href="my_profile.php"><i class="fas fa-user-edit"></i> My Profile</a></li>
                <li><a href="medical_records.php"><i class="fas fa-file-medical-alt"></i> Medical Records</a></li>
                <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
            </ul>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            
            <h2 class="section-title">Overview</h2>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <!-- Card 1 -->
                <div class="glass stat-card">
                    <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $total_apps; ?></h3>
                        <p>Total Bookings</p>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="glass stat-card">
                    <div class="stat-icon"><i class="fas fa-heartbeat"></i></div>
                    <div class="stat-info">
                        <h3>Good</h3>
                        <p>Health Status</p>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="glass stat-card">
                    <div class="stat-icon"><i class="fas fa-wallet"></i></div>
                    <div class="stat-info">
                        <h3>0 LKR</h3>
                        <p>Pending Payment</p>
                    </div>
                </div>
            </div>

            <!-- Upcoming Appointment Section -->
            <div class="glass upcoming-box">
                <div style="display: flex; justify-content: space-between; align-items: center; position: relative; z-index: 2; flex-wrap: wrap; gap: 20px;">
                    <div>
                        <h4 style="color: #a0aec0; text-transform: uppercase; letter-spacing: 1px;">Next Session</h4>
                        
                        <?php if($upcoming): ?>
                            <div class="app-details">
                                <h2><?php echo $upcoming['service_name']; ?></h2>
                                <div class="app-meta">
                                    <span><i class="far fa-calendar-alt"></i> <?php echo $upcoming['appointment_date']; ?></span>
                                    <span><i class="far fa-clock"></i> <?php echo $upcoming['appointment_time']; ?></span>
                                    <span style="color: var(--primary);"><i class="fas fa-tag"></i> LKR <?php echo $upcoming['price']; ?></span>
                                </div>
                            </div>
                        <?php else: ?>
                            <p style="margin-top: 15px; font-size: 1.1rem; color: #e2e8f0;">You have no upcoming appointments scheduled.</p>
                        <?php endif; ?>
                    </div>
                    
                    <div>
                        <?php if($upcoming): ?>
                            <button class="btn-main" style="background: transparent; border: 1px solid var(--primary); color: var(--primary);">Reschedule</button>
                        <?php else: ?>
                            <a href="../booking.php" class="btn-main">Book Now</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Recent History Table -->
            <div class="glass table-container">
                <h3 style="margin-bottom: 20px; color: var(--text-color);">Recent History</h3>
                
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Service Name</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($res_history->num_rows > 0): ?>
                            <?php while($row = $res_history->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <div style="font-weight: 600;"><?php echo $row['service_name']; ?></div>
                                    </td>
                                    <td><?php echo $row['appointment_date']; ?></td>
                                    <td><?php echo $row['appointment_time']; ?></td>
                                    <td>
                                        <?php 
                                            $status = $row['status'];
                                            $badgeClass = 'status-pending'; 
                                            
                                            if($status == 'confirmed') $badgeClass = 'status-confirmed';
                                            elseif($status == 'completed') $badgeClass = 'status-completed';
                                            elseif($status == 'cancelled') $badgeClass = 'status-cancelled';
                                        ?>
                                        <span class="status-badge <?php echo $badgeClass; ?>">
                                            <?php echo ucfirst($status); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align: center; color: #a0aec0; padding: 30px;">
                                    No appointment history found.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

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