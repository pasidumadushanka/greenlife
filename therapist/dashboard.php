<?php
// Vercel Cookie Fix - No session_start needed
include __DIR__ . '/../config/db_conn.php';

// 1. Security Check (Using Cookies)
if (!isset($_COOKIE['user_id']) || $_COOKIE['role'] !== 'therapist') {
    header("Location: ../login.php");
    exit();
}

$therapist_id = $_COOKIE['user_id'];
$therapist_name = $_COOKIE['fullname'];

// --- ACTION HANDLING ---
// Appointment එකක් "Completed" ලෙස Mark කිරීම
if (isset($_GET['action']) && $_GET['action'] == 'complete' && isset($_GET['id'])) {
    $app_id = $_GET['id'];
    $conn->query("UPDATE appointments SET status='completed' WHERE id=$app_id AND therapist_id=$therapist_id");
    header("Location: dashboard.php");
    exit();
}

// --- DATA FETCHING ---

// 1. My Upcoming Appointments (Confirmed ඒවා විතරයි)
$sql_appointments = "SELECT a.id, a.appointment_date, a.appointment_time, a.message, a.status,
                            u.fullname as client_name, u.phone,
                            s.service_name 
                     FROM appointments a 
                     JOIN users u ON a.client_id = u.id 
                     JOIN services s ON a.service_id = s.id 
                     WHERE a.therapist_id = $therapist_id 
                     AND a.status = 'confirmed' 
                     ORDER BY a.appointment_date ASC";
$res_apps = $conn->query($sql_appointments);

// 2. Stats
$today = date('Y-m-d');
$today_count = $conn->query("SELECT COUNT(*) as c FROM appointments WHERE therapist_id=$therapist_id AND appointment_date='$today' AND status='confirmed'")->fetch_assoc()['c'];
$total_patients = $conn->query("SELECT COUNT(*) as c FROM appointments WHERE therapist_id=$therapist_id AND status='completed'")->fetch_assoc()['c'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Therapist Portal - GreenLife</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="style.css"> <!-- New Style File -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Mobile Toggle for Therapist */
        .menu-toggle {
            display: none;
            font-size: 1.5rem;
            color: #60a5fa;
            cursor: pointer;
            margin-right: 15px;
        }
        @media (max-width: 900px) {
            .menu-toggle { display: block; }
            .sidebar-nav { display: none; width: 100%; position: absolute; z-index: 999; top: 60px; left: 0; }
            .sidebar-nav.active { display: block; }
            .dashboard-container { flex-direction: column; }
        }
    </style>
</head>
<body>

    <!-- Nav -->
    <nav class="glass" style="position: sticky; top: 0; z-index: 100; border-bottom: 1px solid rgba(255,255,255,0.1);">
        <div class="container nav-content" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 20px;">
            <div style="display: flex; align-items: center;">
                <div class="menu-toggle" onclick="document.querySelector('.sidebar-nav').classList.toggle('active')">
                    <i class="fas fa-bars"></i>
                </div>
                <a href="#" class="logo"><i class="fas fa-user-md"></i> GreenLife <span style="font-size: 0.8rem; color: #60a5fa;">PRO</span></a>
            </div>
            
            <div style="display: flex; gap: 20px; align-items: center;">
                <span style="display: none; @media(min-width:768px){display:inline;}">Dr. <?php echo explode(' ', $therapist_name)[1] ?? $therapist_name; ?></span>
                <a href="../logout.php" class="btn-main" style="background: #334155; color: white; padding: 5px 15px; font-size: 0.8rem;">Logout</a>
            </div>
        </div>
    </nav>

    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar-nav">
            <ul class="sidebar-menu" style="list-style: none; padding: 0;">
                <li style="margin-bottom: 15px;"><a href="dashboard.php" style="color: #60a5fa; font-weight: bold;"><i class="fas fa-calendar-alt"></i> My Schedule</a></li>
                <li style="margin-bottom: 15px;"><a href="#" style="color: #a0aec0;"><i class="fas fa-users"></i> Patient Records</a></li>
                <li style="margin-bottom: 15px;"><a href="#" style="color: #a0aec0;"><i class="fas fa-envelope"></i> Messages</a></li>
            </ul>
        </aside>

        <!-- Content -->
        <main class="main-content">
            
            <h2 style="margin-bottom: 25px;">My Dashboard</h2>

            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card" style="border-bottom: 3px solid #60a5fa;">
                    <div class="stat-icon" style="background: rgba(96, 165, 250, 0.1); color: #60a5fa;"><i class="fas fa-calendar-day"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $today_count; ?></h3>
                        <p>Today's Sessions</p>
                    </div>
                </div>
                
                <div class="stat-card" style="border-bottom: 3px solid #34d399;">
                    <div class="stat-icon" style="background: rgba(52, 211, 153, 0.1); color: #34d399;"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $total_patients; ?></h3>
                        <p>Patients Treated</p>
                    </div>
                </div>
            </div>

            <!-- Appointment List -->
            <div class="glass" style="padding: 30px; border-radius: 15px;">
                <h3 style="margin-bottom: 20px;">Upcoming Appointments</h3>

                <?php if ($res_apps->num_rows > 0): ?>
                    <?php while($row = $res_apps->fetch_assoc()): ?>
                        
                        <div class="patient-card">
                            <div style="display: flex; gap: 20px; align-items: center;">
                                <!-- Time Badge -->
                                <div style="text-align: center; background: rgba(255,255,255,0.05); padding: 10px; border-radius: 10px; min-width: 80px;">
                                    <div style="font-weight: bold; color: #60a5fa;"><?php echo $row['appointment_time']; ?></div>
                                    <div style="font-size: 0.8rem; color: #a0aec0;"><?php echo $row['appointment_date']; ?></div>
                                </div>

                                <!-- Patient Info -->
                                <div>
                                    <h4 style="margin: 0; font-size: 1.1rem;"><?php echo $row['client_name']; ?></h4>
                                    <p style="margin: 5px 0 0; color: #a0aec0; font-size: 0.9rem;">
                                        <i class="fas fa-spa"></i> <?php echo $row['service_name']; ?>
                                    </p>
                                    <?php if(!empty($row['message'])): ?>
                                        <p style="margin: 5px 0 0; color: #f87171; font-size: 0.85rem;">
                                            <i class="fas fa-notes-medical"></i> Note: <?php echo $row['message']; ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Action -->
                            <div>
                                <a href="dashboard.php?action=complete&id=<?php echo $row['id']; ?>" class="btn-complete" onclick="return confirm('Mark this session as completed?');">
                                    <i class="fas fa-check"></i> Complete
                                </a>
                            </div>
                        </div>

                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="text-align: center; color: #a0aec0; padding: 20px;">No confirmed appointments found.</p>
                <?php endif; ?>

            </div>

        </main>
    </div>

</body>
</html>