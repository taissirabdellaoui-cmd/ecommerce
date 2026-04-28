<?php
require '../config/db.php';

// Admin check (MUST BE BEFORE ANY OUTPUT)
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../login.php?redirect=admin/dashboard.php");
    exit;
}

require '../includes/header.php';

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$product_id) {
    header("Location: manage-products.php");
    exit;
}

$product = $conn->query("SELECT * FROM product WHERE id = $product_id")->fetch_assoc();

if (!$product) {
    header("Location: manage-products.php");
    exit;
}

$categories = $conn->query("SELECT * FROM categories");
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name'] ?? '');
    $description = $conn->real_escape_string($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $category_id = (int)($_POST['category_id'] ?? 0);
    $stock = (int)($_POST['stock'] ?? 0);
    $image = $product['image'];

    if (empty($name) || $price <= 0) {
        $message = '<div class="alert alert-danger">Name and price are required.</div>';
    } else {
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $filename = $_FILES['image']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if (in_array($ext, $allowed)) {
                $upload_dir = '../images/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                
                // Delete old image
                if ($product['image'] && file_exists($upload_dir . $product['image'])) {
                    unlink($upload_dir . $product['image']);
                }
                
                $new_filename = time() . '_' . $filename;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $new_filename)) {
                    $image = $new_filename;
                } else {
                    $message = '<div class="alert alert-warning">Image upload failed, keeping existing image.</div>';
                }
            } else {
                $message = '<div class="alert alert-warning">Invalid image format. Allowed: jpg, jpeg, png, gif, webp</div>';
            }
        }

        $image = $conn->real_escape_string($image);
        $sql = "UPDATE product SET name = '$name', description = '$description', price = $price, category_id = $category_id, qtstock = $stock, image = '$image' WHERE id = $product_id";
        
        if ($conn->query($sql)) {
            $message = '<div class="alert alert-success">Product updated successfully.</div>';
            $product = $conn->query("SELECT * FROM product WHERE id = $product_id")->fetch_assoc();
        } else {
            $message = '<div class="alert alert-danger">Error updating product.</div>';
        }
    }
}
?>

<div class="container">
    <h1 class="mb-4">Edit Product</h1>

    <?php echo $message; ?>

    <div class="row">
        <div class="col-md-8">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="name" class="form-label">Product Name *</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4"><?php echo htmlspecialchars($product['description']); ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">Price *</label>
                        <input type="number" class="form-control" id="price" name="price" step="0.01" value="<?php echo $product['price']; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select class="form-select" id="category_id" name="category_id">
                            <option value="">Select Category</option>
                            <?php while ($cat = $categories->fetch_assoc()): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo $product['category_id'] == $cat['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="stock" class="form-label">Stock Quantity</label>
                        <input type="number" class="form-control" id="stock" name="stock" value="<?php echo $product['qtstock']; ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="image" class="form-label">Product Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        <small class="text-muted">Allowed: jpg, jpeg, png, gif, webp (max 5MB)</small>
                        <?php if ($product['image']): ?>
                            <div class="mt-2"><small class="text-success">Current image: <?php echo htmlspecialchars($product['image']); ?></small></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="manage-products.php" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require '../includes/footer.php'; ?>
