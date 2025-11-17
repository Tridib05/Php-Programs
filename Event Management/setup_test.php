<?php
/**
 * Setup Helper & Test Page
 * This page helps verify the Event Management system is set up correctly
 * Access at: http://localhost/Tridib/Event%20Management/setup_test.php
 */
require_once __DIR__ . '/config/database.php';

$status = [];

// 1. Check database connection
try {
    $test = $pdo->query('SELECT 1');
    $status['db_connection'] = ['ok' => true, 'msg' => 'Database connected successfully'];
} catch (Exception $e) {
    $status['db_connection'] = ['ok' => false, 'msg' => 'Database error: ' . $e->getMessage()];
}

// 2. Check if events table exists
try {
    $res = $pdo->query('DESCRIBE events');
    $columns = $res->fetchAll();
    $col_names = array_column($columns, 'Field');
    $required_cols = ['id', 'title', 'event_date', 'registrations'];
    $has_image = in_array('image', $col_names);
    $status['events_table'] = ['ok' => true, 'msg' => 'events table exists. Columns: ' . implode(', ', $col_names) . ($has_image ? ' [IMAGE SUPPORT OK]' : ' [No image column]')];
} catch (Exception $e) {
    $status['events_table'] = ['ok' => false, 'msg' => 'events table check failed: ' . $e->getMessage()];
}

// 3. Check if registrations table exists
try {
    $res = $pdo->query('DESCRIBE registrations');
    $status['registrations_table'] = ['ok' => true, 'msg' => 'registrations table exists'];
} catch (Exception $e) {
    $status['registrations_table'] = ['ok' => false, 'msg' => 'registrations table check failed: ' . $e->getMessage()];
}

// 4. Check if images/uploads folder is writable
$upload_dir = __DIR__ . '/images/uploads';
if (is_dir($upload_dir)) {
    if (is_writable($upload_dir)) {
        $status['uploads_folder'] = ['ok' => true, 'msg' => 'images/uploads folder is writable'];
    } else {
        $status['uploads_folder'] = ['ok' => false, 'msg' => 'images/uploads folder exists but is NOT writable'];
    }
} else {
    $status['uploads_folder'] = ['ok' => false, 'msg' => 'images/uploads folder does not exist'];
}

// 5. Check file permissions
$required_files = [
    'config/database.php',
    'includes/header.php',
    'includes/footer.php',
    'css/style.css',
    'js/script.js',
    'events.php',
    'event_detail.php',
    'register.php',
    'participants.php',
    'admin/login.php',
    'admin/dashboard.php',
    'admin/create_event.php',
    'admin/logout.php',
];

$missing_files = [];
foreach ($required_files as $f) {
    if (!file_exists(__DIR__ . '/' . $f)) {
        $missing_files[] = $f;
    }
}

if (empty($missing_files)) {
    $status['required_files'] = ['ok' => true, 'msg' => 'All required files present'];
} else {
    $status['required_files'] = ['ok' => false, 'msg' => 'Missing files: ' . implode(', ', $missing_files)];
}

// Count events
try {
    $cnt = $pdo->query('SELECT COUNT(*) FROM events')->fetchColumn();
    $status['sample_data'] = ['ok' => ($cnt > 0), 'msg' => "Events in database: $cnt"];
} catch (Exception $e) {
    $status['sample_data'] = ['ok' => false, 'msg' => 'Could not query events'];
}

include __DIR__ . '/includes/header.php';
?>

<h2>Event Management System - Setup Check</h2>

<table class="table" style="margin-top:20px;">
    <thead>
        <tr>
            <th>Check</th>
            <th>Status</th>
            <th>Details</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($status as $check => $result): ?>
            <tr style="background: <?php echo $result['ok'] ? '#d4edda' : '#f8d7da'; ?>;">
                <td><strong><?php echo htmlspecialchars($check); ?></strong></td>
                <td><?php echo $result['ok'] ? '✓ OK' : '✗ FAIL'; ?></td>
                <td><?php echo htmlspecialchars($result['msg']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h3 style="margin-top:30px;">Quick Links</h3>
<ul>
    <li><a href="events.php">View Events</a></li>
    <li><a href="admin/login.php">Admin Login</a> (demo: admin / admin123)</li>
    <li><a href="README.md" target="_blank">README.md</a></li>
</ul>

<h3 style="margin-top:30px;">Setup Instructions</h3>
<ol>
    <li>If not all checks pass, run the following SQL to create the database and tables:
        <pre><?php echo htmlspecialchars(file_get_contents(__DIR__ . '/setup.sql')); ?></pre>
    </li>
    <li>Use phpMyAdmin or mysql CLI to import setup.sql</li>
    <li>Then reload this page to verify all checks pass</li>
</ol>

<?php include __DIR__ . '/includes/footer.php'; ?>
