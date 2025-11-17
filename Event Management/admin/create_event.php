<?php
session_start();
if(!isset($_SESSION['admin']) || $_SESSION['admin'] !== true){
    header('Location: login.php');
    exit;
}
require_once __DIR__ . '/../config/database.php';
include __DIR__ . '/../includes/header.php';

$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $event_date = trim($_POST['event_date'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $capacity = (int)($_POST['capacity'] ?? 0);

    if($title === '' || $event_date === ''){
        $errors[] = 'Title and date are required.';
    }

    $imageName = null;
    if(!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK){
        $f = $_FILES['image'];
        $info = @getimagesize($f['tmp_name']);
        $allowed = [IMAGETYPE_JPEG=>'jpg', IMAGETYPE_PNG=>'png', IMAGETYPE_GIF=>'gif'];
        if(!$info || !isset($allowed[$info[2]])){
            $errors[] = 'Uploaded file is not a supported image.';
        } elseif($f['size'] > 2 * 1024 * 1024) {
            $errors[] = 'Image exceeds 2MB.';
        } else {
            $ext = $allowed[$info[2]];
            $imageName = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
            $dest = __DIR__ . '/../images/uploads/' . $imageName;
            if(!move_uploaded_file($f['tmp_name'], $dest)){
                $errors[] = 'Failed to move uploaded image.';
                $imageName = null;
            }
        }
    }

    if(empty($errors)){
        try{
            $ins = $pdo->prepare('INSERT INTO events (title,description,event_date,location,capacity,image) VALUES (?,?,?,?,?,?)');
            $ins->execute([$title,$description,$event_date,$location,$capacity,$imageName]);
        } catch (PDOException $ex){
            // If `image` column is missing, try without it
            if(stripos($ex->getMessage(),'unknown column') !== false){
                $ins = $pdo->prepare('INSERT INTO events (title,description,event_date,location,capacity) VALUES (?,?,?,?,?)');
                $ins->execute([$title,$description,$event_date,$location,$capacity]);
            } else {
                $errors[] = 'Database error: ' . $ex->getMessage();
            }
        }
    }

    if(empty($errors)){
        echo '<div class="success">Event created successfully.</div>';
    }
}
?>
<h2>Create Event</h2>
<?php if(!empty($errors)): ?>
    <div class="alert">
        <?php foreach($errors as $err) echo '<div>'.htmlspecialchars($err).'</div>'; ?>
    </div>
<?php endif; ?>

<form id="create-event" method="post" enctype="multipart/form-data">
    <div class="form-row"><label>Title</label><input name="title" type="text" required></div>
    <div class="form-row"><label>Description</label><textarea name="description"></textarea></div>
    <div class="form-row"><label>Date & Time</label><input name="event_date" type="datetime-local" required></div>
    <div class="form-row"><label>Location</label><input name="location" type="text"></div>
    <div class="form-row"><label>Capacity (0 for unlimited)</label><input name="capacity" type="number" min="0" value="0"></div>
    <div class="form-row"><label>Image (optional, JPG/PNG/GIF, max 2MB)</label><input name="image" type="file" accept="image/*"></div>
    <div class="form-row"><button type="submit">Create</button></div>
</form>

<p><a href="dashboard.php">Back to dashboard</a></p>

<?php include __DIR__ . '/../includes/footer.php'; ?>
