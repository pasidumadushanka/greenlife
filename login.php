<?php
// 1. Error Reporting On කිරීම (Error එකක් ආවොත් එළියට පේන්න)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include __DIR__ . '/config/db_conn.php'; // Absolute path භාවිතා කිරීම

echo "<h3>Debugging Started...</h3>";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    echo "Form Submitted.<br>";
    
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    echo "Email Input: " . $email . "<br>";
    // echo "Password Input: " . $password . "<br>"; // ආරක්ෂාවට පාස්වර්ඩ් එක පෙන්වන්නේ නෑ

    // Database Connection Check
    if ($conn->connect_error) {
        die("Connection Failed: " . $conn->connect_error);
    } else {
        echo "Database Connected Successfully.<br>";
    }

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        echo "User Found in Database.<br>";
        $row = $result->fetch_assoc();
        
        // Password Hash එක බලන්න (Debug සඳහා)
        echo "Stored Hash: " . substr($row['password'], 0, 10) . "...<br>";

        if (password_verify($password, $row['password'])) {
            echo "Password Verified: <b style='color:green'>SUCCESS</b><br>";

            // Cookies සෙට් කිරීම
            setcookie("user_id", $row['id'], time() + (86400 * 1), "/"); 
            setcookie("fullname", $row['fullname'], time() + (86400 * 1), "/");
            setcookie("role", $row['role'], time() + (86400 * 1), "/");

            echo "Cookies Set Command Sent.<br>";
            echo "User Role: " . $row['role'] . "<br>";
            echo "<br><b>Login Logic is Working! The issue might be Redirection.</b>";
            
            // Redirect එක තාවකාලිකව නතර කර ඇත (Error එක බලාගැනීමට)
            // header("Location: client/dashboard.php"); 

        } else {
            echo "Password Verify: <b style='color:red'>FAILED</b><br>";
            echo "Make sure the user was created with password_hash()";
        }
    } else {
        echo "User Search: <b style='color:red'>No user found with this email.</b><br>";
    }
} else {
    echo "Waiting for form submission...<br>";
}
?>

<!-- HTML Form (Debugging සඳහා සරලව) -->
<form method="POST" style="margin: 50px; padding: 20px; border: 1px solid white; color: white;">
    <label>Email:</label><br>
    <input type="email" name="email" required style="color:black"><br><br>
    <label>Password:</label><br>
    <input type="password" name="password" required style="color:black"><br><br>
    <button type="submit" style="color:black">Test Login</button>
</form>