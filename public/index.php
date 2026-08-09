<?php
$pageTitle = 'Dashboard';
require_once __DIR__ . '/../includes/header.php';

// Get search term if exists
$search = $_GET['search'] ?? '';

// Get stats
$stats = getStats($conn);

// Get products (all or filtered by search)
if(!empty($search)) {
    // If searching, show filtered results
    $recentProducts = getAllProducts($conn, $search, '', '', '');
} else {
    // If not searching, show recent 10
    $recentProducts = $conn->query("SELECT * FROM products ORDER BY id DESC LIMIT 10")->fetchAll();
}
?>

<header class="header">
    <div class="search-box">
        <form method="get" style="display:flex;gap:10px;width:100%;">
            <input type="text" name="search" placeholder="Search products..." value="<?= clean($search) ?>" autocomplete="off">
            <button type="submit">🔍</button>
            <?php if(!empty($search)): ?>
            <a href="index.php" style="padding:12px 20px;background:#e5e7eb;color:#374151;border-radius:25px;text-decoration:none;font-weight:600;">Clear</a>
            <?php endif; ?>
        </form>
    </div>
</header>

<div class="page-header">
    <div>
        <div class="breadcrumb">Home</div>
        <h1 class="page-title">Dashboard</h1>
    </div>
</div>

<!-- Statistics Cards -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;margin-bottom:30px;">
    <div style="background:white;padding:25px;border-radius:16px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
        <h3 style="color:#6b7280;font-size:14px;margin-bottom:10px;">Total Products</h3>
        <p style="font-size:32px;font-weight:700;color:#4c6ef5;"><?= $stats['total_products'] ?></p>
    </div>
    <div style="background:white;padding:25px;border-radius:16px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
        <h3 style="color:#6b7280;font-size:14px;margin-bottom:10px;">Total Categories</h3>
        <p style="font-size:32px;font-weight:700;color:#10b981;"><?= $stats['total_categories'] ?></p>
    </div>
    <div style="background:white;padding:25px;border-radius:16px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
        <h3 style="color:#6b7280;font-size:14px;margin-bottom:10px;">Total Inventory</h3>
        <p style="font-size:32px;font-weight:700;color:#8b5cf6;"><?= number_format($stats['total_quantity']) ?></p>
    </div>
    <div style="background:white;padding:25px;border-radius:16px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
        <h3 style="color:#6b7280;font-size:14px;margin-bottom:10px;">Low Stock Items</h3>
        <p style="font-size:32px;font-weight:700;color:#ef4444;"><?= $stats['low_stock'] ?></p>
        <p style="font-size:12px;color:#9ca3af;margin-top:5px;">Items with quantity &lt; 10</p>
    </div>
</div>

<!-- Recent Products / Search Results -->
<div class="table-container">
    <h3 style="padding:20px;font-size:18px;color:#1f2937;">
        <?php if(!empty($search)): ?>
            Search Results for "<?= clean($search) ?>" (<?= count($recentProducts) ?> found)
        <?php else: ?>
            Recent Products
        <?php endif; ?>
    </h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Code</th>
                <th>Category</th>
            </tr>
        </thead>
        <tbody>
            <?php if(count($recentProducts) > 0): ?>
                <?php $no = 1; foreach($recentProducts as $p): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= clean($p['title']) ?></td>
                    <td><?= formatPrice($p['price']) ?></td>
                    <td>
                        <span style="display: inline-block; padding: 4px 12px; border-radius: 6px; font-weight: 600; font-size: 13px; 
                            <?php if($p['quantity'] < 10): ?>
                                background: #fee2e2; color: #dc2626;
                            <?php elseif($p['quantity'] < 50): ?>
                                background: #fef3c7; color: #d97706;
                            <?php else: ?>
                                background: #dcfce7; color: #16a34a;
                            <?php endif; ?>">
                            <?= $p['quantity'] ?? 0 ?>
                        </span>
                    </td>
                    <td><?= clean($p['product_code']) ?></td>
                    <td><?= clean($p['category']) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align:center;color:#6b7280;padding:40px;">
                        No products found<?php if(!empty($search)): ?> for "<?= clean($search) ?>"<?php endif; ?>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
