<?php
$pageTitle = 'Manage Categories';
include '../includes/header.php';

// Handle Add Category
if (isset($_POST['add_category'])) {
    $name = trim($_POST['category_name']);
    if (!empty($name)) {
        if (addCategory($conn, $name)) {
            setMessage('Category added successfully!', 'success');
        } else {
            setMessage('Failed to add category. It may already exist.', 'error');
        }
        header("Location: categories.php");
        exit;
    }
}

// Handle Delete Category
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $category = getCategory($conn, $id);

    if ($category) {
        // Check if category is being used
        if (isCategoryUsed($conn, $category['name'])) {
            setMessage('Cannot delete category. It is being used by products.', 'error');
        } else {
            if (deleteCategory($conn, $id)) {
                setMessage('Category deleted successfully!', 'success');
            } else {
                setMessage('Failed to delete category.', 'error');
            }
        }
    }
    header("Location: categories.php");
    exit;
}

// Get all categories
$categories = getAllCategories($conn);

// Get flash message
$message = getMessage();
?>

<div class="page-header">
    <div>
        <div class="breadcrumb">Home > Categories</div>
        <h1 class="page-title">Manage Categories</h1>
    </div>
    <a href="products.php" class="add-btn" style="background: #6b7280;">← Back to Products</a>
</div>

<?php if ($message): ?>
    <div class="alert alert-<?= $message['type'] ?>"><?= clean($message['text']) ?></div>
<?php endif; ?>

<!-- Add Category Form -->
<div class="form-container" style="max-width: 600px; margin-bottom: 30px;">
    <h3 style="margin-bottom: 20px; color: #1f2937;">Add New Category</h3>
    <form method="post" style="display: flex; gap: 15px; align-items: end;">
        <div class="form-group" style="flex: 1; margin-bottom: 0;">
            <label>Category Name</label>
            <input type="text" name="category_name" placeholder="Enter category name" required>
        </div>
        <button type="submit" name="add_category" class="btn-primary"
            style="height: 48px; padding: 0 24px; white-space: nowrap;">
            + Add Category
        </button>
    </form>
</div>

<!-- Categories Table -->
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th style="width: 60px;">#</th>
                <th>Category Name</th>
                <th style="width: 150px;">Products Count</th>
                <th style="width: 120px;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($categories) > 0): ?>
                <?php
                $no = 1;
                foreach ($categories as $cat):
                    // Count products in this category
                    $stmt = $conn->prepare("SELECT COUNT(*) FROM products WHERE category = ?");
                    $stmt->execute([$cat['name']]);
                    $productCount = $stmt->fetchColumn();
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <strong><?= clean($cat['name']) ?></strong>
                        </td>
                        <td>
                            <span
                                style="display: inline-block; padding: 4px 12px; border-radius: 6px; background: #e0e7ff; color: #4c6ef5; font-weight: 600; font-size: 13px;">
                                <?= $productCount ?> products
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <?php if ($productCount == 0): ?>
                                    <button class="btn btn-delete"
                                        onclick="if(confirm('Are you sure you want to delete this category?')) { window.location.href='categories.php?delete=<?= $cat['id'] ?>'; }">
                                        Delete
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-delete" style="opacity: 0.5; cursor: not-allowed;"
                                        onclick="alert('Cannot delete category with existing products. Please reassign or delete products first.')">
                                        Delete
                                    </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align:center;color:#6b7280;padding: 40px;">
                        No categories found. Add your first category above.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div style="margin-top: 20px; padding: 20px; background: #fef3c7; border-radius: 12px; border: 1px solid #fbbf24;">
    <p style="margin: 0; color: #92400e; display: flex; align-items: center; gap: 8px;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="8" x2="12" y2="12"></line>
            <line x1="12" y1="16" x2="12.01" y2="16"></line>
        </svg>
        <strong>Note:</strong> Categories that are being used by products cannot be deleted. You must first reassign or
        delete all products using that category.
    </p>
</div>

<?php include '../includes/footer.php'; ?>