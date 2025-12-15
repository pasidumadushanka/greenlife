<?php
// Database Configuration
$servername = "localhost";
$username = "root";  // XAMPP Default
$password = "";      // XAMPP Default (හිස්ව තියන්න)
$dbname = "greenlife_db";

// Create Connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check Connection & Error Handling
if ($conn->connect_error) {
    // Professional විදිහට Error එක පෙන්නන්න (User ට තාක්ෂණික දේවල් නොපෙන්වා)
    die("
    <div style='color: red; text-align: center; padding: 20px; font-family: Arial;'>
        <h2>System Error!</h2>
        <p>Unable to connect to the database. Please contact the administrator.</p>
        <!-- Developer purposes only: " . $conn->connect_error . " -->
    </div>
    ");
}
?>