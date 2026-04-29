<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'config/db.php';
if (isset($_GET['remove'])) {
    $remove_id = (int)$_GET['remove'];
    $_SESSION['cart'] = array_filter($_SESSION['cart'], function($item) use ($remove_id) {
        return $item['id'] != $remove_id;
    });
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    header("Location: cart.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $product_id => $quantity) {
        $quantity = (int)$quantity;
        if ($quantity <= 0) {
            foreach ($_SESSION['cart'] as $key => $item) {
                if ($item['id'] == $product_id) {
                    unset($_SESSION['cart'][$key]);
                    break;
                }
            }
        } else {
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['id'] == $product_id) {
                    $item['quantity'] = $quantity;
                    break;
                }
            }
        }
    }
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    $message = '<div class="alert alert-success alert-dismissible fade show" role="alert">Cart updated!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
}

if (!isset($message)) {
    $message = '';
}
require 'includes/header.php';
$subtotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$tax = $subtotal * 0.08;
$total = $subtotal + $tax;
?>

<div class="container">
    <h1 class="mb-4" style="font-family: 'Bebas Neue', serif; letter-spacing: 2px; font-weight: 900; color: #00d4ff; text-shadow: 3px 3px 0px rgba(0, 255, 136, 0.4), 2px 2px 0px rgba(0, 0, 0, 0.8);">YOUR CART</h1>

    <?php echo $message; ?>

    <?php if (count($_SESSION['cart']) > 0): ?>
        <div class="row">
            <div class="col-md-8">
                <form method="POST">
                    <div class="table-responsive" style="background-color: #0f0f0f; border: 1px solid #333; border-radius: 4px;">
                        <table class="table" style="margin: 0; color: #fff;">
                            <thead style="background-color: rgba(0, 212, 255, 0.1); border-bottom: 2px solid #00d4ff;">
                                <tr>
                                    <th style="color: #00d4ff; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px;">Product</th>
                                    <th style="color: #00d4ff; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px;">Price</th>
                                    <th style="color: #00d4ff; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px;">Quantity</th>
                                    <th style="color: #00d4ff; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px;">Subtotal</th>
                                    <th style="color: #00d4ff; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($_SESSION['cart'] as $item): ?>
                                    <tr style="border-bottom: 1px solid #333; color: #b0b0b0;">
                                        <td style="color: #fff;">
                                            <a href="product-detail.php?id=<?php echo $item['id']; ?>" class="text-decoration-none" style="color: #00d4ff;">
                                                <?php echo htmlspecialchars($item['name']); ?>
                                            </a>
                                        </td>
                                        <td style="color: #00d4ff; font-weight: 600;">$<?php echo number_format($item['price'], 2); ?></td>
                                        <td>
                                            <input type="number" name="quantity[<?php echo $item['id']; ?>]" value="<?php echo $item['quantity']; ?>" min="0" class="form-control" style="width: 80px; background-color: #1a1a1a; border: 1px solid #333; color: #fff;">
                                        </td>
                                        <td style="color: #00d4ff; font-weight: 600;">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                        <td>
                                            <a href="cart.php?remove=<?php echo $item['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Remove this item?')" style="background-color: #dc3545; border-color: #dc3545;">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" name="update_cart" class="btn btn-primary" style="text-transform: uppercase; font-weight: 900; letter-spacing: 1px;">Update Cart</button>
                        <a href="index.php" class="btn btn-outline-secondary" style="text-transform: uppercase; font-weight: 600; border-color: #333; color: #b0b0b0;">Continue Shopping</a>
                    </div>
                </form>
            </div>

            <div class="col-md-4">
                <div class="card" style="background-color: #0f0f0f; border: 2px solid rgba(0, 212, 255, 0.2); border-radius: 4px;">
                    <div class="card-body">
                        <h5 class="card-title" style="color: #00d4ff; font-weight: 900; text-transform: uppercase; letter-spacing: 1px;">Order Summary</h5>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2" style="color: #b0b0b0; border-bottom: 1px solid #333; padding-bottom: 8px;">
                                <span>Subtotal:</span>
                                <strong style="color: #fff;">$<?php echo number_format($subtotal, 2); ?></strong>
                            </div>
                            <div class="d-flex justify-content-between mb-3" style="color: #b0b0b0; border-bottom: 1px solid #333; padding-bottom: 8px;">
                                <span>Tax (8%):</span>
                                <strong style="color: #fff;">$<?php echo number_format($tax, 2); ?></strong>
                            </div>
                            <div class="d-flex justify-content-between mb-3" style="padding-top: 8px;">
                                <span class="fs-5" style="color: #b0b0b0;">Total:</span>
                                <strong class="fs-5" style="color: #00d4ff; font-weight: 900;">$<?php echo number_format($total, 2); ?></strong>
                            </div>
                        </div>
                        
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="checkout.php" class="btn btn-primary w-100" style="text-transform: uppercase; font-weight: 900; letter-spacing: 1px; margin-top: 15px;">
                                <i class="bi bi-credit-card"></i> Proceed to Checkout
                            </a>
                        <?php else: ?>
                            <div class="alert alert-dismissible fade show" style="background-color: rgba(255, 0, 110, 0.1); border: 2px solid #ff006e; color: #b0b0b0; border-radius: 4px; margin-top: 15px; margin-bottom: 0;" role="alert">
                                <p class="mb-2" style="color: #ff006e; font-weight: 900; text-transform: uppercase; letter-spacing: 1px;">⚡ Login Required!</p>
                                <a href="login.php?redirect=checkout" class="btn btn-primary w-100 mb-2" style="text-transform: uppercase; font-weight: 900; letter-spacing: 1px;">
                                    <i class="bi bi-lock"></i> Login to Checkout
                                </a>
                                <p class="text-muted small mb-0" style="color: #b0b0b0; font-size: 0.85rem;">Don't have an account? <a href="register.php" style="color: #00d4ff; text-decoration: none;">Register here</a></p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" style="filter: brightness(2);"></button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert" style="background-color: rgba(0, 212, 255, 0.1); border: 2px solid #00d4ff; color: #b0b0b0; text-align: center; border-radius: 4px;">
            <p style="margin: 0; font-size: 1.1rem; color: #00d4ff; font-weight: 900;">Your cart is empty</p>
            <a href="index.php" style="color: #00d4ff; text-decoration: none; font-weight: 600;">⬅ Continue shopping</a>
        </div>
    <?php endif; ?>
</div>

<?php require 'includes/footer.php'; ?>
