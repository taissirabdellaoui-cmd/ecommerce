<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=checkout");
    exit;
}

if (count($_SESSION['cart']) == 0) {
    header("Location: cart.php");
    exit;
}

$message = '';
$order_id = null;
$subtotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$tax = $subtotal * 0.08;
$total = $subtotal + $tax;
$user_id = $_SESSION['user_id'];
$user_result = $conn->query("SELECT * FROM client WHERE id = $user_id");
$user = $user_result->fetch_assoc();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $address = trim($_POST['address'] ?? $user['adress']);
    
    if (empty($address)) {
        $message = '<div class="alert alert-danger">Delivery address is required.</div>';
    } else {
        $order_date = date('Y-m-d');
        $address = $conn->real_escape_string($address);
        $total_price = floatval($total);
        $user_id_safe = intval($user_id);
        $sql = "INSERT INTO orders (`client_id`, `order_date`, `status`, `total_price`) VALUES ({$user_id_safe}, '{$order_date}', 'pending', {$total_price})";
        
        if ($conn->query($sql)) {
            $order_id = $conn->insert_id;
            $items_inserted = true;
            foreach ($_SESSION['cart'] as $item) {
                $product_id = intval($item['id']);
                $quantity = intval($item['quantity']);
                $unit_price = floatval($item['price']);
                
                $item_sql = "INSERT INTO order_items (`order_id`, `product_id`, `quantity`, `unit_price`) VALUES ({$order_id}, {$product_id}, {$quantity}, {$unit_price})";
                if (!$conn->query($item_sql)) {
                    $items_inserted = false;
                    $message .= '<div class="alert alert-warning">Warning: Could not insert order item - ' . $conn->error . '</div>';
                }
            }
            $ship_sql = "INSERT INTO shipment (`order_id`, `status`, `adress_livraison`) VALUES ({$order_id}, 'preparing', '{$address}')";
            if (!$conn->query($ship_sql)) {
                $message .= '<div class="alert alert-warning">Warning: Could not create shipment record - ' . $conn->error . '</div>';
            }
            $_SESSION['cart'] = [];
            if ($items_inserted) {
                header("Location: order-confirmation.php?order_id=$order_id");
                exit;
            }
        } else {
            $message = '<div class="alert alert-danger">Error creating order:<br>' . htmlspecialchars($conn->error) . '<br><small>SQL: ' . htmlspecialchars($sql) . '</small></div>';
        }
    }
}
require 'includes/header.php';
?>
?>

<div class="container">
    <h1 class="mb-4" style="font-family: 'Bebas Neue', serif; letter-spacing: 2px; font-weight: 900; color: #00d4ff; text-shadow: 3px 3px 0px rgba(0, 255, 136, 0.4), 2px 2px 0px rgba(0, 0, 0, 0.8);">SECURE YOUR GEAR</h1>

    <?php echo $message; ?>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4" style="background-color: #0f0f0f; border: 1px solid #333; border-radius: 4px;">
                <div class="card-body">
                    <h5 class="card-title" style="color: #00d4ff; font-weight: 900; text-transform: uppercase; letter-spacing: 1px;">Delivery Information</h5>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label" style="color: #b0b0b0; text-transform: uppercase; font-weight: 600; font-size: 0.85rem; letter-spacing: 1px;">Full Name</label>
                            <input type="text" class="form-control" id="name" value="<?php echo htmlspecialchars($user['name']); ?>" disabled style="background-color: #1a1a1a; border: 1px solid #333; color: #b0b0b0;">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label" style="color: #b0b0b0; text-transform: uppercase; font-weight: 600; font-size: 0.85rem; letter-spacing: 1px;">Email</label>
                            <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled style="background-color: #1a1a1a; border: 1px solid #333; color: #b0b0b0;">
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label" style="color: #b0b0b0; text-transform: uppercase; font-weight: 600; font-size: 0.85rem; letter-spacing: 1px;">Delivery Address *</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required style="background-color: #0f0f0f; border: 1px solid #333; color: #fff;"><?php echo htmlspecialchars($user['adress']); ?></textarea>
                        </div>

                        <h5 class="card-title mt-4" style="color: #00d4ff; font-weight: 900; text-transform: uppercase; letter-spacing: 1px;">Order Items</h5>
                        <div class="table-responsive" style="background-color: #0f0f0f; border: 1px solid #333; border-radius: 4px;">
                            <table class="table table-sm" style="margin: 0; color: #b0b0b0;">
                                <thead style="background-color: rgba(0, 212, 255, 0.1); border-bottom: 2px solid #00d4ff;">
                                    <tr>
                                        <th style="color: #00d4ff; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px;">Product</th>
                                        <th style="color: #00d4ff; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px;">Price</th>
                                        <th style="color: #00d4ff; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px;">Qty</th>
                                        <th style="color: #00d4ff; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px;">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($_SESSION['cart'] as $item): ?>
                                        <tr style="border-bottom: 1px solid #333;">
                                            <td style="color: #fff;"><?php echo htmlspecialchars($item['name']); ?></td>
                                            <td style="color: #00d4ff; font-weight: 600;">$<?php echo number_format($item['price'], 2); ?></td>
                                            <td style="color: #b0b0b0;"><?php echo $item['quantity']; ?></td>
                                            <td style="color: #00d4ff; font-weight: 600;">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-2" style="text-transform: uppercase; font-weight: 900; letter-spacing: 1px;">
                                <i class="bi bi-check-circle"></i> Place Order
                            </button>
                            <a href="cart.php" class="btn btn-outline-secondary btn-lg w-100" style="text-transform: uppercase; font-weight: 600; border-color: #333; color: #b0b0b0;">Back to Cart</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card sticky-top" style="background-color: #0f0f0f; border: 2px solid rgba(0, 212, 255, 0.2); border-radius: 4px; top: 20px;">
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
                        <div class="d-flex justify-content-between" style="padding-top: 8px;">
                            <span class="fs-5" style="color: #b0b0b0;">Total:</span>
                            <strong class="fs-5" style="color: #00d4ff; font-weight: 900;">$<?php echo number_format($total, 2); ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>
