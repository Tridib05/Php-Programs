<?php
session_start();
// If already logged in, go to dashboard
if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Hard-coded demo credentials (change for production)
    $validUser = 'admin';
    $validPass = 'password123';

    if ($username === $validUser && $password === $validPass) {
        $_SESSION['user'] = $username;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <style>body{font-family:Arial;max-width:420px;margin:40px auto;padding:20px;border:1px solid #ddd;}label,input{display:block;width:100%;margin-bottom:10px;}input[type=text],input[type=password]{padding:8px}</style>
</head>
<body>
    <h2>Login</h2>
    <?php if ($error): ?><p style="color:red"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>
    <form method="post">
        <label>Username
            <input type="text" name="username" required autofocus>
        </label>
        <label>Password
            <input type="password" name="password" required>
        </label>
        <button type="submit">Login</button>
    </form>
    <p><small>Demo credentials: <strong>admin / password123</strong></small></p>
</body>
</html>
