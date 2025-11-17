<?php
/**
 * E-Commerce Setup & Verification Page
 */
require_once 'config/database.php';

$status = [];

// 1. Check database connection
try {
    $db = new Database();
    $test = $db->getConnection()->query('SELECT 1');
    $status['db_connection'] = ['ok' => true, 'msg' => 'Database connected successfully'];
} catch (Exception $e) {
    $status['db_connection'] = ['ok' => false, 'msg' => 'Database error: ' . $e->getMessage()];
}

// 2. Check tables
$tables_needed = ['categories', 'products', 'orders', 'order_items'];
foreach($tables_needed as $table) {
    try {
        $result = $db->fetchOne("DESCRIBE $table");
        $status["table_$table"] = ['ok' => true, 'msg' => "$table table exists"];
    } catch (Exception $e) {
        $status["table_$table"] = ['ok' => false, 'msg' => "$table table missing"];
    }
}

// 3. Check images folder
$img_dir = __DIR__ . '/images/products';
if(is_dir($img_dir) && is_writable($img_dir)) {
    $status['images_folder'] = ['ok' => true, 'msg' => 'images/products folder is writable'];
} else {
    $status['images_folder'] = ['ok' => false, 'msg' => 'images/products folder missing or not writable'];
}

// 4. Count data
try {
    $product_count = $db->fetchOne("SELECT COUNT(*) as c FROM products")['c'];
    $category_count = $db->fetchOne("SELECT COUNT(*) as c FROM categories")['c'];
    $status['sample_data'] = ['ok' => ($product_count > 0 && $category_count > 0), 'msg' => "Categories: $category_count, Products: $product_count"];
} catch (Exception $e) {
    $status['sample_data'] = ['ok' => false, 'msg' => 'Could not count data'];
}

$page_title = 'E-Commerce Setup Check';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="mb-4">E-Commerce Catalog - Setup Check</h2>

                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Check</th>
                            <th>Status</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($status as $check => $result): ?>
                            <tr style="background: <?php echo $result['ok'] ? '#d4edda' : '#f8d7da'; ?>;">
                                <td><strong><?php echo htmlspecialchars($check); ?></strong></td>
                                <td><?php echo $result['ok'] ? '<span class="badge bg-success">✓ OK</span>' : '<span class="badge bg-danger">✗ FAIL</span>'; ?></td>
                                <td><?php echo htmlspecialchars($result['msg']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Quick Links</h5>
                    </div>
                    <div class="card-body">
                        <a href="index.php" class="btn btn-primary me-2">
                            <i class="fas fa-store"></i> View Store
                        </a>
                        <a href="admin/login.php" class="btn btn-warning me-2">
                            <i class="fas fa-lock"></i> Admin Login
                        </a>
                        <a href="README.md" class="btn btn-info" target="_blank">
                            <i class="fas fa-book"></i> Documentation
                        </a>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Setup Instructions</h5>
                    </div>
                    <div class="card-body">
                        <h6>If not all checks pass:</h6>
                        <ol>
                            <li>Open phpMyAdmin at http://localhost/phpmyadmin</li>
                            <li>Click "Import" tab</li>
                            <li>Upload <code>setup.sql</code> from this folder</li>
                            <li>Reload this page</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
