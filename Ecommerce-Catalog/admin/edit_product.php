<?php
/**
 * Admin Edit Product
 */
session_start();
if(!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../config/database.php';
$db = new Database();

$product_id = intval($_GET['id'] ?? 0);
if($product_id <= 0) {
    die('Invalid product ID');
}

$product = $db->fetchOne("SELECT * FROM products WHERE id = ?", [$product_id]);
if(!$product) {
    die('Product not found');
}

$errors = [];
$success = false;

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $stock = intval($_POST['stock'] ?? 0);
    $category_id = intval($_POST['category_id'] ?? 0);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if($name === '') $errors[] = 'Product name is required';
    if($price <= 0) $errors[] = 'Price must be greater than 0';
    if($stock < 0) $errors[] = 'Stock cannot be negative';

    $image_name = $product['image'];
    if(!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $f = $_FILES['image'];
        $info = @getimagesize($f['tmp_name']);
        $allowed = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_GIF => 'gif'];
        
        if(!$info || !isset($allowed[$info[2]])) {
            $errors[] = 'Please upload a valid image';
        } elseif($f['size'] > 5 * 1024 * 1024) {
            $errors[] = 'Image size must be under 5MB';
        } else {
            $ext = $allowed[$info[2]];
            $image_name = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
            $dest = __DIR__ . '/../images/products/' . $image_name;
            if(move_uploaded_file($f['tmp_name'], $dest)) {
                // Delete old image if exists
                if($product['image'] && file_exists(__DIR__ . '/../images/products/' . $product['image'])) {
                    unlink(__DIR__ . '/../images/products/' . $product['image']);
                }
            } else {
                $errors[] = 'Failed to upload image';
                $image_name = $product['image'];
            }
        }
    }

    if(empty($errors)) {
        $data = [
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'stock' => $stock,
            'category_id' => ($category_id > 0) ? $category_id : null,
            'image' => $image_name,
            'is_active' => $is_active
        ];
        $db->update('products', $data, 'id', $product_id);
        $success = true;
        $product = $db->fetchOne("SELECT * FROM products WHERE id = ?", [$product_id]);
    }
}

$categories = $db->fetchAll("SELECT * FROM categories ORDER BY name");
$page_title = 'Edit Product - Admin';
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
<body style="background: #f8f9fa;">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">Edit Product: <?php echo htmlspecialchars($product['name']); ?></h3>
                    </div>
                    <div class="card-body">
                        <?php if($success): ?>
                            <div class="alert alert-success">Product updated successfully!</div>
                        <?php endif; ?>

                        <?php if(!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <?php foreach($errors as $e) echo '<div>' . htmlspecialchars($e) . '</div>'; ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Product Name *</label>
                                <input type="text" class="form-control" name="name" required value="<?php echo htmlspecialchars($product['name']); ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="4"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Price ($) *</label>
                                        <input type="number" class="form-control" name="price" step="0.01" min="0" required value="<?php echo $product['price']; ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Stock *</label>
                                        <input type="number" class="form-control" name="stock" min="0" required value="<?php echo $product['stock']; ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Category</label>
                                <select class="form-control" name="category_id">
                                    <option value="">-- Select Category --</option>
                                    <?php foreach($categories as $cat): ?>
                                        <option value="<?php echo $cat['id']; ?>" <?php echo ($product['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($cat['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Product Image (JPG, PNG, GIF - Max 5MB)</label>
                                <?php if($product['image']): ?>
                                    <div class="mb-2">
                                        <img src="../images/products/<?php echo htmlspecialchars($product['image']); ?>" style="max-width: 150px;" alt="Product">
                                    </div>
                                <?php endif; ?>
                                <input type="file" class="form-control" name="image" accept="image/*">
                                <small class="text-muted">Leave empty to keep current image</small>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" name="is_active" id="is_active" <?php echo ($product['is_active']) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="is_active">
                                    Active (visible to customers)
                                </label>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Update Product</button>
                                <a href="products.php" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
