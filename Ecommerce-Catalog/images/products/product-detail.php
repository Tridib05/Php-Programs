<?php
require_once 'config/database.php';

$db = new Database();
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$product_id) {
    header('Location: products.php');
    exit;
}

// Get product details
$product = $db->fetchOne("
    SELECT p.*, c.name as category_name 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    WHERE p.id = ? AND p.is_active = 1
", [$product_id]);

if (!$product) {
    header('Location: products.php');
    exit;
}

$page_title = htmlspecialchars($product['name']) . ' - Product Detail';

// Get related products (same category, excluding current product)
$related_products = $db->fetchAll("
    SELECT p.*, c.name as category_name 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    WHERE p.category_id = ? AND p.id != ? AND p.is_active = 1 
    ORDER BY RAND() 
    LIMIT 4
", [$product['category_id'], $product_id]);

include 'includes/header.php';
?>

<div class="container my-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="products.php">Products</a></li>
            <li class="breadcrumb-item"><a href="products.php?category=<?php echo $product['category_id']; ?>">
                <?php echo htmlspecialchars($product['category_name']); ?>
            </a></li>
            <li class="breadcrumb-item active"><?php echo htmlspecialchars($product['name']); ?></li>
        </ol>
    </nav>

    <!-- Product Details -->
    <div class="row">
        <div class="col-md-6">
            <img src="https://via.placeholder.com/600x400/<?php echo dechex(rand(0x000000, 0xFFFFFF)); ?>/ffffff?text=<?php echo urlencode($product['name']); ?>" 
                 class="img-fluid product-detail-image" alt="<?php echo htmlspecialchars($product['name']); ?>">
        </div>
        <div class="col-md-6">
            <div class="product-info">
                <h1 class="mb-3"><?php echo htmlspecialchars($product['name']); ?></h1>
                
                <div class="mb-3">
                    <span class="badge bg-primary"><?php echo htmlspecialchars($product['category_name']); ?></span>
                    <?php if ($product['stock_quantity'] > 0): ?>
                        <span class="badge bg-success ms-2">In Stock (<?php echo $product['stock_quantity']; ?> available)</span>
                    <?php else: ?>
                        <span class="badge bg-danger ms-2">Out of Stock</span>
                    <?php endif; ?>
                </div>

                <div class="price-section mb-4">
                    <h2 class="price-tag mb-0">৳<?php echo number_format($product['price'], 2); ?></h2>
                </div>

                <div class="product-description mb-4">
                    <h5>Product Description</h5>
                    <p class="text-muted"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                </div>

                <div class="product-actions">
                    <?php if ($product['stock_quantity'] > 0): ?>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <button class="btn btn-primary btn-lg w-100" onclick="addToCart(<?php echo $product_id; ?>)">
                                    <i class="fas fa-cart-plus"></i> Add to Cart
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-success btn-lg w-100">
                                    <i class="fas fa-bolt"></i> Buy Now
                                </button>
                            </div>
                        </div>
                    <?php else: ?>
                        <button class="btn btn-secondary btn-lg w-100" disabled>
                            <i class="fas fa-ban"></i> Out of Stock
                        </button>
                    <?php endif; ?>
                    
                    <div class="mt-3">
                        <button class="btn btn-outline-danger">
                            <i class="fas fa-heart"></i> Add to Wishlist
                        </button>
                        <button class="btn btn-outline-primary ms-2">
                            <i class="fas fa-share-alt"></i> Share
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Specifications -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Product Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Product Name:</th>
                                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                                </tr>
                                <tr>
                                    <th>Category:</th>
                                    <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                                </tr>
                                <tr>
                                    <th>Price:</th>
                                    <td class="price-tag">৳<?php echo number_format($product['price'], 2); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Stock Status:</th>
                                    <td>
                                        <?php if ($product['stock_quantity'] > 0): ?>
                                            <span class="text-success">
                                                <i class="fas fa-check-circle"></i> In Stock (<?php echo $product['stock_quantity']; ?>)
                                            </span>
                                        <?php else: ?>
                                            <span class="text-danger">
                                                <i class="fas fa-times-circle"></i> Out of Stock
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Added Date:</th>
                                    <td><?php echo date('F j, Y', strtotime($product['created_at'])); ?></td>
                                </tr>
                                <tr>
                                    <th>Last Updated:</th>
                                    <td><?php echo date('F j, Y', strtotime($product['updated_at'])); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <?php if (!empty($related_products)): ?>
    <div class="mt-5">
        <h3 class="mb-4">Related Products</h3>
        <div class="row">
            <?php foreach ($related_products as $related_product): ?>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card product-card h-100">
                    <div class="position-relative">
                        <img src="https://via.placeholder.com/300x200/<?php echo dechex(rand(0x000000, 0xFFFFFF)); ?>/ffffff?text=<?php echo urlencode($related_product['name']); ?>" 
                             class="card-img-top product-image" alt="<?php echo htmlspecialchars($related_product['name']); ?>">
                        <?php if ($related_product['stock_quantity'] > 0): ?>
                            <span class="badge bg-success badge-stock">In Stock</span>
                        <?php else: ?>
                            <span class="badge bg-danger badge-stock">Out of Stock</span>
                        <?php endif; ?>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title"><?php echo htmlspecialchars($related_product['name']); ?></h6>
                        <p class="card-text text-muted small"><?php echo htmlspecialchars($related_product['short_description']); ?></p>
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="price-tag">৳<?php echo number_format($related_product['price'], 2); ?></span>
                                <a href="product-detail.php?id=<?php echo $related_product['id']; ?>" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
