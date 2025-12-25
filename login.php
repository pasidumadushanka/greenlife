<?php
// 1. Output Buffering On කිරීම (Header Issues විසඳීමට මෙය අනිවාර්යයි)
ob_start();

include 'config/db_conn.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        
        if (password_verify($password, $row['password'])) {
            
            // 2. Cookies සකස් කිරීම (පැය 24ක් සඳහා)
            // වැදගත්: "/" මගින් මුළු වෙබ් අඩවියටම මෙය අදාල බව කියයි
            setcookie("user_id", $row['id'], time() + (86400 * 1), "/"); 
            setcookie("fullname", $row['fullname'], time() + (86400 * 1), "/");
            setcookie("role", $row['role'], time() + (86400 * 1), "/");

            // 3. Redirect කිරීම
            if($row['role'] == 'admin'){
                header("Location: admin/dashboard.php");
            } elseif($row['role'] == 'therapist'){
                header("Location: therapist/dashboard.php");
            } else {
                header("Location: client/dashboard.php");
            }
            exit();

        } else {
            $error = "Invalid Password!";
        }
    } else {
        $error = "No account found with this email!";
    }
}

// Header එක Include කිරීම
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

<?php 
include 'includes/footer.php'; 
// Output එක යැවීම
ob_end_flush();
?>