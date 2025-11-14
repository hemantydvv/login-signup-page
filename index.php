<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'connection.php'; // Fixed filename

$msg = '';

if (isset($_POST['submit'])) {
    $user_type = trim($_POST['user_type']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $cpassword = trim($_POST['cpassword']);

    if (empty($user_type) || empty($name) || empty($email) || empty($password) || empty($cpassword)) {
        $msg = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Invalid email format.";
    } elseif ($password !== $cpassword) {
        $msg = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $msg = "Password must be at least 6 characters.";
    } else {
        $check_email = $conn->prepare("SELECT id FROM users WHERE email = ?");
        if (!$check_email) {
            $msg = "Database error: " . $conn->error;
        } else {
            $check_email->bind_param("s", $email);
            $check_email->execute();
            $res = $check_email->get_result();

            if ($res->num_rows > 0) {
                $msg = "Email already registered.";
            } else {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (user_type, name, email, password) VALUES (?, ?, ?, ?)");
                if (!$stmt) {
                    $msg = "Database error: " . $conn->error;
                } else {
                    $stmt->bind_param("ssss", $user_type, $name, $email, $hashed);

                    if ($stmt->execute()) {
                        $_SESSION['success_msg'] = "Registration successful! Please login.";
                        header("Location: login.php");
                        exit();
                    } else {
                        $msg = "Registration failed: " . $stmt->error;
                    }
                    $stmt->close();
                }
            }
            $check_email->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
  <div class="form">
    <form method="POST">
      <h2>Register</h2>
      <?php if ($msg): ?>
        <p class="msg <?= strpos($msg, 'successful') !== false ? 'success' : 'error' ?>"><?= htmlspecialchars($msg) ?></p>
      <?php endif; ?>

      <div class="form-group">
        <select name="user_type" class="form-control" required>
          <option value="">Select user type</option>
          <option value="user">User</option>
          <option value="admin">Admin</option>
        </select>
      </div>

      <div class="form-group">
        <input type="text" name="name" placeholder="Enter your name" class="form-control" required>
      </div>

      <div class="form-group">
        <input type="email" name="email" placeholder="Enter your email" class="form-control" required>
      </div>

      <div class="form-group">
        <input type="password" name="password" placeholder="Enter your password" class="form-control" required>
      </div>

      <div class="form-group">
        <input type="password" name="cpassword" placeholder="Confirm your password" class="form-control" required>
      </div>

      <button type="submit" class="btn" name="submit">Register Now</button>
      <p>Already have an account? <a href="login.php">Login Now</a></p>
    </form>
  </div>
</body>
</html>