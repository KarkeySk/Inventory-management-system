<?php
// AJAX Delete Product Handler
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't show errors in AJAX response

// Include configuration and functions
require_once '../../config/db.php';
require_once '../../includes/functions.php';

// Check if user is logged in
requireLogin();

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "error:Invalid request method";
    exit;
}

// Check if ID is provided
if (!isset($_POST['id']) || empty($_POST['id'])) {
    echo "error:Product ID not provided";
    exit;
}

$id = (int) $_POST['id'];

// Validate ID
if ($id <= 0) {
    echo "error:Invalid product ID";
    exit;
}

// Try to delete the product
try {
    if (deleteProduct($conn, $id)) {
        echo "success:Product deleted successfully!";
    } else {
        echo "error:Failed to delete product. Please try again.";
    }
} catch (Exception $e) {
    echo "error:An error occurred while deleting the product.";
}
?>
