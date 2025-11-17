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

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare('SELECT * FROM candidates WHERE id = ?');
$stmt->execute([$id]);
$cand = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$cand) {
    echo 'Candidate not found.';
    exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($token)) {
        $errors[] = 'Invalid CSRF token.';
    } else {
        $name = trim($_POST['name'] ?? '');
        $desc = trim($_POST['description'] ?? '');
        if (!$name) {
            $errors[] = 'Name required.';
        } else {
      // handle optional photo upload
      $photoName = $cand['photo'] ?? null;
      if (!empty($_FILES['photo']['name'])) {
        $upf = $_FILES['photo'];
        $allowed = ['image/jpeg','image/png','image/gif'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $upf['tmp_name']);
        finfo_close($finfo);
        if (!in_array($mime, $allowed)) {
          $errors[] = 'Invalid photo type. Use jpg/png/gif.';
        } elseif ($upf['size'] > 2 * 1024 * 1024) {
          $errors[] = 'Photo too large (max 2MB).';
        } else {
          $ext = pathinfo($upf['name'], PATHINFO_EXTENSION);
          $photoName = 'cand_' . time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
          move_uploaded_file($upf['tmp_name'], __DIR__ . '/uploads/' . $photoName);
          // delete previous photo if present
          if (!empty($cand['photo']) && file_exists(__DIR__ . '/uploads/' . $cand['photo'])) {
            @unlink(__DIR__ . '/uploads/' . $cand['photo']);
          }
        }
      }

      $up = $pdo->prepare('UPDATE candidates SET name = ?, description = ?, photo = ? WHERE id = ?');
      $up->execute([$name, $desc, $photoName, $id]);
            header('Location: admin_candidates.php?updated=1');
            exit;
        }
    }
}

include __DIR__ . '/includes/header.php';
?>
<div class="row">
  <div class="col-md-8">
    <h2>Edit Candidate</h2>
    <?php if($errors): ?><div class="alert alert-danger"><ul><?php foreach($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?></ul></div><?php endif; ?>
    <form method="post" enctype="multipart/form-data">
      <?php csrf_input(); ?>
      <div class="mb-3">
        <label class="form-label">Name</label>
        <input class="form-control" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? $cand['name']); ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea class="form-control" name="description"><?php echo htmlspecialchars($_POST['description'] ?? $cand['description']); ?></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Photo (optional)</label>
        <?php if(!empty($cand['photo'])): ?><div class="mb-2"><img src="uploads/<?php echo htmlspecialchars($cand['photo']); ?>" class="cand-photo"></div><?php endif; ?>
        <input class="form-control" type="file" name="photo" accept="image/*">
      </div>
      <button class="btn btn-primary">Save</button>
      <a class="btn btn-secondary" href="admin_candidates.php">Back</a>
    </form>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>