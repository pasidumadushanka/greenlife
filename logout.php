<?php
session_save_path('/tmp'); // Vercel සදහා path එක
session_start();

// Session Variables සියල්ල ඉවත් කිරීම
$_SESSION = array();

// Session Cookie එකත් ඉවත් කිරීම (Browser එකෙන්)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Session එක විනාශ කිරීම
session_destroy();

// Login පිටුවට යැවීම
header("Location: login.php");
exit();
?>