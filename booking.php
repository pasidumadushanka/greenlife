<?php
session_start();
include 'config/db_conn.php';
include 'includes/header.php';

// Login වී නැත්නම් Login පිටුවට යවන්න
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login to book an appointment.'); window.location.href='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];
$error = "";
$success = "";

// 1. Services ටික Database එකෙන් ගන්න (Dropdown එකට)
$services_result = $conn->query("SELECT * FROM services");

// 2. Therapists ටික ගන්න
$therapists_result = $conn->query("SELECT id, fullname FROM users WHERE role='therapist'");

// 3. Form Submission Handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service_id = $_POST['service_id'];
    $therapist_id = !empty($_POST['therapist_id']) ? $_POST['therapist_id'] : "NULL"; // Therapist කෙනෙක් තෝරා නැත්නම් NULL
    $date = $_POST['date'];
    $time = $_POST['time'];
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // Validation: අද දිනට පෙර දිනයක් තෝරා ඇත්දැයි බැලීම
    if (strtotime($date) < strtotime(date('Y-m-d'))) {
        $error = "Please select a valid future date.";
    } else {
        // Appointment එක Database එකට දැමීම
        $sql = "INSERT INTO appointments (client_id, therapist_id, service_id, appointment_date, appointment_time, message, status) 
                VALUES ('$user_id', $therapist_id, '$service_id', '$date', '$time', '$message', 'pending')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>
                alert('Appointment Request Sent Successfully!');
                window.location.href='client/dashboard.php';
            </script>";
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

<div class="container booking-container">
    
    <!-- Left Side: Booking Form -->
    <div class="glass booking-form-card">
        <h2 style="margin-bottom: 20px; color: var(--primary);">Book Your Session</h2>
        
        <?php if($error): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            
            <!-- Service Selection -->
            <div class="form-group">
                <label>Select Service</label>
                <!-- id="serviceSelect" දුන්නේ JavaScript වලින් අල්ලගන්න -->
                <select name="service_id" id="serviceSelect" class="form-control" required onchange="updateSummary()">
                    <option value="" data-price="0">-- Choose a Wellness Service --</option>
                    <?php 
                    if ($services_result->num_rows > 0) {
                        while($s = $services_result->fetch_assoc()) {
                            // data-price attribute එකෙන් Price එක HTML එක ඇතුලේ හංගනවා JS වලට ගන්න
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
            <div style="display: flex; gap: 20px;">
                <div class="form-group" style="flex: 1;">
                    <label>Date</label>
                    <input type="date" name="date" class="form-control" min="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Preferred Time</label>
                    <input type="time" name="time" class="form-control" required>
                </div>
            </div>

            <!-- Message -->
            <div class="form-group">
                <label>Special Notes / Symptoms</label>
                <textarea name="message" class="form-control" rows="3" placeholder="Any specific requirements?"></textarea>
            </div>

            <button type="submit" class="btn-main" style="width: 100%; margin-top: 10px;">Confirm Booking</button>
        </form>
    </div>

    <!-- Right Side: Live Summary -->
    <div class="glass booking-summary-card">
        <h3 style="margin-bottom: 20px;">Booking Summary</h3>
        
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

        <div style="margin-top: 30px; border-top: 1px solid var(--glass-border); padding-top: 10px;">
            <p style="text-align: right; color: #a0aec0;">Total Amount</p>
            <div class="total-price" id="totalAmount">500.00 LKR</div>
        </div>
        
        <p style="font-size: 0.8rem; color: #64748b; margin-top: 20px; text-align: center;">
            <i class="fas fa-lock"></i> Payment will be collected at the center.
        </p>
    </div>

</div>

<!-- JavaScript to Update Price Dynamically -->
<script>
    function updateSummary() {
        var select = document.getElementById('serviceSelect');
        // Select කරපු Option එකේ 'data-price' එක ගැනීම
        var price = parseFloat(select.options[select.selectedIndex].getAttribute('data-price')) || 0;
        
        var bookingFee = 500;
        var tax = price * 0.05; // 5% tax
        var total = price + bookingFee + tax;

        // HTML Update කිරීම
        document.getElementById('displayPrice').innerText = price.toFixed(2) + " LKR";
        document.getElementById('taxAmount').innerText = tax.toFixed(2) + " LKR";
        document.getElementById('totalAmount').innerText = total.toFixed(2) + " LKR";
    }
</script>

<?php include 'includes/footer.php'; ?>