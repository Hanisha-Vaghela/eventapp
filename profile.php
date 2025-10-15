<?php
require_once 'db.php';
ensure_logged_in();
include 'header.php';
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

$stmt = $pdo->prepare("SELECT b.*, e.title, e.start_datetime FROM bookings b JOIN events e ON e.id=b.event_id WHERE b.user_id = ? ORDER BY b.booked_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$bookings = $stmt->fetchAll();
?>
<h2>Profile</h2>
<div class="profile-grid">
  <div class="profile-card">
    <h3><?=htmlspecialchars($user['fname'].' '.$user['lname'])?></h3>
    <p><strong>Email:</strong> <?=htmlspecialchars($user['email'])?></p>
    <p><strong>Mobile:</strong> <?=htmlspecialchars($user['mobile'])?></p>
    <p><strong>DOB:</strong> <?=htmlspecialchars($user['dob'])?></p>
    <p><strong>Location:</strong> <?=htmlspecialchars($user['city'].', '.$user['state'])?></p>
    <p><strong>Address:</strong> <?=nl2br(htmlspecialchars($user['address']))?></p>
  </div>
  <div class="bookings-card">
    <h3>Your Bookings</h3>
    <?php if($bookings): ?>
      <ul class="booking-list">
        <?php foreach($bookings as $b): ?>
          <li>
            <strong><?=htmlspecialchars($b['title'])?></strong> • <?=date('d M Y, H:i', strtotime($b['start_datetime']))?>
            <div class="muted">Seats: <?=$b['seats']?> • Status: <?=htmlspecialchars($b['status'])?></div>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
      <p>No bookings yet. <a href="events.php">Browse events</a></p>
    <?php endif; ?>
  </div>
</div>
<?php include 'footer.php'; ?>
