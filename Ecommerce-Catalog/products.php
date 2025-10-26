<?php
require_once 'config/database.php';

$page_title = 'Products - E-commerce Product Catalog';
$db = new Database();

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 12;
$offset = ($page - 1) * $per_page;

// Filters
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;
$sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'name';
$sort_order = isset($_GET['order']) ? $_GET['order'] : 'ASC';

// Build query
$where_conditions = ['p.is_active = 1'];
$params = [];

if ($category_id) {
    $where_conditions[] = 'p.category_id = ?';
    $params[] = $category_id;
}

$where_clause = 'WHERE ' . implode(' AND ', $where_conditions);

// Valid sort options
$valid_sorts = ['name', 'price', 'created_at'];
$sort_by = in_array($sort_by, $valid_sorts) ? $sort_by : 'name';
$sort_order = ($sort_order === 'DESC') ? 'DESC' : 'ASC';

// Get products
$sql = "SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        $where_clause 
        ORDER BY p." . $sort_by . " " . $sort_order . " 
        LIMIT $per_page OFFSET $offset";

$products = $db->fetchAll($sql, $params);

// Get total count for pagination
$count_sql = "SELECT COUNT(*) as total 
              FROM products p 
              $where_clause";
$total_result = $db->fetchOne($count_sql, $params);
$total_products = $total_result['total'];
$total_pages = ceil($total_products / $per_page);

// Get categories for filter
$categories = $db->fetchAll("SELECT * FROM categories ORDER BY name");

include 'includes/header.php';
?>

<div class="container my-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Products</h1>
            <p class="text-muted">Showing <?php echo count($products); ?> of <?php echo $total_products; ?> products</p>
        </div>
        <div class="col-md-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Products</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Filters and Sorting -->
    <div class="search-section">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Category</label>
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>" 
                                <?php echo ($category_id == $category['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Sort By</label>
                <select name="sort" class="form-select">
                    <option value="name" <?php echo ($sort_by == 'name') ? 'selected' : ''; ?>>Name</option>
                    <option value="price" <?php echo ($sort_by == 'price') ? 'selected' : ''; ?>>Price</option>
                    <option value="created_at" <?php echo ($sort_by == 'created_at') ? 'selected' : ''; ?>>Date Added</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Order</label>
                <select name="order" class="form-select">
                    <option value="ASC" <?php echo ($sort_order == 'ASC') ? 'selected' : ''; ?>>Ascending</option>
                    <option value="DESC" <?php echo ($sort_order == 'DESC') ? 'selected' : ''; ?>>Descending</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary d-block w-100">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Products Grid -->
    <?php if (empty($products)): ?>
        <div class="text-center py-5">
            <i class="fas fa-search fa-3x text-muted mb-3"></i>
            <h3>No products found</h3>
            <p class="text-muted">Try adjusting your filters or search criteria.</p>
            <a href="products.php" class="btn btn-primary">View All Products</a>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($products as $product): ?>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card product-card h-100">
                    <div class="position-relative">
                        <img src="https://via.placeholder.com/300x200/<?php echo dechex(rand(0x000000, 0xFFFFFF)); ?>/ffffff?text=<?php echo urlencode($product['name']); ?>" 
                             class="card-img-top product-image" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <?php if ($product['stock_quantity'] > 0): ?>
                            <span class="badge bg-success badge-stock">In Stock</span>
                        <?php else: ?>
                            <span class="badge bg-danger badge-stock">Out of Stock</span>
                        <?php endif; ?>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                        <p class="card-text text-muted small"><?php echo htmlspecialchars($product['short_description']); ?></p>
                        <p class="card-text"><small class="text-muted"><?php echo htmlspecialchars($product['category_name']); ?></small></p>
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="price-tag">৳<?php echo number_format($product['price'], 2); ?></span>
                                <div>
                                    <a href="product-detail.php?id=<?php echo $product['id']; ?>" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if ($product['stock_quantity'] > 0): ?>
                                        <button class="btn btn-primary btn-sm" onclick="addToCart(<?php echo $product['id']; ?>)">
                                            <i class="fas fa-cart-plus"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <nav aria-label="Product pagination">
                <ul class="pagination">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>">
                                <i class="fas fa-chevron-left"></i> Previous
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                            <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>">
                                Next <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
