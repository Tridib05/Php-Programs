<?php
require_once __DIR__ . '/config/database.php';
include __DIR__ . '/includes/header.php';

$event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if($event_id <= 0){
    echo '<p class="alert">Invalid event ID.</p>';
    include __DIR__ . '/includes/footer.php';
    exit;
}

// Fetch event
$est = $pdo->prepare('SELECT title FROM events WHERE id = ?');
$est->execute([$event_id]);
$event = $est->fetch();
if(!$event){
    echo '<p class="alert">Event not found.</p>';
    include __DIR__ . '/includes/footer.php';
    exit;
}

$stmt = $pdo->prepare('SELECT name,email,phone,registered_at FROM registrations WHERE event_id = ? ORDER BY registered_at ASC');
$stmt->execute([$event_id]);
$regs = $stmt->fetchAll();
?>
<h2>Participants for: <?php echo htmlspecialchars($event['title']); ?></h2>
<?php if(empty($regs)): ?>
    <p class="small">No participants yet.</p>
<?php else: ?>
    <table class="table">
        <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Phone</th><th>Registered At</th></tr></thead>
        <tbody>
            <?php $i=1; foreach($regs as $r): ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo htmlspecialchars($r['name']); ?></td>
                <td><?php echo htmlspecialchars($r['email']); ?></td>
                <td><?php echo htmlspecialchars($r['phone']); ?></td>
                <td><?php echo htmlspecialchars($r['registered_at']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<p><a href="event_detail.php?id=<?php echo (int)$event_id; ?>">Back to event</a></p>

<?php include __DIR__ . '/includes/footer.php';
