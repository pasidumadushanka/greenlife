<?php
include 'config/db_conn.php';
include 'includes/header.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // User ව email එකෙන් සොයන්න
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        
        // Password එක verify කිරීම (Hashed password vs Input password)
        if (password_verify($password, $row['password'])) {
            // Login Success - Session ආරම්භ කිරීම
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['fullname'] = $row['fullname'];
            $_SESSION['role'] = $row['role'];

            // Role එක අනුව අදාල පිටුවට යැවීම
            if($row['role'] == 'admin'){
                header("Location: admin/dashboard.php"); // තාම හදලා නෑ
            } elseif($row['role'] == 'therapist'){
                header("Location: therapist/dashboard.php"); // තාම හදලා නෑ
            } else {
                header("Location: client/dashboard.php"); // Client නම් Home එකට
            }
            exit();

        } else {
            $error = "Invalid Password!";
        }
    } else {
        $error = "No account found with this email!";
    }
}
?>

<div class="auth-container">
    <div class="glass auth-card">
        <h2 style="text-align: center; margin-bottom: 20px;">Welcome Back</h2>
        
        <?php if($error): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
            </div>

            <button type="submit" class="btn-main" style="width: 100%;">Login</button>
        </form>

        <div class="auth-footer">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>