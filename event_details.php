<?php
require_once 'db.php';
ensure_logged_in();
if(!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']){
    http_response_code(403);
    echo "Access denied.";
    exit;
}
include 'header.php';
$stmt = $pdo->query("SELECT b.*, u.fname,u.lname,u.email, e.title FROM bookings b JOIN users u ON u.id=b.user_id JOIN events e ON e.id=b.event_id ORDER BY b.booked_at DESC");
$bookings = $stmt->fetchAll();
?>
<h2>All Bookings</h2>
<table class="table">
<thead>
<tr>
  <th>ID</th><th>User</th><th>Email</th><th>Event</th><th>Seats</th><th>Booked At</th><th>Status</th>
</tr>
</thead>
<tbody>
<?php foreach($bookings as $b): ?>
<tr>
  <td><?=$b['id']?></td>
  <td><?=htmlspecialchars($b['fname'].' '.$b['lname'])?></td>
  <td><?=htmlspecialchars($b['email'])?></td>
  <td><?=htmlspecialchars($b['title'])?></td>
  <td><?=$b['seats']?></td>
  <td><?=date('d M Y H:i', strtotime($b['booked_at']))?></td>
  <td><?=htmlspecialchars($b['status'])?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php include 'footer.php'; ?>
