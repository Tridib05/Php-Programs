<?php
require __DIR__ . '/config/database.php';
$config = include __DIR__ . '/config/database.php';
$dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
$pdo = new PDO($dsn, $config['user'], $config['pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/csrf.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($token)) {
        $errors[] = 'Invalid CSRF token.';
    }

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$password) $errors[] = 'Password required.';

    // check lockout
    if (!login_attempt_allowed()) {
        $errors[] = 'Too many login attempts. Try again later.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            // successful login
            unset($user['password']);
            $_SESSION['user'] = $user;
            login_regenerate();
            // reset attempts
            $_SESSION['login_attempts'] = 0;
            $_SESSION['login_lock_until'] = null;
            header('Location: vote.php');
            exit;
        } else {
            // increment attempts
            login_attempt_increment();
            $errors[] = 'Invalid credentials.';
        }
    }
}

generate_csrf_token();
include __DIR__ . '/includes/header.php';
?>
<div class="row justify-content-center">
  <div class="col-md-6">
    <h2>Login</h2>
    <?php if(isset($_GET['registered'])): ?><div class="alert alert-success">Registration successful. Please login.</div><?php endif; ?>
    <?php if($errors): ?>
      <div class="alert alert-danger"><ul><?php foreach($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?></ul></div>
    <?php endif; ?>

    <form method="post">
      <?php csrf_input(); ?>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input class="form-control" type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? '') ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input class="form-control" type="password" name="password" required>
      </div>
      <button class="btn btn-primary">Login</button>
    </form>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>