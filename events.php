<?php
require_once 'db.php';
include 'header.php';

$stmt = $pdo->query("SELECT * FROM events ORDER BY start_datetime ASC");
$events = $stmt->fetchAll();
?>
<h2>Upcoming Events</h2>

<div class="events-grid">
<?php foreach($events as $e): ?>
  <article class="event-card">
    <h3><?= htmlspecialchars($e['title']) ?></h3>
    <p><?= htmlspecialchars(substr($e['description'], 0, 150)) ?><?= strlen($e['description']) > 150 ? '...' : '' ?></p>
    
    <div class="event-details">
      <span><?= date('d M Y, H:i', strtotime($e['start_datetime'])) ?></span>
      <span class="price">₹<?= number_format($e['price'], 2) ?></span>
    </div>

    <p class="meta">📍 <?= htmlspecialchars($e['location']) ?></p>

    <div class="event-actions">
      <a class="btn" href="book.php?event_id=<?= $e['id'] ?>">Book Now</a>
    </div>
  </article>
<?php endforeach; ?>
</div>
<?php include 'footer.php'; ?>