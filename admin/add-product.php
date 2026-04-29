<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require '../config/db.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../login.php?redirect=admin/dashboard.php");
    exit;
}

require 'header-admin.php';

$categories = $conn->query("SELECT * FROM categories");
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name'] ?? '');
    $description = $conn->real_escape_string($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $category_id = (int)($_POST['category_id'] ?? 0);
    $stock = (int)($_POST['stock'] ?? 0);
    $image = '';

    if (empty($name) || $price <= 0) {
        $message = '<div class="alert alert-danger">Name and price are required.</div>';
    } else {
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $filename = $_FILES['image']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if (in_array($ext, $allowed)) {
                $upload_dir = '../images/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                
                $new_filename = time() . '_' . $filename;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $new_filename)) {
                    $image = $new_filename;
                } else {
                    $message = '<div class="alert alert-warning">Image upload failed, product added without image.</div>';
                }
            } else {
                $message = '<div class="alert alert-warning">Invalid image format. Allowed: jpg, jpeg, png, gif, webp</div>';
            }
        }

        $image = $conn->real_escape_string($image);
        $sql = "INSERT INTO product (name, description, price, category_id, qtstock, image) VALUES ('$name', '$description', $price, $category_id, $stock, '$image')";
        
        if ($conn->query($sql)) {
            $message = '<div class="alert alert-success">Product added successfully.</div>';
            header("Location: manage-products.php");
            exit;
        } else {
            $message = '<div class="alert alert-danger">Error adding product.</div>';
        }
    }
}
?>

<div class="container">
    <h1 class="mb-4">Add New Product</h1>

    <?php echo $message; ?>

    <div class="row">
        <div class="col-md-8">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="name" class="form-label">Product Name *</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">Price *</label>
                        <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select class="form-select" id="category_id" name="category_id">
                            <option value="">Select Category</option>
                            <?php while ($cat = $categories->fetch_assoc()): ?>
                                <option value="<?php echo $cat['id']; ?>">
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="stock" class="form-label">Stock Quantity</label>
                        <input type="number" class="form-control" id="stock" name="stock" value="0">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="image" class="form-label">Product Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        <small class="text-muted">Allowed: jpg, jpeg, png, gif, webp (max 5MB)</small>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Add Product</button>
                    <a href="manage-products.php" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require 'footer-admin.php'; ?>
