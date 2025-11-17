<?php
/**
 * Admin Orders Management
 */
session_start();
if(!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../config/database.php';
$db = new Database();

// Get all orders
$orders = $db->fetchAll("SELECT * FROM orders ORDER BY created_at DESC");

$page_title = 'Manage Orders - Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .sidebar { background: #2c3e50; color: white; min-height: 100vh; padding: 20px 0; }
        .sidebar a { color: white; text-decoration: none; display: block; padding: 10px 20px; transition: 0.3s; }
        .sidebar a:hover { background: #34495e; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar">
                <h4 class="mb-4"><i class="fas fa-store"></i> E-Shop Admin</h4>
                <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
                <a href="products.php"><i class="fas fa-box"></i> Products</a>
                <a href="categories.php"><i class="fas fa-list"></i> Categories</a>
                <a href="orders.php" style="background: #34495e;"><i class="fas fa-shopping-cart"></i> Orders</a>
                <hr>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <h2 class="mb-4">Orders</h2>

                <div class="card">
                    <div class="card-body">
                        <?php if(empty($orders)): ?>
                            <p class="text-muted">No orders found.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Customer</th>
                                            <th>Email</th>
                                            <th>Items</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($orders as $order): ?>
                                            <tr>
                                                <td>#<?php echo $order['id']; ?></td>
                                                <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                                <td><?php echo htmlspecialchars($order['customer_email']); ?></td>
                                                <td><?php echo $order['total_items']; ?></td>
                                                <td>$<?php echo number_format($order['total_price'], 2); ?></td>
                                                <td>
                                                    <span class="badge bg-info"><?php echo htmlspecialchars($order['status']); ?></span>
                                                </td>
                                                <td><?php echo date('M d, Y H:i', strtotime($order['created_at'])); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
