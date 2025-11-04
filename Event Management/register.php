<?php
require_once __DIR__ . '/config/database.php';
include __DIR__ . '/includes/header.php';

$event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    // Handle form submission
    $event_id = isset($_POST['event_id']) ? (int)$_POST['event_id'] : 0;
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    $errors = [];
    if($event_id <= 0) $errors[] = 'Invalid event.';
    if($name === '') $errors[] = 'Name is required.';
    if($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';

    // Check event exists and capacity
    if(empty($errors)){
        $stmt = $pdo->prepare('SELECT capacity FROM events WHERE id = ?');
        $stmt->execute([$event_id]);
        $ev = $stmt->fetch();
        if(!$ev){
            $errors[] = 'Event not found.';
        } else {
            // count registrations
            $cstmt = $pdo->prepare('SELECT COUNT(*) FROM registrations WHERE event_id = ?');
            $cstmt->execute([$event_id]);
            $count = (int)$cstmt->fetchColumn();
            if($ev['capacity'] > 0 && $count >= $ev['capacity']){
                $errors[] = 'Event is full.';
            }
        }
    }

    if(empty($errors)){
        // prevent duplicate registration for same email + event
        $check = $pdo->prepare('SELECT id FROM registrations WHERE event_id = ? AND email = ?');
        $check->execute([$event_id, $email]);
        if($check->fetch()){
            $errors[] = 'You have already registered for this event with this email.';
        }
    }

    if(empty($errors)){
        $ins = $pdo->prepare('INSERT INTO registrations (event_id,name,email,phone,registered_at) VALUES (?,?,?,?,NOW())');
        $ins->execute([$event_id,$name,$email,$phone]);
        echo '<div class="event"><p class="success">Registration successful. Thank you, '.htmlspecialchars($name).'.</p>
            <p><a href="event_detail.php?id='.$event_id.'">Back to event</a></p></div>';
        include __DIR__ . '/includes/footer.php';
        exit;
    }
}

// Show registration form
if($event_id <= 0){
    echo '<p class="alert">Missing event ID.</p>';
    include __DIR__ . '/includes/footer.php';
    exit;
}

$stmt = $pdo->prepare('SELECT id,title FROM events WHERE id = ?');
$stmt->execute([$event_id]);
$event = $stmt->fetch();
if(!$event){
    echo '<p class="alert">Event not found.</p>';
    include __DIR__ . '/includes/footer.php';
    exit;
}
?>
<article class="event">
    <h2>Register for: <?php echo htmlspecialchars($event['title']); ?></h2>

    <?php if(!empty($errors)): ?>
        <div class="alert">
            <?php foreach($errors as $err) echo '<div>'.htmlspecialchars($err).'</div>'; ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <input type="hidden" name="event_id" value="<?php echo (int)$event['id']; ?>">
        <div class="form-row">
            <label for="name">Full name</label>
            <input id="name" name="name" type="text" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
        </div>
        <div class="form-row">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
        </div>
        <div class="form-row">
            <label for="phone">Phone (optional)</label>
            <input id="phone" name="phone" type="text" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
        </div>
        <div class="form-row">
            <button type="submit">Submit registration</button>
        </div>
    </form>

    <p><a href="event_detail.php?id=<?php echo (int)$event['id']; ?>">Back to event</a></p>
</article>

<?php include __DIR__ . '/includes/footer.php';
