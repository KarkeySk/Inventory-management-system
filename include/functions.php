<?php
// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['admin']) && $_SESSION['admin'] === true;
}

// Redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}

// Sanitize output
function clean($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Format price
function formatPrice($price) {
    return number_format($price, 2);
}

// Get all products
function getAllProducts($conn, $search = '', $category = '', $price = '') {
    $where = "WHERE 1=1";
    $params = [];
    
    if(!empty($search)) {
        $where .= " AND (title LIKE ? OR product_code LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    
    if(!empty($category)) {
        $where .= " AND category = ?";
        $params[] = $category;
    }
    
    if(!empty($price)) {
        $where .= " AND price <= ?";
        $params[] = $price;
    }
    
    $stmt = $conn->prepare("SELECT * FROM products $where ORDER BY id DESC");
    $stmt->execute($params);
    return $stmt->fetchAll();
}

// Get single product
function getProduct($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Get all categories
function getCategories($conn) {
    $stmt = $conn->query("SELECT DISTINCT category FROM products ORDER BY category");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Add product
function addProduct($conn, $data) {
    $stmt = $conn->prepare("INSERT INTO products (title, price, product_code, category, description) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([
        $data['title'],
        $data['price'],
        $data['product_code'],
        $data['category'],
        $data['description']
    ]);
}

// Update product
function updateProduct($conn, $id, $data) {
    $stmt = $conn->prepare("UPDATE products SET title=?, price=?, product_code=?, category=?, description=? WHERE id=?");
    return $stmt->execute([
        $data['title'],
        $data['price'],
        $data['product_code'],
        $data['category'],
        $data['description'],
        $id
    ]);
}

// Delete product
function deleteProduct($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    return $stmt->execute([$id]);
}

// Get statistics
function getStats($conn) {
    $stats = [];
    $stats['total_products'] = $conn->query("SELECT COUNT(*) FROM products")->fetchColumn();
    $stats['total_categories'] = $conn->query("SELECT COUNT(DISTINCT category) FROM products")->fetchColumn();
    return $stats;
}

// Set flash message
function setMessage($message, $type = 'success') {
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $type;
}

// Get and clear flash message
function getMessage() {
    if(isset($_SESSION['message'])) {
        $msg = [
            'text' => $_SESSION['message'],
            'type' => $_SESSION['message_type']
        ];
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
        return $msg;
    }
    return null;
}
?>