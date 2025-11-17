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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: vote.php');
    exit;
}

// CSRF check
require_once __DIR__ . '/includes/csrf.php';
$token = $_POST['csrf_token'] ?? '';
if (!verify_csrf_token($token)) {
    header('Location: vote.php?error=csrf');
    exit;
}

$candidate_id = isset($_POST['candidate_id']) ? (int) $_POST['candidate_id'] : 0;
if ($candidate_id <= 0) {
    header('Location: vote.php');
    exit;
}

try {
    $stmt = $pdo->prepare('INSERT INTO votes (user_id, candidate_id) VALUES (?, ?)');
    $stmt->execute([$user['id'], $candidate_id]);
    header('Location: results.php');
    exit;
} catch (PDOException $e) {
    // duplicate vote or other DB error
    if ($e->getCode() === '23000') {
        // unique constraint violation (already voted)
        header('Location: vote.php?error=already_voted');
        exit;
    }
    // otherwise rethrow for visibility in dev
    throw $e;
}
