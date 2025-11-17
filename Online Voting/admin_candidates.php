<?php
require __DIR__ . '/config/database.php';
$config = include __DIR__ . '/config/database.php';
$dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
$pdo = new PDO($dsn, $config['user'], $config['pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

if (session_status() === PHP_SESSION_NONE) session_start();
$user = $_SESSION['user'] ?? null;
if (!$user || empty($user['is_admin'])) {
    http_response_code(403);
    echo 'Access denied. You must be an admin.';
    exit;
}

require_once __DIR__ . '/includes/csrf.php';

$errors = [];
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $token = $_POST['csrf_token'] ?? '';
  if (!verify_csrf_token($token)) {
    $errors[] = 'Invalid CSRF token.';
  } else {
    $name = trim($_POST['name'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    if (!$name) {
      $errors[] = 'Candidate name required.';
    } else {
      // handle optional photo upload
      $photoName = null;
      if (!empty($_FILES['photo']['name'])) {
        $up = $_FILES['photo'];
        $allowed = ['image/jpeg','image/png','image/gif'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $up['tmp_name']);
        finfo_close($finfo);
        if (!in_array($mime, $allowed)) {
          $errors[] = 'Invalid photo type. Use jpg/png/gif.';
        } elseif ($up['size'] > 2 * 1024 * 1024) {
          $errors[] = 'Photo too large (max 2MB).';
        } else {
          $ext = pathinfo($up['name'], PATHINFO_EXTENSION);
          $photoName = 'cand_' . time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
          move_uploaded_file($up['tmp_name'], __DIR__ . '/uploads/' . $photoName);
        }
      }

      if (empty($errors)) {
        $ins = $pdo->prepare('INSERT INTO candidates (name, description, photo) VALUES (?, ?, ?)');
        $ins->execute([$name, $desc, $photoName]);
        header('Location: admin_candidates.php?added=1');
        exit;
      }
    }
  }
}

// fetch candidates
$stmt = $pdo->query('SELECT * FROM candidates ORDER BY id ASC');
$candidates = $stmt->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/includes/header.php';
?>
<div class="row">
  <div class="col-md-8">
    <h2>Manage Candidates</h2>
    <?php if(!empty($_GET['added'])): ?><div class="alert alert-success">Candidate added.</div><?php endif; ?>
    <?php if($errors): ?><div class="alert alert-danger"><ul><?php foreach($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?></ul></div><?php endif; ?>

    <form method="post" class="mb-4" enctype="multipart/form-data">
      <?php csrf_input(); ?>
      <div class="mb-3">
        <label class="form-label">Name</label>
        <input class="form-control" name="name" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea class="form-control" name="description"></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Photo (optional)</label>
        <input class="form-control" type="file" name="photo" accept="image/*">
      </div>
      <button class="btn btn-primary">Add Candidate</button>
    </form>

    <h4>Existing Candidates</h4>
    <div class="list-group">
      <?php foreach($candidates as $c): ?>
        <div class="list-group-item">
          <div class="d-flex vote-item">
            <?php if (!empty($c['photo'])): ?><img src="uploads/<?php echo htmlspecialchars($c['photo']); ?>" class="cand-photo" alt="<?php echo htmlspecialchars($c['name']); ?>"/><?php endif; ?>
            <div>
              <h5><?php echo htmlspecialchars($c['name']); ?></h5>
              <p><?php echo nl2br(htmlspecialchars($c['description'])); ?></p>
            </div>
          </div>
          <a class="btn btn-sm btn-secondary" href="admin_edit_candidate.php?id=<?php echo (int)$c['id']; ?>">Edit</a>
          <form method="post" action="admin_delete_candidate.php" style="display:inline" onsubmit="return confirm('Delete candidate?');">
            <?php csrf_input(); ?>
            <input type="hidden" name="id" value="<?php echo (int)$c['id']; ?>">
            <button class="btn btn-sm btn-danger">Delete</button>
          </form>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="col-md-4">
    <h4>Quick Links</h4>
    <ul>
      <li><a href="../">Back to site root</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>