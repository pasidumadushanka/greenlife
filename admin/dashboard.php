<?php
session_start();
include '../config/db_conn.php';

// Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Handle Actions (Approve/Reject)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $app_id = $_GET['id'];
    $action = $_GET['action'];
    $new_status = ($action == 'approve') ? 'confirmed' : 'cancelled';
    $conn->query("UPDATE appointments SET status='$new_status' WHERE id=$app_id");
    header("Location: dashboard.php");
    exit();
}

// Data Fetching
$pending_count = $conn->query("SELECT COUNT(*) as count FROM appointments WHERE status='pending'")->fetch_assoc()['count'];
$users_count = $conn->query("SELECT COUNT(*) as count FROM users WHERE role='client'")->fetch_assoc()['count'];
$income_res = $conn->query("SELECT SUM(s.price) as total FROM appointments a JOIN services s ON a.service_id = s.id WHERE a.status='confirmed'");
$total_income = $income_res->fetch_assoc()['total'] ?? 0;

$sql_all = "SELECT a.id, a.appointment_date, a.appointment_time, a.status, 
                   u.fullname as client_name, s.service_name 
            FROM appointments a 
            JOIN users u ON a.client_id = u.id 
            JOIN services s ON a.service_id = s.id 
            ORDER BY a.appointment_date DESC";
$res_all = $conn->query($sql_all);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - GreenLife</title>
    <!-- Main Style -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- Admin Specific Style -->
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <!-- Top Bar -->
    <nav class="glass" style="position: sticky; top: 0; z-index: 100; border-bottom: 1px solid rgba(255,255,255,0.1);">
        <div class="container nav-content">
            <a href="#" class="logo"><i class="fas fa-shield-alt"></i> GreenLife <span style="font-size: 0.8rem; color: var(--primary);">ADMIN</span></a>
            <div style="display: flex; gap: 20px; align-items: center;">
                <span style="color: #fff;">Welcome, Admin</span>
                <a href="../logout.php" class="btn-main" style="padding: 5px 15px; font-size: 0.8rem; background: #fff; color: #000;">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Layout Container -->
    <div class="dashboard-container">
        
        <!-- Sidebar -->
        <aside class="sidebar-nav">
            <ul class="sidebar-menu" style="list-style: none; padding: 0;">
                <li style="margin-bottom: 15px;"><a href="dashboard.php" style="color: var(--primary); font-weight: bold;"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li style="margin-bottom: 15px;"><a href="#" style="color: #a0aec0;"><i class="fas fa-users"></i> Manage Users</a></li>
                <li style="margin-bottom: 15px;"><a href="#" style="color: #a0aec0;"><i class="fas fa-spa"></i> Services</a></li>
                <li style="margin-bottom: 15px;"><a href="#" style="color: #a0aec0;"><i class="fas fa-file-alt"></i> Reports</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            
            <h2 style="margin-bottom: 25px; color: #fff;">System Overview</h2>

            <!-- Stats Grid (මෙන්න මෙතන තමයි Class එක වෙනස් කලේ) -->
            <div class="admin-stats-grid">
                
                <!-- Pending Requests -->
                <div class="stat-card card-orange">
                    <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $pending_count; ?></h3>
                        <p>Pending Requests</p>
                    </div>
                </div>

                <!-- Total Clients -->
                <div class="stat-card card-blue">
                    <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $users_count; ?></h3>
                        <p>Active Clients</p>
                    </div>
                </div>

                <!-- Total Revenue -->
                <div class="stat-card card-green">
                    <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo number_format($total_income, 2); ?></h3>
                        <p>Total Revenue (LKR)</p>
                    </div>
                </div>
            </div>

            <!-- Management Table -->
            <div class="admin-table-container">
                <h3 style="margin-bottom: 20px; color: #fff;">Appointment Management</h3>
                
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Service</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($res_all->num_rows > 0): ?>
                            <?php while($row = $res_all->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo $row['id']; ?></td>
                                    <td><?php echo $row['client_name']; ?></td>
                                    <td><?php echo $row['service_name']; ?></td>
                                    <td><?php echo $row['appointment_date']; ?> <small>(<?php echo $row['appointment_time']; ?>)</small></td>
                                    <td>
                                        <?php 
                                            $st = $row['status'];
                                            $color = ($st=='confirmed') ? '#34d399' : (($st=='cancelled') ? '#f87171' : '#fbbf24');
                                            echo "<span style='color: $color; font-weight: bold; text-transform: capitalize;'>$st</span>";
                                        ?>
                                    </td>
                                    <td>
                                        <?php if($row['status'] == 'pending'): ?>
                                            <a href="dashboard.php?action=approve&id=<?php echo $row['id']; ?>" class="btn-action btn-approve"><i class="fas fa-check"></i></a>
                                            <a href="dashboard.php?action=reject&id=<?php echo $row['id']; ?>" class="btn-action btn-reject" onclick="return confirm('Cancel this?');"><i class="fas fa-times"></i></a>
                                        <?php else: ?>
                                            <span style="color: #64748b; font-size: 0.8rem;">Completed</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6" style="text-align: center;">No data found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </main>
    </div>

</body>
</html>