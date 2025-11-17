<?php
/**
 * Delete Product
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
    header('Location: products.php');
    exit;
}

$product = $db->fetchOne("SELECT * FROM products WHERE id = ?", [$product_id]);
if(!$product) {
    header('Location: products.php');
    exit;
}

// Delete image if exists
if($product['image'] && file_exists(__DIR__ . '/../images/products/' . $product['image'])) {
    unlink(__DIR__ . '/../images/products/' . $product['image']);
}

// Delete product
$db->delete('products', 'id', $product_id);

header('Location: products.php?success=Product deleted successfully');
exit;
