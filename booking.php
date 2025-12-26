<?php
// Vercel Cookie Fix - No session_start needed
include __DIR__ . '/config/db_conn.php'; // Correct path

// Security Check (Using Cookie)
if (!isset($_COOKIE['user_id'])) {
    echo "<script>alert('Please login to book an appointment.'); window.location.href='login.php';</script>";
    exit();
}

$user_id = $_COOKIE['user_id'];
$user_name = $_COOKIE['fullname'];

$error = "";
$success = "";

// 1. Services ටික Database එකෙන් ගන්න
$services_result = $conn->query("SELECT * FROM services");

// 2. Therapists ටික ගන්න
$therapists_result = $conn->query("SELECT id, fullname FROM users WHERE role='therapist'");

// 3. Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service_id = $_POST['service_id'];
    $therapist_id = !empty($_POST['therapist_id']) ? $_POST['therapist_id'] : "NULL";
    $date = $_POST['date'];
    $time = $_POST['time'];
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    if (strtotime($date) < strtotime(date('Y-m-d'))) {
        $error = "Please select a valid future date.";
    } else {
        $sql = "INSERT INTO appointments (client_id, therapist_id, service_id, appointment_date, appointment_time, message, status) 
                VALUES ('$user_id', $therapist_id, '$service_id', '$date', '$time', '$message', 'pending')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Appointment Request Sent Successfully!'); window.location.href='client/dashboard.php';</script>";
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - GreenLife</title>
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        /* Navbar Fixes */
        .nav-content { display: flex; justify-content: space-between; align-items: center; padding: 15px 0; }
        .logo { font-size: 1.5rem; font-weight: bold; color: var(--primary); text-decoration: none; }

        /* Booking Page Specific Layout */
        .booking-wrapper {
            display: flex;
            gap: 40px;
            padding: 50px 0;
            align-items: flex-start;
            min-height: 80vh;
        }

        .booking-form-card {
            flex: 2;
            padding: 40px;
        }

        .booking-summary-card {
            flex: 1;
            padding: 30px;
            position: sticky;
            top: 100px;
            border: 1px solid var(--primary);
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(255, 255, 255, 0.02));
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--glass-border);
            color: #a0aec0;
        }

        .total-price {
            font-size: 2rem;
            color: var(--primary);
            font-weight: 700;
            text-align: right;
            margin-top: 20px;
            text-shadow: 0 0 15px rgba(16, 185, 129, 0.4);
        }

        /* Inputs Styling Upgrade */
        .form-control {
            background: rgba(0, 0, 0, 0.3) !important;
            border: 1px solid var(--glass-border);
            color: #fff;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            background: rgba(0, 0, 0, 0.5) !important;
        }

        @media (max-width: 900px) {
            .booking-wrapper { flex-direction: column; }
            .booking-summary-card { width: 100%; position: relative; top: 0; }
        }
    </style>
</head>
<body>

    <!-- FIXED NAV BAR -->
    <nav class="glass" style="position: sticky; top: 0; z-index: 100; border-bottom: 1px solid rgba(255,255,255,0.1);">
        <div class="container nav-content">
            <a href="index.php" class="logo"><i class="fas fa-leaf"></i> GreenLife</a>
            <div style="display: flex; gap: 20px; align-items: center;">
                <!-- Go to Dashboard Link -->
                <a href="client/dashboard.php" style="color: #a0aec0; font-size: 0.9rem; margin-right: 10px; text-decoration: none;">
                    <i class="fas fa-arrow-left"></i> Dashboard
                </a>
                
                <span style="color: #e2e8f0; font-size: 0.9rem;">Hello, <b><?php echo explode(' ', $user_name)[0]; ?></b></span>
                <a href="logout.php" class="btn-main" style="padding: 8px 20px; font-size: 0.85rem;">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container booking-wrapper">
        
        <!-- Left Side: Booking Form -->
        <div class="glass booking-form-card">
            <h2 style="margin-bottom: 25px; color: var(--primary);">Book Your Session</h2>
            
            <?php if($error): ?>
                <div style="background: rgba(239,68,68,0.2); color: #fca5a5; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                
                <!-- Service Selection -->
                <div class="form-group">
                    <label>Select Service</label>
                    <select name="service_id" id="serviceSelect" class="form-control" required onchange="updateSummary()">
                        <option value="" data-price="0">-- Choose a Wellness Service --</option>
                        <?php 
                        if ($services_result->num_rows > 0) {
                            while($s = $services_result->fetch_assoc()) {
                                echo "<option value='".$s['id']."' data-price='".$s['price']."'>".$s['service_name']."</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <!-- Therapist Selection -->
                <div class="form-group">
                    <label>Select Therapist (Optional)</label>
                    <select name="therapist_id" class="form-control">
                        <option value="">Any Available Therapist</option>
                        <?php 
                        if ($therapists_result->num_rows > 0) {
                            while($t = $therapists_result->fetch_assoc()) {
                                echo "<option value='".$t['id']."'>Dr. ".$t['fullname']."</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <!-- Date & Time Row -->
                <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                    <div class="form-group" style="flex: 1; min-width: 200px;">
                        <label>Date</label>
                        <input type="date" name="date" class="form-control" min="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="form-group" style="flex: 1; min-width: 200px;">
                        <label>Preferred Time</label>
                        <input type="time" name="time" class="form-control" required>
                    </div>
                </div>

                <!-- Message -->
                <div class="form-group">
                    <label>Special Notes / Symptoms</label>
                    <textarea name="message" class="form-control" rows="3" placeholder="Any specific requirements?"></textarea>
                </div>

                <button type="submit" class="btn-main" style="width: 100%; margin-top: 20px; font-size: 1.1rem;">Confirm Booking</button>
            </form>
        </div>

        <!-- Right Side: Live Summary -->
        <div class="glass booking-summary-card">
            <h3 style="margin-bottom: 25px;">Booking Summary</h3>
            
            <div class="summary-item">
                <span>Service Fee</span>
                <span id="displayPrice">0.00 LKR</span>
            </div>
            <div class="summary-item">
                <span>Booking Fee</span>
                <span>500.00 LKR</span>
            </div>
            <div class="summary-item">
                <span>Tax (5%)</span>
                <span id="taxAmount">0.00 LKR</span>
            </div>

            <div style="margin-top: 30px; border-top: 1px solid var(--glass-border); padding-top: 15px;">
                <p style="text-align: right; color: #a0aec0; margin-bottom: 5px;">Total Amount</p>
                <div class="total-price" id="totalAmount">500.00 LKR</div>
            </div>
            
            <p style="font-size: 0.8rem; color: #64748b; margin-top: 25px; text-align: center;">
                <i class="fas fa-lock"></i> Payment will be collected at the center.
            </p>
        </div>

    </div>

    <!-- JavaScript to Update Price Dynamically -->
    <script>
        function updateSummary() {
            var select = document.getElementById('serviceSelect');
            var price = parseFloat(select.options[select.selectedIndex].getAttribute('data-price')) || 0;
            
            var bookingFee = 500;
            var tax = price * 0.05; 
            var total = price + bookingFee + tax;

            document.getElementById('displayPrice').innerText = price.toFixed(2) + " LKR";
            document.getElementById('taxAmount').innerText = tax.toFixed(2) + " LKR";
            document.getElementById('totalAmount').innerText = total.toFixed(2) + " LKR";
        }
    </script>

</body>
</html>