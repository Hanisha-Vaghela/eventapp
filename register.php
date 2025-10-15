<?php
require_once 'db.php';
if(isset($_SESSION['user_id'])) header('Location: index.php');
$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = trim($_POST['fname'] ?? '');
    $lname = trim($_POST['lname'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $mobile = trim($_POST['mobile'] ?? '');
    $password = $_POST['password'] ?? '';
    $dob = $_POST['dob'] ?? null;
    $address = trim($_POST['address'] ?? '');
    $pincode = trim($_POST['pincode'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $state = trim($_POST['state'] ?? '');
    $region = trim($_POST['region'] ?? '');

    if(!$fname || !$lname) $errors[] = "First and Last name are required.";
    if(!$email) $errors[] = "Valid email is required.";
    if(strlen($password) < 6) $errors[] = "Password must be at least 6 characters.";
    // additional validations (phone, pincode) can be added

    if(empty($errors)){
        // check existing
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if($stmt->fetch()) {
            $errors[] = "Email already registered.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (fname,lname,email,mobile,password,dob,address,pincode,city,state,region) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->execute([$fname,$lname,$email,$mobile,$hash,$dob,$address,$pincode,$city,$state,$region]);
            $userId = $pdo->lastInsertId();
            // log in user
            session_regenerate_id(true);
            $_SESSION['user_id'] = $userId;
            $_SESSION['user_name'] = $fname;
            $_SESSION['is_admin'] = 0;
            header('Location: index.php');
            exit;
        }
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Register - EventApp</title>
  <link rel="stylesheet" href="style.css">
  <script defer src="script.js"></script>
</head>
<body class="auth-page">
  <div class="auth-card">
    <h2>Create Account</h2>
    <?php if(!empty($errors)): ?>
      <div class="errors">
        <?php foreach($errors as $e) echo "<p>".htmlspecialchars($e)."</p>"; ?>
      </div>
    <?php endif; ?>
    <form method="post" id="registerForm">
      <div class="grid-2">
        <input name="fname" placeholder="First name" required value="<?=htmlspecialchars($_POST['fname'] ?? '')?>">
        <input name="lname" placeholder="Last name" required value="<?=htmlspecialchars($_POST['lname'] ?? '')?>">
      </div>
      <input name="email" placeholder="Email" required value="<?=htmlspecialchars($_POST['email'] ?? '')?>">
      <div class="grid-2">
        <input name="mobile" placeholder="Mobile No" value="<?=htmlspecialchars($_POST['mobile'] ?? '')?>">
        <input name="dob" type="date" placeholder="DOB" value="<?=htmlspecialchars($_POST['dob'] ?? '')?>">
      </div>
      <input name="password" type="password" placeholder="Password" required>
      <textarea name="address" placeholder="Address"><?=htmlspecialchars($_POST['address'] ?? '')?></textarea>
      <div class="grid-3">
        <input name="pincode" placeholder="Pin code" value="<?=htmlspecialchars($_POST['pincode'] ?? '')?>">
        <input name="city" placeholder="City" value="<?=htmlspecialchars($_POST['city'] ?? '')?>">
        <input name="state" placeholder="State" value="<?=htmlspecialchars($_POST['state'] ?? '')?>">
      </div>
      <input name="region" placeholder="Region" value="<?=htmlspecialchars($_POST['region'] ?? '')?>">
      <button class="btn primary" type="submit">Register</button>
      <p class="muted">Already have account? <a href="login.php">Login</a></p>
    </form>
  </div>
</body>
</html>
