<?php
require_once 'db.php';
if(isset($_SESSION['user_id'])) header('Location: index.php');
$error = '';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if($user && password_verify($password, $user['password'])){
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['fname'];
        $_SESSION['is_admin'] = $user['is_admin'];
        header('Location: index.php');
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Login - EventApp</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">
  <div class="auth-card">
    <h2>Login</h2>
    <?php if($error) echo "<div class='errors'><p>".htmlspecialchars($error)."</p></div>"; ?>
    <form method="post" id="loginForm">
      <input name="email" placeholder="Email" required>
      <input name="password" type="password" placeholder="Password" required>
      <button class="btn primary" type="submit">Login</button>
      <p class="muted">No account? <a href="register.php">Sign up</a></p>
    </form>
  </div>
</body>
</html>
