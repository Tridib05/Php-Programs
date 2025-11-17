<?php
require __DIR__ . '/config/database.php';
$config = include __DIR__ . '/config/database.php';
$dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
$pdo = new PDO($dsn, $config['user'], $config['pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
$user = $_SESSION['user'];
// check if user already voted
$stmt = $pdo->prepare('SELECT * FROM votes WHERE user_id = ? LIMIT 1');
$stmt->execute([$user['id']]);
$already = (bool) $stmt->fetch(PDO::FETCH_ASSOC);

// fetch candidates
$stmt = $pdo->query('SELECT * FROM candidates ORDER BY id ASC');
$candidates = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/includes/csrf.php';
generate_csrf_token();

include __DIR__ . '/includes/header.php';
?>
<div class="row">
  <div class="col-md-8">
    <h2>Vote for a Candidate</h2>
    <p>Logged in as <strong><?php echo htmlspecialchars($user['name']); ?></strong>. <a href="logout.php">Logout</a></p>

    <?php if ($already): ?>
      <div class="alert alert-info">You have already voted. <a href="results.php">See results</a>.</div>
    <?php else: ?>
      <div class="list-group">
        <?php foreach ($candidates as $cand): ?>
          <div class="list-group-item">
            <div class="vote-item">
              <?php if (!empty($cand['photo'])): ?><img src="uploads/<?php echo htmlspecialchars($cand['photo']); ?>" class="cand-photo" alt="<?php echo htmlspecialchars($cand['name']); ?>"><?php endif; ?>
              <div style="flex:1">
                <h5><?php echo htmlspecialchars($cand['name']); ?></h5>
                <p><?php echo nl2br(htmlspecialchars($cand['description'])); ?></p>
              </div>
              <div>
                <form method="post" action="process_vote.php" style="display:inline">
                  <?php csrf_input(); ?>
                  <input type="hidden" name="candidate_id" value="<?php echo (int)$cand['id']; ?>">
                  <button class="btn btn-success">Vote</button>
                </form>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

  <div class="col-md-4">
    <h4>Quick Links</h4>
    <ul>
      <li><a href="results.php">View Results</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>