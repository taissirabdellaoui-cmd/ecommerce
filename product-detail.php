<?php
require 'config/db.php';
require 'includes/header.php';

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$message = '';

if (!$product_id) {
    header("Location: index.php");
    exit;
}

// Get product details
$query = "SELECT p.*, c.name as category_name FROM product p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = $product_id";
$result = $conn->query($query);
$product = $result->fetch_assoc();

if (!$product) {
    header("Location: index.php");
    exit;
}

// Handle add to cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    
    if ($quantity > 0 && $quantity <= $product['qtstock']) {
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $product_id) {
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }
        if (!$found) {
            $_SESSION['cart'][] = [
                'id' => $product_id,
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity,
                'image' => $product['image']
            ];
        }
        $message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Product added to cart! <a href="cart.php" class="alert-link">View Cart</a>
                    </div>';
    }
}
?>

<div class="container">
    <?php echo $message; ?>
    
    <nav aria-label="breadcrumb" style="background-color: transparent;">
        <ol class="breadcrumb" style="background-color: #0f0f0f; border: 1px solid #333; border-radius: 4px;">
            <li class="breadcrumb-item"><a href="index.php" style="color: #00d4ff; text-decoration: none;">Home</a></li>
            <li class="breadcrumb-item"><a href="index.php?category=<?php echo $product['category_id']; ?>" style="color: #00d4ff; text-decoration: none;"><?php echo htmlspecialchars($product['category_name']); ?></a></li>
            <li class="breadcrumb-item active" style="color: #b0b0b0;"><?php echo htmlspecialchars($product['name']); ?></li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-6">
            <div class="card" style="background-color: #0f0f0f; border: 1px solid #333;">
                <div class="card-body p-0">
                    <img src="images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="img-fluid" style="min-height: 400px; object-fit: cover; border-radius: 4px;" onerror="this.src='https://via.placeholder.com/400?text=<?php echo urlencode($product['name']); ?>'">
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <h1 style="font-family: 'Bebas Neue', serif; letter-spacing: 2px; font-weight: 900; color: #00d4ff; text-shadow: 3px 3px 0px rgba(0, 255, 136, 0.4), 2px 2px 0px rgba(0, 0, 0, 0.8);"><?php echo htmlspecialchars($product['name']); ?></h1>
            <p class="text-muted" style="text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px;"><?php echo htmlspecialchars($product['category_name']); ?></p>
            
            <h2 style="color: #00d4ff; margin-bottom: 20px; font-weight: 900; font-size: 2rem;">$<?php echo number_format($product['price'], 2); ?></h2>

            <div class="mb-3">
                <span class="badge" style="background: linear-gradient(135deg, #00d4ff, #0099cc); font-weight: 700; padding: 8px 12px; text-transform: uppercase; letter-spacing: 0.5px;">Stock: <?php echo $product['qtstock']; ?></span>
                <?php if ($product['qtstock'] <= 5): ?>
                    <span class="badge bg-warning">Low Stock!</span>
                <?php endif; ?>
            </div>

            <p class="fs-5 mb-4"><?php echo htmlspecialchars($product['description']); ?></p>

            <form method="POST">
                <div class="mb-3">
                    <label for="quantity" class="form-label" style="text-transform: uppercase; font-weight: 600; font-size: 0.85rem; letter-spacing: 1px; color: #b0b0b0;">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" class="form-control" style="background-color: #0f0f0f; border: 1px solid #333; color: #fff;" value="1" min="1" max="<?php echo $product['qtstock']; ?>">
                </div>

                <?php if ($product['qtstock'] > 0): ?>
                    <button type="submit" name="add_to_cart" class="btn btn-primary btn-lg w-100 mb-2" style="text-transform: uppercase; font-weight: 900; letter-spacing: 1px;">
                        <i class="bi bi-cart-plus"></i> Add to Cart
                    </button>
                <?php else: ?>
                    <button class="btn btn-secondary btn-lg w-100 mb-2" disabled>Out of Stock</button>
                <?php endif; ?>

                <a href="index.php" class="btn btn-outline-secondary btn-lg w-100" style="text-transform: uppercase; font-weight: 600; border-color: #333; color: #b0b0b0;">
                    <i class="bi bi-arrow-left"></i> Continue Shopping
                </a>
            </form>

            <div class="mt-4 p-4 rounded" style="background-color: #0f0f0f; border: 2px solid rgba(0, 212, 255, 0.2);">
                <h5 style="color: #00d4ff; font-weight: 900; text-transform: uppercase; letter-spacing: 1px;"><i class="bi bi-info-circle" style="margin-right: 8px;"></i>Product Information</h5>
                <ul class="list-unstyled mt-3" style="color: #b0b0b0;">
                    <li style="padding: 8px 0; border-bottom: 1px solid #333;"><strong style="color: #00d4ff;">Product ID:</strong> #<?php echo $product['id']; ?></li>
                    <li style="padding: 8px 0; border-bottom: 1px solid #333;"><strong style="color: #00d4ff;">Category:</strong> <?php echo htmlspecialchars($product['category_name']); ?></li>
                    <li style="padding: 8px 0;"><strong style="color: #00d4ff;">Stock Available:</strong> <?php echo $product['qtstock']; ?> units</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>
