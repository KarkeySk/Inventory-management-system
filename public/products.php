<?php
$pageTitle = 'Products';
include '../includes/header.php';

// Get filter values
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$price = $_GET['price'] ?? '';

// Get products
$products = getAllProducts($conn, $search, $category, $price);
$categories = getCategories($conn);

// Get flash message
$message = getMessage();
?>

<div class="page-header">
	<div>
		<div class="breadcrumb">Home > Products</div>
		<h1 class="page-title">List Product</h1>
	</div>
	<div style="display: flex; gap: 10px;">
		<a href="categories.php" class="add-btn" style="background: #6b7280;">
			<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
				<rect x="3" y="3" width="7" height="7"></rect>
				<rect x="14" y="3" width="7" height="7"></rect>
				<rect x="14" y="14" width="7" height="7"></rect>
				<rect x="3" y="14" width="7" height="7"></rect>
			</svg>
			Manage Categories
		</a>
		<a href="add.php" class="add-btn">+ Add Product</a>
	</div>
</div>

<?php if($message): ?>
	<div class="alert alert-<?= $message['type'] ?>"><?= clean($message['text']) ?></div>
<?php endif; ?>

<!-- Search with Live Autocomplete -->
<div class="filters" style="margin-bottom: 15px;">
	<form onsubmit="return false;" style="display: grid; grid-template-columns: 1fr; gap: 0;">
		<div style="position: relative;">
			<input 
				type="text" 
				id="liveSearchBox" 
				placeholder="Search..." 
				autocomplete="off"
				style="width: 100%; padding: 14px 18px; border-radius: 12px; border: 1.5px solid #e5e7eb; font-size: 15px;">
		</div>
	</form>
</div>

<!-- Advanced Filters Row -->
<div class="filters">
	<form method="get" style="display: grid; grid-template-columns: 1fr 1fr auto auto; gap: 15px; align-items: center;">
		<select name="category" style="padding: 14px 18px; border-radius: 12px; border: 1.5px solid #e5e7eb;">
			<option value="">All Categories</option>
			<?php foreach($categories as $cat): ?>
				<option value="<?= $cat ?>" <?= $category == $cat ? 'selected' : '' ?>><?= clean($cat) ?></option>
			<?php endforeach; ?>
		</select>
		
		<input 
			type="number" 
			name="price" 
			placeholder="Max Price" 
			step="0.01" 
			value="<?= clean($price) ?>"
			style="padding: 14px 18px; border-radius: 12px; border: 1.5px solid #e5e7eb;">
		
		<button type="submit" style="padding: 14px 28px; border-radius: 12px; background: #4c6ef5; color: white; border: none; font-weight: 600; cursor: pointer; white-space: nowrap;">
			Filter
		</button>
		
		<a href="products.php" style="padding: 14px 24px; background: #e5e7eb; color: #374151; border-radius: 12px; text-decoration: none; font-weight: 600; white-space: nowrap; text-align: center;">
			Clear
		</a>
	</form>
</div>

<!-- Products Table -->
<div class="table-container">
	<table>
		<thead>
			<tr>
				<th>#</th>
				<th>Title</th>
				<th>Price</th>
				<th>Quantity</th>
				<th>Product Code</th>
				<th>Category</th>
				<th>Description</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody id="productTable">
			<?php if(count($products) > 0): ?>
				<?php $no = 1; foreach($products as $p): ?>
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
						<td><?= clean(substr($p['description'], 0, 50)) ?>...</td>
						<td>
							<div class="actions">
								<a href="edit.php?id=<?= $p['id'] ?>" class="btn btn-edit">Edit</a>
								<button class="btn btn-delete" onclick="deleteProduct(<?= $p['id'] ?>)">Delete</button>
							</div>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php else: ?>
				<tr>
					<td colspan="8" style="text-align:center;color:#6b7280;">No products found</td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>

<?php include '../includes/footer.php'; ?>
