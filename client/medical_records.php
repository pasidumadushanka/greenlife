<?php
session_start();
include '../config/db_conn.php';

if (!isset($_SESSION['user_id'])) { header("Location: ../login.php"); exit(); }
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['fullname'];

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
    <title>Medical Records - GreenLife</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="glass" style="position: sticky; top: 0; z-index: 100;">
        <div class="container nav-content">
            <a href="../index.php" class="logo"><i class="fas fa-leaf"></i> GreenLife</a>
            <a href="../logout.php" class="btn-main">Logout</a>
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
                <li><a href="medical_records.php" class="active"><i class="fas fa-file-medical-alt"></i> Medical Records</a></li>
                <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <h2 class="section-title">Medical History</h2>
            
            <?php if ($result->num_rows > 0): ?>
                <div class="stats-grid" style="grid-template-columns: 1fr;"> <!-- Single Column List -->
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
                    <p>No medical records found. Once you complete an appointment, it will appear here.</p>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>