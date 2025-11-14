<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="form">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['user_name']); ?>!</h2>
    <p>You are logged in as <strong><?= htmlspecialchars($_SESSION['user_type']); ?></strong>.</p>
    
    <?php if ($_SESSION['user_type'] === 'admin'): ?>
      <p>👑 You have admin privileges.</p>
    <?php else: ?>
      <p>👤 Standard user access.</p>
    <?php endif; ?>

    <a href="logout.php" class="btn mt-3" style="position: relative; top: 17px;">Logout</a>
  </div>
</body>
</html>