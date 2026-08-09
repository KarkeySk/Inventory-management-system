<?php
// Delete Product (Non-AJAX fallback)
require_once '../config/db.php';
require_once '../includes/functions.php';

// Check if user is logged in
requireLogin();

// Check if ID is provided via GET or POST
$id = $_GET['id'] ?? $_POST['id'] ?? 0;
$id = (int) $id;

if ($id <= 0) {
    setMessage('Invalid product ID', 'error');
    header("Location: products.php");
    exit;
}

// Try to delete the product
try {
    if (deleteProduct($conn, $id)) {
        setMessage('Product deleted successfully!', 'success');
    } else {
        setMessage('Failed to delete product. Please try again.', 'error');
    }
} catch (Exception $e) {
    setMessage('An error occurred while deleting the product.', 'error');
}

// Redirect back to products page
header("Location: products.php");
exit;
?>
