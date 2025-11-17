<?php
/**
 * Admin Categories Management
 */
session_start();
if(!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../config/database.php';
$db = new Database();

$errors = [];
$success = false;

// Add category
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if($name === '') {
        $errors[] = 'Category name is required';
    } else {
        $data = ['name' => $name, 'description' => $description];
        $db->insert('categories', $data);
        $success = 'Category added successfully';
    }
}

// Delete category
if(isset($_GET['delete'])) {
    $cat_id = intval($_GET['delete']);
    // Check if category has products
    $count = $db->fetchOne("SELECT COUNT(*) as count FROM products WHERE category_id = ?", [$cat_id])['count'];
    if($count > 0) {
        $errors[] = 'Cannot delete category with products';
    } else {
        $db->delete('categories', 'id', $cat_id);
        $success = 'Category deleted successfully';
    }
}

$categories = $db->fetchAll("SELECT * FROM categories ORDER BY name");

$page_title = 'Manage Categories - Admin';
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
                <a href="categories.php" style="background: #34495e;"><i class="fas fa-list"></i> Categories</a>
                <a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a>
                <hr>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <h2 class="mb-4">Manage Categories</h2>

                <?php if($success): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>

                <?php if(!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach($errors as $e) echo '<div>' . htmlspecialchars($e) . '</div>'; ?>
                    </div>
                <?php endif; ?>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">Add New Category</div>
                            <div class="card-body">
                                <form method="POST">
                                    <input type="hidden" name="action" value="add">
                                    <div class="mb-3">
                                        <label class="form-label">Category Name *</label>
                                        <input type="text" class="form-control" name="name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" name="description" rows="3"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Add Category</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">All Categories</div>
                            <div class="card-body">
                                <?php if(empty($categories)): ?>
                                    <p class="text-muted">No categories yet.</p>
                                <?php else: ?>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Description</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($categories as $cat): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($cat['name']); ?></td>
                                                    <td><?php echo htmlspecialchars(substr($cat['description'] ?? '', 0, 50)); ?></td>
                                                    <td>
                                                        <a href="?delete=<?php echo $cat['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this category?');">Delete</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
