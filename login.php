<?php
// 1. Vercel Session Fix (අනිවාර්යයෙන්ම උඩින්ම තිබිය යුතුයි)
session_save_path('/tmp');
session_start();

// 2. Database Connection
include 'config/db_conn.php';

$error = "";

// 3. Login Logic (HTML පෙන්වීමට පෙර මෙය සිදු විය යුතුයි)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // User ව email එකෙන් සොයන්න
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        
        // Password එක verify කිරීම
        if (password_verify($password, $row['password'])) {
            // Login Success - Session Data සකස් කිරීම
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['fullname'] = $row['fullname'];
            $_SESSION['role'] = $row['role'];

            // Role එක අනුව Redirect කිරීම
            // වැදගත්: මෙතනදි HTML output එකක් යන්න කලින් Redirect වෙන්න ඕන
            if($row['role'] == 'admin'){
                header("Location: admin/dashboard.php");
            } elseif($row['role'] == 'therapist'){
                header("Location: therapist/dashboard.php");
            } else {
                header("Location: client/dashboard.php");
            }
            exit(); // Redirect වුනාම Script එක මෙතනින් නවත්වන්න ඕන

        } else {
            $error = "Invalid Password!";
        }
    } else {
        $error = "No account found with this email!";
    }
}

// 4. Header එක Include කරන්න (Logic එක ඉවර වුනාට පස්සේ)
// මෙතනින් තමයි HTML පටන් ගන්නේ
include 'includes/header.php'; 
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