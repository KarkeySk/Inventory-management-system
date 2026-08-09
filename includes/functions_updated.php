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

// Get all categories from categories table
function getCategories($conn) {
    $stmt = $conn->query("SELECT name FROM categories ORDER BY name");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Get all categories with ID
function getAllCategories($conn) {
    $stmt = $conn->query("SELECT * FROM categories ORDER BY name");
    return $stmt->fetchAll();
}

// Get single category
function getCategory($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Add category
function addCategory($conn, $name) {
    try {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        return $stmt->execute([trim($name)]);
    } catch(PDOException $e) {
        return false;
    }
}

// Update category
function updateCategory($conn, $id, $name) {
    try {
        $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
        return $stmt->execute([trim($name), $id]);
    } catch(PDOException $e) {
        return false;
    }
}

// Delete category
function deleteCategory($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    return $stmt->execute([$id]);
}

// Check if category is being used by products
function isCategoryUsed($conn, $categoryName) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM products WHERE category = ?");
    $stmt->execute([$categoryName]);
    return $stmt->fetchColumn() > 0;
}

// Add product (with quantity)
function addProduct($conn, $data) {
    $stmt = $conn->prepare("INSERT INTO products (title, price, product_code, category, description, quantity) VALUES (?, ?, ?, ?, ?, ?)");
    return $stmt->execute([
        $data['title'],
        $data['price'],
        $data['product_code'],
        $data['category'],
        $data['description'],
        $data['quantity'] ?? 0
    ]);
}

// Update product (with quantity)
function updateProduct($conn, $id, $data) {
    $stmt = $conn->prepare("UPDATE products SET title=?, price=?, product_code=?, category=?, description=?, quantity=? WHERE id=?");
    return $stmt->execute([
        $data['title'],
        $data['price'],
        $data['product_code'],
        $data['category'],
        $data['description'],
        $data['quantity'] ?? 0,
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
    $stats['total_categories'] = $conn->query("SELECT COUNT(*) FROM categories")->fetchColumn();
    $stats['total_quantity'] = $conn->query("SELECT SUM(quantity) FROM products")->fetchColumn() ?? 0;
    $stats['low_stock'] = $conn->query("SELECT COUNT(*) FROM products WHERE quantity < 10")->fetchColumn();
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
