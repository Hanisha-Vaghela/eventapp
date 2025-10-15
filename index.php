<?php
require_once 'db.php';
include 'header.php';
$stmt = $pdo->query("SELECT * FROM events ORDER BY start_datetime ASC LIMIT 6");
$events = $stmt->fetchAll();
?>
<section class="hero">
  <div>
    <h1>Discover & Book Amazing Events</h1>
    <p>Workshops, conferences, festivals, and meetups — find the right event and reserve your seat easily.</p>
    <a href="events.php" class="btn primary">Browse All Events</a>
  </div>

  <div class="hero-card">
    <?php if(count($events)): ?>
      <?php foreach($events as $e): ?>
        <div class="event-short">
          <h3><?=htmlspecialchars($e['title'])?></h3>
          <p><?=htmlspecialchars(substr($e['description'],0,120))?>...</p>
          <p class="muted">
            <?=date('d M Y, H:i', strtotime($e['start_datetime']))?> • 
            <?=htmlspecialchars($e['location'])?>
          </p>
          <a href="book.php?event_id=<?=$e['id']?>" class="btn">Book Now</a>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>No events yet. Check back soon!</p>
    <?php endif; ?>
  </div>
</section>

<?php include 'footer.php'; ?>
