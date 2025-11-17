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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin_candidates.php');
    exit;
}
$token = $_POST['csrf_token'] ?? '';
if (!verify_csrf_token($token)) {
    header('Location: admin_candidates.php?error=csrf');
    exit;
}
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($id <= 0) {
    header('Location: admin_candidates.php');
    exit;
}
// fetch photo filename to delete from disk
$stmt = $pdo->prepare('SELECT photo FROM candidates WHERE id = ?');
$stmt->execute([$id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row && !empty($row['photo'])) {
    $path = __DIR__ . '/uploads/' . $row['photo'];
    if (file_exists($path)) @unlink($path);
}

$del = $pdo->prepare('DELETE FROM candidates WHERE id = ?');
$del->execute([$id]);
header('Location: admin_candidates.php?deleted=1');
exit;

