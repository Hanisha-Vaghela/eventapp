<?php
require_once 'db.php';
ensure_logged_in();

$event_id = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch();

if (!$event) {
    header('Location: events.php');
    exit;
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $seats = max(1, (int)($_POST['seats'] ?? 1));
    $notes = trim($_POST['notes'] ?? '');

    // Check total booked seats
    $stmt = $pdo->prepare("SELECT SUM(seats) AS total FROM bookings WHERE event_id = ? AND status = 'booked'");
    $stmt->execute([$event_id]);
    $row = $stmt->fetch();
    $booked = $row['total'] ?? 0;

    if (($booked + $seats) > $event['capacity']) {
        $remaining = max(0, $event['capacity'] - $booked);
        $errors[] = "Not enough seats available. Remaining: {$remaining}";
    } else {
        $stmt = $pdo->prepare("INSERT INTO bookings (user_id, event_id, seats, notes) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $event_id, $seats, $notes]);
        $success = "✅ Booking successful! Your booking ID: " . $pdo->lastInsertId();
    }
}

include 'header.php';
?>

<!-- <style>
/* ========== BOOKING PAGE STYLING ========== */
body {
  background: #f9fafb;
  font-family: 'Poppins', sans-serif;
  color: #1f2937;
}

h2 {
  text-align: center;
  margin: 40px 0 20px;
  font-weight: 600;
  color: #111827;
}

.event-details-grid {
  max-width: 850px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: 1fr;
  gap: 30px;
  background: #fff;
  border-radius: 18px;
  box-shadow: 0 6px 14px rgba(0,0,0,0.08);
  padding: 40px;
}

.event-details-grid p {
  font-size: 0.95rem;
  line-height: 1.6;
  color: #4b5563;
}

.muted {
  color: #6b7280;
  margin-top: 8px;
}

.book-card {
  border-top: 2px solid #4f46e5;
  padding-top: 20px;
}

label {
  display: block;
  margin: 10px 0 6px;
  font-weight: 500;
  color: #374151;
}

input[type="number"], textarea {
  width: 100%;
  padding: 10px;
  border-radius: 8px;
  border: 1px solid #d1d5db;
  font-size: 1rem;
  outline: none;
  transition: border-color 0.2s;
}

input[type="number"]:focus, textarea:focus {
  border-color: #4f46e5;
}

textarea {
  resize: vertical;
  height: 80px;
}

.btn.primary {
  display: inline-block;
  background: #4f46e5;
  color: white;
  border: none;
  padding: 10px 18px;
  border-radius: 10px;
  cursor: pointer;
  font-weight: 500;
  margin-top: 16px;
  transition: background 0.3s ease;
}

.btn.primary:hover {
  background: #3730a3;
}

.success {
  background: #dcfce7;
  color: #166534;
  border-left: 5px solid #16a34a;
  padding: 12px 18px;
  border-radius: 8px;
  margin-bottom: 20px;
  font-weight: 500;
}

.errors {
  background: #fee2e2;
  color: #991b1b;
  border-left: 5px solid #ef4444;
  padding: 12px 18px;
  border-radius: 8px;
  margin-bottom: 10px;
  font-weight: 500;
}
</style> -->

<h2>Book: <?= htmlspecialchars($event['title']) ?></h2>

<div class="event-details-grid">
  <div>
    <p><?= nl2br(htmlspecialchars($event['description'])) ?></p>
    <p class="muted"><?= htmlspecialchars($event['location']) ?> • <?= date('d M Y, H:i', strtotime($event['start_datetime'])) ?></p>
    <p><strong>Capacity:</strong> <?= $event['capacity'] ?> &nbsp; <strong>Price:</strong> ₹<?= number_format($event['price'], 2) ?></p>
  </div>

  <div class="book-card">
    <?php if ($success): ?>
      <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php else: ?>
      <?php if (!empty($errors)): ?>
        <?php foreach ($errors as $er): ?>
          <div class="errors"><p><?= htmlspecialchars($er) ?></p></div>
        <?php endforeach; ?>
      <?php endif; ?>

      <form method="post" id="bookForm">
        <label for="seats">Seats</label>
        <input name="seats" id="seats" type="number" min="1" max="<?= $event['capacity'] ?>" value="1" required>

        <label for="notes">Notes (optional)</label>
        <textarea name="notes" id="notes" placeholder="Any special requirements?"></textarea>

        <button class="btn primary" type="submit">Confirm Booking</button>
      </form>
    <?php endif; ?>
  </div>
</div>

<?php include 'footer.php'; ?>
