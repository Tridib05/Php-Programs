<?php
require_once 'config/database.php';

$page_title = 'Home - E-commerce Product Catalog';
$db = new Database();

// Get categories
$categories = $db->fetchAll("SELECT * FROM categories ORDER BY name");

// Get featured products (latest 8 products)
$featured_products = $db->fetchAll(""
    SELECT p.*, c.name as category_name 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    WHERE p.is_active = 1 
    ORDER BY p.created_at DESC 
    LIMIT 8
""
);

include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Welcome to E-Shop</h1>
                <p class="lead mb-4">Discover amazing products across multiple categories. Quality products at unbeatable prices!</p>
                <a href="products.php" class="btn btn-light btn-lg">
                    <i class="fas fa-shopping-bag"></i> Shop Now
                </a>
            </div>
            <div class="col-lg-6">
                <img src="https://via.placeholder.com/500x300/007bff/ffffff?text=E-Shop" 
                     class="img-fluid rounded" alt="E-Shop Hero">
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<div class="container">
    <h2 class="text-center mb-5">Shop by Categories</h2>
    <div class="row">
        <?php foreach ($categories as $category): ?>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card category-card h-100">
                <img src="https://via.placeholder.com/300x150/<?php echo dechex(rand(0x000000, 0xFFFFFF)); ?>/ffffff?text=<?php echo urlencode($category['name']); ?>" 
                     class="card-img-top category-image" alt="<?php echo htmlspecialchars($category['name']); ?>">
                <div class="card-body text-center">
                    <h5 class="card-title"><?php echo htmlspecialchars($category['name']); ?></h5>
                    <p class="card-text text-muted"><?php echo htmlspecialchars($category['description']); ?></p>
                    <a href="products.php?category=<?php echo $category['id']; ?>" class="btn btn-primary">
                        <i class="fas fa-arrow-right"></i> Browse
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Featured Products Section -->
<div class="container mt-5">
    <h2 class="text-center mb-5">Featured Products</h2>
    <div class="row">
        <?php foreach ($featured_products as $product): ?>
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
    
    <div class="text-center mt-4">
        <a href="products.php" class="btn btn-primary btn-lg">
            <i class="fas fa-th"></i> View All Products
        </a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
