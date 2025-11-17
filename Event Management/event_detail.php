<?php
require_once __DIR__ . '/config/database.php';
include __DIR__ . '/includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if($id <= 0){
    echo '<p class="alert">Invalid event ID.</p>';
    include __DIR__ . '/includes/footer.php';
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM events WHERE id = ?');
$stmt->execute([$id]);
$event = $stmt->fetch();
if(!$event){
    echo '<p class="alert">Event not found.</p>';
    include __DIR__ . '/includes/footer.php';
    exit;
}

// Show event details
?>
<article class="event">
    <?php if(!empty($event['image'])): ?>
        <p><img src="images/uploads/<?php echo htmlspecialchars($event['image']); ?>" alt="<?php echo htmlspecialchars($event['title']); ?>" style="max-width:400px;display:block;margin-bottom:8px;border-radius:4px"></p>
    <?php endif; ?>
    <h2><?php echo htmlspecialchars($event['title']); ?></h2>
    <p class="small">Date: <?php echo htmlspecialchars($event['event_date']); ?> &nbsp;|&nbsp; Location: <?php echo htmlspecialchars($event['location']); ?> &nbsp;|&nbsp; Capacity: <?php echo (int)$event['capacity']; ?></p>
    <p><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
    <p>
        <a href="register.php?id=<?php echo $event['id']; ?>"><button>Register for this event</button></a>
        <a href="participants.php?id=<?php echo $event['id']; ?>" style="margin-left:8px">View participants</a>
    </p>
</article>

<?php include __DIR__ . '/includes/footer.php';
