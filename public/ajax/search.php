<?php
require_once '../../config/db.php';
require_once '../../includes/functions.php';

if(!isLoggedIn()) {
    echo "Not logged in";
    exit;
}

$search = $_GET['search'] ?? '';

$sql = "SELECT * FROM products WHERE title LIKE ? OR product_code LIKE ? ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$searchTerm = "%$search%";
$stmt->execute([$searchTerm, $searchTerm]);

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(count($products) == 0) {
    echo '<tr><td colspan="8" style="text-align:center;color:#6b7280;">No products found</td></tr>';
    exit;
}

$no = 1;
foreach($products as $p) {
    // Determine quantity badge color
    $quantity = $p['quantity'] ?? 0;
    if($quantity < 10) {
        $badge_style = 'background: #fee2e2; color: #dc2626;';
    } elseif($quantity < 50) {
        $badge_style = 'background: #fef3c7; color: #d97706;';
    } else {
        $badge_style = 'background: #dcfce7; color: #16a34a;';
    }
    
    echo '<tr>';
    echo '<td>'.$no++.'</td>';
    echo '<td>'.htmlspecialchars($p['title']).'</td>';
    echo '<td>'.number_format($p['price'], 2).'</td>';
    echo '<td><span style="display: inline-block; padding: 4px 12px; border-radius: 6px; font-weight: 600; font-size: 13px; '.$badge_style.'">'.$quantity.'</span></td>';
    echo '<td>'.htmlspecialchars($p['product_code']).'</td>';
    echo '<td>'.htmlspecialchars($p['category']).'</td>';
    echo '<td>'.htmlspecialchars(substr($p['description'], 0, 50)).'...</td>';
    echo '<td>
            <div class="actions">
                <a href="edit.php?id='.$p['id'].'" class="btn btn-edit">Edit</a>
                <button class="btn btn-delete" onclick="deleteProduct('.$p['id'].')">Delete</button>
            </div>
          </td>';
    echo '</tr>';
}
?>
