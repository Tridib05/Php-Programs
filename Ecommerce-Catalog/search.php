<?php
require_once 'config/database.php';

$page_title = 'Search Results - E-commerce Product Catalog';
$db = new Database();

$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 12;
$offset = ($page - 1) * $per_page;

$products = [];
$total_products = 0;
$total_pages = 0;

if (!empty($search_query)) {
    // Search in product name and description
    $search_sql = "SELECT p.*, c.name as category_name 
                   FROM products p 
                   LEFT JOIN categories c ON p.category_id = c.id 
                   WHERE p.is_active = 1 
                   AND (p.name LIKE ? OR p.description LIKE ? OR p.short_description LIKE ?) 
                   ORDER BY 
                   CASE 
                       WHEN p.name LIKE ? THEN 1
                       WHEN p.short_description LIKE ? THEN 2
                       ELSE 3
                   END,
                   p.name
                   LIMIT $per_page OFFSET $offset";
    
    $search_term = "%$search_query%";
    $search_name = "%$search_query%";
    $params = [$search_term, $search_term, $search_term, $search_name, $search_name];
    
    $products = $db->fetchAll($search_sql, $params);
    
    // Get total count
    $count_sql = "SELECT COUNT(*) as total 
                  FROM products p 
                  WHERE p.is_active = 1 
                  AND (p.name LIKE ? OR p.description LIKE ? OR p.short_description LIKE ?)";
    
    $total_result = $db->fetchOne($count_sql, [$search_term, $search_term, $search_term]);
    $total_products = $total_result['total'];
    $total_pages = ceil($total_products / $per_page);
}

include 'includes/header.php';
?>

<div class="container my-4">
    <!-- Search Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1>Search Results</h1>
            <?php if (!empty($search_query)): ?>
                <p class="text-muted">
                    Showing <?php echo count($products); ?> of <?php echo $total_products; ?> results for 
                    <strong>"<?php echo htmlspecialchars($search_query); ?>"</strong>
                </p>
            <?php else: ?>
                <p class="text-muted">Please enter a search term to find products.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Search Form -->
    <div class="search-section">
        <form method="GET" action="search.php">
            <div class="row g-3">
                <div class="col-md-10">
                    <input type="text" class="form-control form-control-lg" 
                           name="q" placeholder="Search for products..." 
                           value="<?php echo htmlspecialchars($search_query); ?>" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Search Results -->
    <?php if (empty($search_query)): ?>
        <div class="text-center py-5">
            <i class="fas fa-search fa-3x text-muted mb-3"></i>
            <h3>Start Your Search</h3>
            <p class="text-muted">Enter keywords to find the products you're looking for.</p>
        </div>
    <?php elseif (empty($products)): ?>
        <div class="text-center py-5">
            <i class="fas fa-search fa-3x text-muted mb-3"></i>
            <h3>No Results Found</h3>
            <p class="text-muted">Sorry, we couldn't find any products matching "<strong><?php echo htmlspecialchars($search_query); ?></strong>".</p>
            <div class="mt-3">
                <h5>Search Suggestions:</h5>
                <ul class="list-unstyled">
                    <li>• Check your spelling</li>
                    <li>• Try different keywords</li>
                    <li>• Use more general terms</li>
                    <li>• Browse our <a href="products.php">all products</a></li>
                </ul>
            </div>
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

        <!-- Pagination for Search Results -->
        <?php if ($total_pages > 1): ?>
            <nav aria-label="Search pagination">
                <ul class="pagination">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?q=<?php echo urlencode($search_query); ?>&page=<?php echo $page - 1; ?>">
                                <i class="fas fa-chevron-left"></i> Previous
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                            <a class="page-link" href="?q=<?php echo urlencode($search_query); ?>&page=<?php echo $i; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?q=<?php echo urlencode($search_query); ?>&page=<?php echo $page + 1; ?>">
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
