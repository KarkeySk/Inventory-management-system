<?php
$pageTitle = 'Edit Product';
include '../includes/header.php';

$id = $_GET['id'] ?? 0;
$product = getProduct($conn, $id);

if(!$product) {
    header("Location: products.php");
    exit;
}

// Get categories from database
$categories = getCategories($conn);

if(isset($_POST['submit'])){
    if(updateProduct($conn, $id, $_POST)) {
        setMessage('Product updated successfully!', 'success');
        header("Location: products.php");
        exit;
    } else {
        $error = "Failed to update product";
    }
}
?>

<div class="page-header">
    <div>
        <div class="breadcrumb">Home > Products > Edit</div>
        <h1 class="page-title">Edit Product</h1>
    </div>
</div>

<?php if(isset($error)): ?>
<div class="alert alert-error"><?= $error ?></div>
<?php endif; ?>

<div class="form-container">
    <form method="post">
        <div class="form-group">
            <label>Product Title</label>
            <input type="text" name="title" value="<?= clean($product['title']) ?>" required>
        </div>
        
        <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>Price</label>
                <input type="number" name="price" step="0.01" value="<?= $product['price'] ?>" required>
            </div>
            <div class="form-group">
                <label>Quantity</label>
                <input type="number" name="quantity" min="0" value="<?= $product['quantity'] ?? 0 ?>" required>
            </div>
        </div>
        
        <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>Product Code</label>
                <input type="text" name="product_code" value="<?= clean($product['product_code']) ?>" required>
            </div>
            <div class="form-group">
                <label>Category</label>
                <select name="category" required>
                    <option value="">Select Category</option>
                    <?php foreach($categories as $cat): ?>
                        <option value="<?= clean($cat) ?>" <?= $product['category'] == $cat ? 'selected' : '' ?>>
                            <?= clean($cat) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" required><?= clean($product['description']) ?></textarea>
        </div>
        
        <div class="form-actions">
            <button type="submit" name="submit" class="btn-primary">Update Product</button>
            <a href="products.php" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<style>
.form-row {
    margin-bottom: 0;
}
</style>

<?php include '../includes/footer.php'; ?>
