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

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$order_id) {
    header("Location: manage-orders.php");
    exit;
}

$order = $conn->query("SELECT * FROM orders WHERE id = $order_id")->fetch_assoc();

if (!$order) {
    header("Location: manage-orders.php");
    exit;
}

$customer = $conn->query("SELECT * FROM client WHERE id = " . $order['client_id'])->fetch_assoc();
$items = $conn->query("SELECT oi.*, p.name FROM order_items oi JOIN product p ON oi.product_id = p.id WHERE oi.order_id = $order_id")->fetch_all(MYSQLI_ASSOC);
$shipment = $conn->query("SELECT * FROM shipment WHERE order_id = $order_id")->fetch_assoc();
?>

<div class="container">
    <h1 class="mb-4">Order #<?php echo $order['id']; ?></h1>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Order Items</h5>
                    <div class="table-responsive">
                        <table class="table">
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
                </div>
            </div>

            <?php if ($shipment): ?>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Shipment Details</h5>
                        <p><strong>Address:</strong> <?php echo htmlspecialchars($shipment['adress_livraison']); ?></p>
                        <p><strong>Status:</strong> <span class="badge bg-info"><?php echo ucfirst($shipment['status']); ?></span></p>
                        <?php if ($shipment['date_ship']): ?>
                            <p><strong>Ship Date:</strong> <?php echo date('M d, Y', strtotime($shipment['date_ship'])); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Customer Info</h5>
                    <p><strong><?php echo htmlspecialchars($customer['name']); ?></strong></p>
                    <p><?php echo htmlspecialchars($customer['email']); ?></p>
                    <p class="small text-muted"><?php echo htmlspecialchars($customer['adress']); ?></p>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Order Summary</h5>
                    <p><strong>Date:</strong> <?php echo date('M d, Y', strtotime($order['order_date'])); ?></p>
                    <p><strong>Status:</strong> <span class="badge bg-info"><?php echo ucfirst($order['status']); ?></span></p>
                    <hr>
                    <p><strong>Total:</strong> <strong class="text-success">$<?php echo number_format($order['total_price'], 2); ?></strong></p>
                </div>
            </div>
        </div>
    </div>

    <a href="manage-orders.php" class="btn btn-outline-secondary mt-3">Back to Orders</a>
</div>

<?php require 'footer-admin.php'; ?>
