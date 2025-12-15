<?php
session_start();
session_destroy(); // Session විනාශ කිරීම
header("Location: login.php"); // නැවත Login එකට යැවීම
exit();
?>