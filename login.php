<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'connection.php';

$msg = '';

if (isset($_SESSION['success_msg'])) {
    $msg = $_SESSION['success_msg'];
    unset($_SESSION['success_msg']);
} elseif (isset($_GET['msg'])) {
    $msg = htmlspecialchars($_GET['msg']);
}

if (isset($_POST['login_submit'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $msg = "Email and password are required.";
    } else {
        $stmt = $conn->prepare("SELECT id, name, password, user_type FROM users WHERE email = ?");
        if (!$stmt) {
            $msg = "Database error: " . $conn->error;
        } else {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $res = $stmt->get_result();

            if ($res->num_rows === 1) {
                $user = $res->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['logged_in'] = true;
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_type'] = $user['user_type'];

                    header("Location: dashboard.php");
                    exit();
                } else {
                    $msg = "Invalid password.";
                }
            } else {
                $msg = "Email not found.";
            }
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
  <div class="form">
    <h2>Login</h2>
    <?php if ($msg): ?>
      <p class="msg <?= strpos($msg, 'successful') !== false ? 'success' : 'error' ?>"><?= $msg ?></p>
    <?php endif; ?>
    <form method="POST">
      <div class="form-group">
        <input type="email" name="email" placeholder="Enter your email" class="form-control" required>
      </div>
      <div class="form-group">
        <input type="password" name="password" placeholder="Enter your password" class="form-control" required>
      </div>
      <button type="submit" class="btn" name="login_submit">Login Now</button>
      <p>Don’t have an account? <a href="index.php">Register Now</a></p>
    </form>
  </div>
</body>
</html>