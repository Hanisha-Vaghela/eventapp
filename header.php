<?php
// inc/header.php
if (session_status() == PHP_SESSION_NONE) session_start();
$loggedIn = isset($_SESSION['user_id']);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>EventApp</title>
  <link rel="stylesheet" href="style.css">
  <script defer src="script.js"></script>
</head>
<body>
<header class="site-header">
  <div class="container header-inner">
    <div class="brand">
      <a href="index.php">Event<span class="accent">App</span></a>
    </div>
    <nav class="main-nav">
      <a href="index.php">Home</a>
      <a href="create_event.php">Create Event</a>
      <a href="events.php">Events</a>
      <?php if($loggedIn): ?>
        <a href="profile.php">Profile</a>
        <?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
          <a href="event_details.php">Bookings</a>
        <?php endif; ?>
        <a href="logout.php" class="btn-ghost">Logout</a>
      <?php else: ?>
        <a href="login.php" class="btn">Login</a>
        <a href="register.php" class="btn">Sign up</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main class="container">
