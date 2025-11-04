<?php
session_start();
require_once __DIR__ . '/../config/database.php';

// Simple admin credentials - change these in production
$ADMIN_USER = 'admin';
$ADMIN_PASS = 'admin123';

if(isset($_SESSION['admin']) && $_SESSION['admin'] === true){
    header('Location: dashboard.php');
    exit;
}

$error = '';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';
    if($user === $ADMIN_USER && $pass === $ADMIN_PASS){
        $_SESSION['admin'] = true;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid credentials.';
    }
}
?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<h2>Admin login</h2>
<?php if($error): ?><div class="alert"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
<form method="post">
    <div class="form-row">
        <label>Username</label>
        <input name="username" type="text" required>
    </div>
    <div class="form-row">
        <label>Password</label>
        <input name="password" type="password" required>
    </div>
    <div class="form-row">
        <button type="submit">Login</button>
    </div>
</form>
<?php include __DIR__ . '/../includes/footer.php'; ?>
