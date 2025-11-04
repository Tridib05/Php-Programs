<?php
session_start();
if(!isset($_SESSION['admin']) || $_SESSION['admin'] !== true){
    header('Location: login.php');
    exit;
}
require_once __DIR__ . '/../config/database.php';
include __DIR__ . '/../includes/header.php';

// fetch events
$stmt = $pdo->query('SELECT id,title,DATE(event_date) AS event_date,location,capacity FROM events ORDER BY event_date DESC');
$events = $stmt->fetchAll();
?>
<h2>Admin Dashboard</h2>
<p><a href="create_event.php">Create new event</a> | <a href="logout.php">Logout</a></p>
<?php if(empty($events)): ?>
    <p class="small">No events yet.</p>
<?php else: ?>
    <table class="table">
        <thead><tr><th>#</th><th>Title</th><th>Date</th><th>Location</th><th>Capacity</th></tr></thead>
        <tbody>
        <?php $i=1; foreach($events as $ev): ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo htmlspecialchars($ev['title']); ?></td>
                <td><?php echo htmlspecialchars($ev['event_date']); ?></td>
                <td><?php echo htmlspecialchars($ev['location']); ?></td>
                <td><?php echo (int)$ev['capacity']; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
