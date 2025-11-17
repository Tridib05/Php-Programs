<?php
/**
 * Shopping Cart
 */
session_start();

if(!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle add to cart
if($_POST['action'] ?? '' === 'add') {
    $product_id = intval($_POST['product_id'] ?? 0);
    $quantity = intval($_POST['quantity'] ?? 1);
    
    if($product_id > 0 && $quantity > 0) {
        if(isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
    }
    header('Location: cart.php');
    exit;
}

// Handle remove from cart
if($_GET['remove'] ?? '' !== '') {
    $product_id = intval($_GET['remove']);
    unset($_SESSION['cart'][$product_id]);
    header('Location: cart.php');
    exit;
}

// Handle update quantities
if($_POST['action'] ?? '' === 'update') {
    foreach($_POST['quantities'] ?? [] as $product_id => $quantity) {
        $product_id = intval($product_id);
        $quantity = intval($quantity);
        if($quantity > 0) {
            $_SESSION['cart'][$product_id] = $quantity;
        } else {
            unset($_SESSION['cart'][$product_id]);
        }
    }
    header('Location: cart.php');
    exit;
}

require_once 'config/database.php';
$db = new Database();

$cart_items = [];
$total_price = 0;

if(!empty($_SESSION['cart'])) {
    $product_ids = array_keys($_SESSION['cart']);
    $placeholders = str_repeat('?,', count($product_ids) - 1) . '?';
    $products = $db->fetchAll(
        "SELECT * FROM products WHERE id IN ($placeholders) AND is_active = 1",
        $product_ids
    );

    foreach($products as $product) {
        $quantity = $_SESSION['cart'][$product['id']];
        $item = $product;
        $item['quantity'] = $quantity;
        $item['line_total'] = $product['price'] * $quantity;
        $cart_items[] = $item;
        $total_price += $item['line_total'];
    }
}

$page_title = 'Shopping Cart - E-Shop';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container my-5">
        <h2 class="mb-4">Shopping Cart</h2>

        <?php if(empty($cart_items)): ?>
            <div class="alert alert-info">
                Your cart is empty. <a href="products.php">Continue shopping</a>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-md-8">
                    <form method="POST">
                        <input type="hidden" name="action" value="update">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($cart_items as $item): ?>
                                    <tr>
                                        <td>
                                            <a href="product-detail.php?id=<?php echo $item['id']; ?>">
                                                <?php echo htmlspecialchars($item['name']); ?>
                                            </a>
                                        </td>
                                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                                        <td>
                                            <input type="number" name="quantities[<?php echo $item['id']; ?>]" value="<?php echo $item['quantity']; ?>" min="0" class="form-control" style="width: 80px;">
                                        </td>
                                        <td>$<?php echo number_format($item['line_total'], 2); ?></td>
                                        <td>
                                            <a href="?remove=<?php echo $item['id']; ?>" class="btn btn-sm btn-danger">Remove</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-primary">Update Cart</button>
                    </form>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Order Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <strong>Subtotal:</strong>
                                <span>$<?php echo number_format($total_price, 2); ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Shipping:</strong>
                                <span>Free</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <strong class="fs-5">Total:</strong>
                                <span class="fs-5">$<?php echo number_format($total_price, 2); ?></span>
                            </div>
                            <a href="checkout.php" class="btn btn-success w-100">Proceed to Checkout</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
