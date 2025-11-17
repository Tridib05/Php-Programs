<?php
require __DIR__ . '/config/database.php';
$config = include __DIR__ . '/config/database.php';
$dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
$pdo = new PDO($dsn, $config['user'], $config['pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

// simple utility to set a user as admin by email
$messages = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $messages[] = 'Valid email required.';
    } else {
        $up = $pdo->prepare('UPDATE users SET is_admin = 1 WHERE email = ?');
        $up->execute([$email]);
        if ($up->rowCount() > 0) {
            $messages[] = 'User promoted to admin.';
        } else {
            $messages[] = 'No user found with that email.';
        }
    }
}

include __DIR__ . '/includes/header.php';
?>
<div class="row justify-content-center">
  <div class="col-md-6">
    <h2>Make Admin</h2>
    <?php if($messages): ?><div class="alert alert-info"><?php foreach($messages as $m) echo '<div>'.htmlspecialchars($m).'</div>'; ?></div><?php endif; ?>
    <form method="post">
      <div class="mb-3">
        <label class="form-label">User Email</label>
        <input class="form-control" name="email" type="email" required>
      </div>
      <button class="btn btn-primary">Make Admin</button>
    </form>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>