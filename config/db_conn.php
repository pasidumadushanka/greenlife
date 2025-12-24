<?php
// 1. Cloud Database විස්තර ලබා ගැනීමට උත්සාහ කිරීම (Vercel සඳහා)
$servername = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv('DB_PASSWORD');
$dbname = getenv('DB_NAME');
$port = getenv('DB_PORT');

// 2. Cloud විස්තර නැත්නම්, Localhost භාවිතා කිරීම (XAMPP සඳහා)
if (!$servername) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "greenlife_db";
    $port = 3306;
}

// 3. Connection එක සාදාගැනීම
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// 4. Error Handling
if ($conn->connect_error) {
    // Professional Error Message
    die("
    <div style='color: red; text-align: center; padding: 20px; font-family: Arial;'>
        <h2>System Error!</h2>
        <p>Unable to connect to the database. Please contact the administrator.</p>
        <!-- Debug Info: " . $conn->connect_error . " -->
    </div>
    ");
}
?>