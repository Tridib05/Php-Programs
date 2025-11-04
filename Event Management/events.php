<?php
require_once __DIR__ . '/config/database.php';
include __DIR__ . '/includes/header.php';

// Fetch upcoming events (now includes image)
$stmt = $pdo->query('SELECT id,title,DATE(event_date) AS event_date,location,capacity, image FROM events ORDER BY event_date ASC');
$events = $stmt->fetchAll();
?>
<h2>Upcoming Events</h2>
<?php if(!$events): ?>
    <p class="small">No events found. Import `setup.sql` to add sample data.</p>
<?php else: ?>
    <?php foreach($events as $e): ?>
        <div class="event">
            <?php if(!empty($e['image'])): ?>
                <p><img src="images/uploads/<?php echo htmlspecialchars($e['image']); ?>" alt="<?php echo htmlspecialchars($e['title']); ?>" style="max-width:200px;display:block;margin-bottom:8px;border-radius:4px"></p>
            <?php endif; ?>
            <h2><?php echo htmlspecialchars($e['title']); ?></h2>
            <p class="small">Date: <?php echo htmlspecialchars($e['event_date']); ?> &nbsp;|&nbsp; Location: <?php echo htmlspecialchars($e['location']); ?></p>
            <p>
                <a href="event_detail.php?id=<?php echo $e['id']; ?>">View details</a>
            </p>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php';
