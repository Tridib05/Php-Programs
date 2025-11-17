<?php
/**
 * Admin Dashboard
 */
session_start();
if(!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../config/database.php';
$db = new Database();

// Get dashboard stats
$total_products = $db->fetchOne("SELECT COUNT(*) as count FROM products")['count'];
$total_categories = $db->fetchOne("SELECT COUNT(*) as count FROM categories")['count'];
$total_orders = $db->fetchOne("SELECT COUNT(*) as count FROM orders")['count'];
$total_revenue = $db->fetchOne("SELECT COALESCE(SUM(total_price), 0) as revenue FROM orders WHERE status = 'completed'")['revenue'];

// Get recent orders
$recent_orders = $db->fetchAll("SELECT * FROM orders ORDER BY created_at DESC LIMIT 5");

// Get low stock products
$low_stock = $db->fetchAll("SELECT id, name, stock FROM products WHERE stock <= 5 ORDER BY stock ASC LIMIT 5");

$page_title = 'Admin Dashboard - E-Shop';
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
        .stat-card { background: white; padding: 20px; border-radius: 5px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .stat-card h5 { color: #666; font-size: 12px; text-transform: uppercase; margin-bottom: 10px; }
        .stat-card .number { font-size: 30px; font-weight: bold; color: #667eea; }
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
                <a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a>
                <hr>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <h2 class="mb-4">Dashboard</h2>

                <!-- Stats -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stat-card">
                            <h5>Total Products</h5>
                            <div class="number"><?php echo $total_products; ?></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <h5>Total Categories</h5>
                            <div class="number"><?php echo $total_categories; ?></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <h5>Total Orders</h5>
                            <div class="number"><?php echo $total_orders; ?></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <h5>Total Revenue</h5>
                            <div class="number">$<?php echo number_format($total_revenue, 2); ?></div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Recent Orders</h5>
                    </div>
                    <div class="card-body">
                        <?php if(empty($recent_orders)): ?>
                            <p class="text-muted">No orders yet.</p>
                        <?php else: ?>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($recent_orders as $order): ?>
                                        <tr>
                                            <td>#<?php echo $order['id']; ?></td>
                                            <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                            <td><?php echo $order['total_items']; ?></td>
                                            <td>$<?php echo number_format($order['total_price'], 2); ?></td>
                                            <td><span class="badge bg-info"><?php echo htmlspecialchars($order['status']); ?></span></td>
                                            <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Low Stock Warning -->
                <?php if(!empty($low_stock)): ?>
                    <div class="card">
                        <div class="card-header bg-warning">
                            <h5 class="mb-0">⚠️ Low Stock Products</h5>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Stock</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($low_stock as $product): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                                            <td><strong><?php echo $product['stock']; ?></strong></td>
                                            <td><a href="edit_product.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-primary">Edit</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
