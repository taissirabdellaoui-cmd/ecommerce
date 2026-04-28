<?php
require '../config/db.php';

// Admin check (MUST BE BEFORE ANY OUTPUT)
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../login.php?redirect=admin/dashboard.php");
    exit;
}

require '../includes/header.php';

$message = '';

// Handle product deletion
if (isset($_GET['delete'])) {
    $product_id = (int)$_GET['delete'];
    if ($conn->query("DELETE FROM product WHERE id = $product_id")) {
        $message = '<div class="alert alert-success">Product deleted successfully.</div>';
    }
}

$products = $conn->query("SELECT p.*, c.name as category_name FROM product p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC");
?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Products</h1>
        <a href="add-product.php" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New Product
        </a>
    </div>

    <?php echo $message; ?>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($product = $products->fetch_assoc()): ?>
                    <tr>
                        <td>#<?php echo $product['id']; ?></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo htmlspecialchars($product['category_name'] ?? 'N/A'); ?></td>
                        <td>$<?php echo number_format($product['price'], 2); ?></td>
                        <td><?php echo $product['qtstock']; ?></td>
                        <td>
                            <a href="edit-product.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="manage-products.php?delete=<?php echo $product['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <a href="dashboard.php" class="btn btn-outline-secondary mt-3">Back to Dashboard</a>
</div>

<?php require '../includes/footer.php'; ?>
