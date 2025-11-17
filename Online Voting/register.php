<?php
$require_path = __DIR__ . '/config/database.php';
require $require_path;
$config = include __DIR__ . '/config/database.php';
$dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
$pdo = new PDO($dsn, $config['user'], $config['pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // CSRF check
  require_once __DIR__ . '/includes/csrf.php';
  $token = $_POST['csrf_token'] ?? '';
  if (!verify_csrf_token($token)) {
    $errors[] = 'Invalid CSRF token.';
  }
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email required.';

    if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
generate_csrf_token();
include __DIR__ . '/includes/header.php';
    if ($password !== $password2) $errors[] = 'Passwords do not match.';

    if (empty($errors)) {
        // Check duplicate
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'Email already registered.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ins = $pdo->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
            $ins->execute([$name, $email, $hash]);
            header('Location: login.php?registered=1');
            exit;
        }
    }
}

include __DIR__ . '/includes/header.php';
?>
<div class="row justify-content-center">
  <div class="col-md-6">
    <h2>Register</h2>
    <?php if($errors): ?>
      <div class="alert alert-danger"><ul><?php foreach($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?></ul></div>
    <?php endif; ?>

    <form method="post">
      <?php csrf_input(); ?>
      <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input class="form-control" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? '') ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input class="form-control" type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? '') ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input class="form-control" type="password" name="password" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Confirm Password</label>
        <input class="form-control" type="password" name="password2" required>
      </div>
      <button class="btn btn-primary">Register</button>
    </form>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>