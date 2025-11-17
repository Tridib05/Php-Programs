<?php
/**
 * Admin Products Management
 */
session_start();
if(!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../config/database.php';
$db = new Database();

// Get all products with category
$products = $db->fetchAll("
    SELECT p.*, c.name as category_name 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    ORDER BY p.created_at DESC
");

$page_title = 'Manage Products - Admin';
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
                <a href="products.php" class="active" style="background: #34495e;"><i class="fas fa-box"></i> Products</a>
                <a href="categories.php"><i class="fas fa-list"></i> Categories</a>
                <a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a>
                <hr>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Manage Products</h2>
                    <a href="add_product.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add Product</a>
                </div>

                <div class="card">
                    <div class="card-body">
                        <?php if(empty($products)): ?>
                            <p class="text-muted">No products found. <a href="add_product.php">Create one</a></p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Category</th>
                                            <th>Price</th>
                                            <th>Stock</th>
                                            <th>Active</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($products as $product): ?>
                                            <tr>
                                                <td><?php echo $product['id']; ?></td>
                                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                                <td><?php echo htmlspecialchars($product['category_name'] ?? 'N/A'); ?></td>
                                                <td>$<?php echo number_format($product['price'], 2); ?></td>
                                                <td><?php echo $product['stock']; ?></td>
                                                <td><?php echo $product['is_active'] ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>'; ?></td>
                                                <td>
                                                    <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                                    <a href="delete_product.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?');">Delete</a>
                                                </td>
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
