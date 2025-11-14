<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
$_SESSION['success_msg'] = "You have been logged out successfully.";
session_unset();
session_destroy();
header("Location: login.php");
exit();
?>