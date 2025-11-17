<?php
require_once __DIR__ . '/session.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Online Voting System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
    <a class="navbar-brand" href="/Tridib/OnlineVoting/index.php">Online Voting</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <?php if(!empty($_SESSION['user'])): ?>
          <li class="nav-item"><a class="nav-link" href="vote.php">Vote</a></li>
          <li class="nav-item"><a class="nav-link" href="results.php">Results</a></li>
          <?php if(!empty($_SESSION['user']['is_admin'])): ?>
            <li class="nav-item"><a class="nav-link" href="admin_candidates.php">Admin</a></li>
          <?php endif; ?>
          <li class="nav-item"><a class="nav-link" href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['user']['name']); ?>)</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="results.php">Results</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<div class="container mt-4">
