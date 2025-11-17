<?php
/**
 * Checkout Page
 */
session_start();

require_once 'config/database.php';
$db = new Database();

if(empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit;
}

$success = false;
$errors = [];

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name = trim($_POST['customer_name'] ?? '');
    $customer_email = trim($_POST['customer_email'] ?? '');
    $customer_phone = trim($_POST['customer_phone'] ?? '');
    $shipping_address = trim($_POST['shipping_address'] ?? '');

    if($customer_name === '') $errors[] = 'Name is required';
    if($customer_email === '' || !filter_var($customer_email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required';
    if($shipping_address === '') $errors[] = 'Shipping address is required';

    // Calculate total
    $product_ids = array_keys($_SESSION['cart']);
    $placeholders = str_repeat('?,', count($product_ids) - 1) . '?';
    $products = $db->fetchAll(
        "SELECT id, price FROM products WHERE id IN ($placeholders)",
        $product_ids
    );

    $total_price = 0;
    $total_items = 0;
    $stock_issues = [];

    foreach($products as $product) {
        $qty = $_SESSION['cart'][$product['id']];
        if($qty <= 0) {
            $stock_issues[] = 'Invalid quantity';
        }
        $total_price += $product['price'] * $qty;
        $total_items += $qty;
    }

    if(empty($errors) && empty($stock_issues)) {
        // Create order
        $order_data = [
            'customer_name' => $customer_name,
            'customer_email' => $customer_email,
            'customer_phone' => $customer_phone,
            'shipping_address' => $shipping_address,
            'total_items' => $total_items,
            'total_price' => $total_price,
            'status' => 'pending'
        ];
        
        $order_id = $db->insert('orders', $order_data);
        
        // Create order items
        foreach($products as $product) {
            $qty = $_SESSION['cart'][$product['id']];
            $item_data = [
                'order_id' => $order_id,
                'product_id' => $product['id'],
                'quantity' => $qty,
                'price' => $product['price']
            ];
            $db->insert('order_items', $item_data);
        }

        // Clear cart
        unset($_SESSION['cart']);
        $success = true;
    }
}

// Get cart items
$product_ids = array_keys($_SESSION['cart'] ?? []);
$cart_items = [];
$total_price = 0;

if(!empty($product_ids)) {
    $placeholders = str_repeat('?,', count($product_ids) - 1) . '?';
    $products = $db->fetchAll(
        "SELECT id, name, price FROM products WHERE id IN ($placeholders)",
        $product_ids
    );

    foreach($products as $product) {
        $qty = $_SESSION['cart'][$product['id']];
        $line_total = $product['price'] * $qty;
        $cart_items[] = [
            'name' => $product['name'],
            'quantity' => $qty,
            'price' => $product['price'],
            'line_total' => $line_total
        ];
        $total_price += $line_total;
    }
}

$page_title = 'Checkout - E-Shop';
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
        <h2 class="mb-4">Checkout</h2>

        <?php if($success): ?>
            <div class="alert alert-success">
                <h4>Order placed successfully!</h4>
                <p>Your order has been received. We'll process it shortly.</p>
                <a href="index.php" class="btn btn-primary">Continue Shopping</a>
            </div>
        <?php else: ?>
            <?php if(!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach($errors as $e) echo '<div>' . htmlspecialchars($e) . '</div>'; ?>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-8">
                    <form method="POST">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Shipping Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Full Name *</label>
                                    <input type="text" class="form-control" name="customer_name" required value="<?php echo htmlspecialchars($_POST['customer_name'] ?? ''); ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Email *</label>
                                    <input type="email" class="form-control" name="customer_email" required value="<?php echo htmlspecialchars($_POST['customer_email'] ?? ''); ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="tel" class="form-control" name="customer_phone" value="<?php echo htmlspecialchars($_POST['customer_phone'] ?? ''); ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Shipping Address *</label>
                                    <textarea class="form-control" name="shipping_address" rows="3" required><?php echo htmlspecialchars($_POST['shipping_address'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg">Place Order</button>
                        <a href="cart.php" class="btn btn-secondary btn-lg">Back to Cart</a>
                    </form>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Order Summary</h5>
                        </div>
                        <div class="card-body">
                            <?php foreach($cart_items as $item): ?>
                                <div class="d-flex justify-content-between mb-2">
                                    <div>
                                        <div><?php echo htmlspecialchars($item['name']); ?></div>
                                        <small class="text-muted">x<?php echo $item['quantity']; ?></small>
                                    </div>
                                    <div>$<?php echo number_format($item['line_total'], 2); ?></div>
                                </div>
                            <?php endforeach; ?>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <strong class="fs-5">Total:</strong>
                                <span class="fs-5">$<?php echo number_format($total_price, 2); ?></span>
                            </div>
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
