<?php
require 'config/db.php';
require 'includes/header.php';

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

if (!$order_id) {
    header("Location: index.php");
    exit;
}

$order = $conn->query("SELECT * FROM orders WHERE id = $order_id")->fetch_assoc();
$items = $conn->query("SELECT oi.*, p.name FROM order_items oi JOIN product p ON oi.product_id = p.id WHERE oi.order_id = $order_id")->fetch_all(MYSQLI_ASSOC);
$shipment = $conn->query("SELECT * FROM shipment WHERE order_id = $order_id")->fetch_assoc();

if (!$order) {
    header("Location: index.php");
    exit;
}
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-success">
                <div class="card-body text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                    </div>
                    <h1 class="card-title text-success">Order Placed Successfully!</h1>
                    <p class="card-text text-muted">Thank you for your purchase.</p>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">Order Details</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Order ID:</strong> #<?php echo $order['id']; ?></p>
                            <p><strong>Order Date:</strong> <?php echo date('M d, Y', strtotime($order['order_date'])); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Status:</strong> <span class="badge bg-info">Pending Confirmation</span></p>
                            <p><strong>Total:</strong> <span class="fs-5 text-success">$<?php echo number_format($order['total_price'], 2); ?></span></p>
                        </div>
                    </div>

                    <h5 class="card-title mt-4">Order Items</h5>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Unit Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                                        <td>$<?php echo number_format($item['unit_price'], 2); ?></td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td>$<?php echo number_format($item['unit_price'] * $item['quantity'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if ($shipment): ?>
                        <h5 class="card-title mt-4">Shipment Information</h5>
                        <p><strong>Delivery Address:</strong> <?php echo htmlspecialchars($shipment['adress_livraison']); ?></p>
                        <p><strong>Shipment Status:</strong> <span class="badge bg-secondary"><?php echo ucfirst($shipment['status']); ?></span></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mt-4 text-center">
                <a href="user-dashboard.php" class="btn btn-primary me-2">View My Orders</a>
                <a href="index.php" class="btn btn-outline-secondary">Continue Shopping</a>
            </div>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>
