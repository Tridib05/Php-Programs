<?php
require __DIR__ . '/config/database.php';
$config = include __DIR__ . '/config/database.php';
$dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
$pdo = new PDO($dsn, $config['user'], $config['pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

// total votes
$totalStmt = $pdo->query('SELECT COUNT(*) AS total FROM votes');
$total = (int) $totalStmt->fetch(PDO::FETCH_ASSOC)['total'];

// fetch counts per candidate
$stmt = $pdo->query('SELECT c.id, c.name, c.description, COUNT(v.id) AS votes FROM candidates c LEFT JOIN votes v ON v.candidate_id = c.id GROUP BY c.id ORDER BY votes DESC');
$candidates = $stmt->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/includes/header.php';
?>
<div class="mb-4">
  <canvas id="resultsChart" width="600" height="250"></canvas>
</div>
<div class="row">
  <div class="col-md-8">
    <h2>Results</h2>
    <p>Total votes: <strong><?php echo $total; ?></strong></p>
    <div class="list-group">
      <?php foreach ($candidates as $cand):
        $v = (int) $cand['votes'];
        $pct = $total > 0 ? round(($v / $total) * 100, 1) : 0;
      ?>
        <div class="list-group-item">
          <h5><?php echo htmlspecialchars($cand['name']); ?> <small class="text-muted"><?php echo $v; ?> votes</small></h5>
          <p><?php echo nl2br(htmlspecialchars($cand['description'])); ?></p>
          <div class="progress" style="height:18px">
            <div class="progress-bar" role="progressbar" style="width: <?php echo $pct; ?>%;" aria-valuenow="<?php echo $pct; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $pct; ?>%</div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="col-md-4">
    <h4>Quick Links</h4>
    <ul>
      <li><a href="vote.php">Back to Vote</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  (function(){
    const ctx = document.getElementById('resultsChart');
    if (!ctx) return;
    const labels = <?php echo json_encode(array_map(function($c){ return $c['name']; }, $candidates)); ?>;
    const data = <?php echo json_encode(array_map(function($c){ return (int)$c['votes']; }, $candidates)); ?>;
    new Chart(ctx, {
      type: 'bar',
      data: { labels: labels, datasets: [{ label: 'Votes', data: data, backgroundColor: 'rgba(54,162,235,0.6)' }] },
      options: { responsive: true, scales: { y: { beginAtZero: true, precision:0 } } }
    });
  })();
</script>