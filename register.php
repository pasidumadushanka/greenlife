<?php
include 'config/db_conn.php';
include 'includes/header.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $role = $_POST['role']; // User තෝරන Role එක ලබා ගැනීම

    if ($password !== $cpassword) {
        $error = "Passwords do not match!";
    } else {
        $check_email = "SELECT * FROM users WHERE email='$email'";
        $result = $conn->query($check_email);

        if ($result->num_rows > 0) {
            $error = "Email already registered!";
        } else {
            $hashed_pass = password_hash($password, PASSWORD_DEFAULT);

            // Role එකත් එක්ක Database එකට යැවීම
            $sql = "INSERT INTO users (fullname, email, password, phone, role) 
                    VALUES ('$fullname', '$email', '$hashed_pass', '$phone', '$role')";
            
            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Registration Successful as $role! Please Login.'); window.location.href='login.php';</script>";
            } else {
                $error = "Error: " . $conn->error;
            }
        }
    }
}
?>

<div class="auth-container">
    <div class="glass auth-card">
        <h2 style="text-align: center; margin-bottom: 20px;">Join GreenLife</h2>
        
        <?php if($error): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Register As:</label>
                <select name="role" class="form-control" style="background: rgba(0,0,0,0.5);">
                    <option value="client">Client (Patient)</option>
                    <option value="therapist">Therapist (Doctor)</option>
                    <option value="admin">Admin (Management)</option>
                </select>
            </div>

            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="fullname" class="form-control" placeholder="Enter full name" required>
            </div>
            
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="example@gmail.com" required>
            </div>

            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" class="form-control" placeholder="07XXXXXXXX" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Create password" required>
            </div>

            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="cpassword" class="form-control" placeholder="Confirm password" required>
            </div>

            <button type="submit" class="btn-main" style="width: 100%;">Create Account</button>
        </form>

        <div class="auth-footer">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>