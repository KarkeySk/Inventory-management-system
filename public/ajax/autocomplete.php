<?php
require_once '../../config/db.php';
require_once '../../includes/functions.php';

if(!isLoggedIn()) {
    echo json_encode([]);
    exit;
}

$term = $_GET['term'] ?? '';

if(empty($term)) {
    echo json_encode([]);
    exit;
}

$stmt = $conn->prepare("SELECT DISTINCT title FROM products WHERE title LIKE ? LIMIT 10");
$stmt->execute(["%$term%"]);
$results = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo json_encode($results);
?>