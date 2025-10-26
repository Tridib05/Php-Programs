<?php
require_once __DIR__ . '/login-check.php';
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dashboard</title>
    <style>body{font-family:Arial;max-width:720px;margin:40px auto;padding:20px;} a{display:inline-block;margin-top:10px;}</style>
</head>
<body>
    <h1>Dashboard</h1>
    <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['user']); ?></strong>!</p>
    <p><a href="logout.php">Logout</a></p>
</body>
</html>
