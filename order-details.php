<?php
require 'config/db.php';
require 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

if (!$order_id) {
    header("Location: user-dashboard.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$order = $conn->query("SELECT * FROM orders WHERE id = $order_id AND client_id = $user_id")->fetch_assoc();

if (!$order) {
    header("Location: user-dashboard.php");
    exit;
}

$items = $conn->query("SELECT oi.*, p.name FROM order_items oi JOIN product p ON oi.product_id = p.id WHERE oi.order_id = $order_id")->fetch_all(MYSQLI_ASSOC);
$shipment = $conn->query("SELECT * FROM shipment WHERE order_id = $order_id")->fetch_assoc();
$client = $conn->query("SELECT * FROM client WHERE id = $user_id")->fetch_assoc();
?>

<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="user-dashboard.php">My Orders</a></li>
            <li class="breadcrumb-item active">Order #<?php echo $order['id']; ?></li>
        </ol>
    </nav>

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
                        <h5 class="card-title">Shipment Status</h5>
                        <p><strong>Status:</strong> <span class="badge bg-<?php echo $shipment['status'] == 'delivered' ? 'success' : ($shipment['status'] == 'shipping' ? 'info' : 'warning'); ?>"><?php echo ucfirst($shipment['status']); ?></span></p>
                        <p><strong>Delivery Address:</strong> <?php echo htmlspecialchars($shipment['adress_livraison']); ?></p>
                        <?php if ($shipment['date_ship']): ?>
                            <p><strong>Shipped Date:</strong> <?php echo date('M d, Y', strtotime($shipment['date_ship'])); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Order Summary</h5>
                    <p><strong>Order Date:</strong> <?php echo date('M d, Y', strtotime($order['order_date'])); ?></p>
                    <p><strong>Order Status:</strong> <span class="badge bg-<?php echo $order['status'] == 'delivered' ? 'success' : ($order['status'] == 'shipped' ? 'info' : ($order['status'] == 'cancelled' ? 'danger' : 'warning')); ?>"><?php echo ucfirst($order['status']); ?></span></p>
                    
                    <hr>
                    
                    <h6>Customer Info</h6>
                    <p class="small">
                        <strong><?php echo htmlspecialchars($client['name']); ?></strong><br>
                        <?php echo htmlspecialchars($client['email']); ?><br>
                        <?php echo htmlspecialchars($client['adress']); ?>
                    </p>

                    <hr>

                    <p class="mb-2">
                        <span>Subtotal:</span>
                        <strong class="float-end">$<?php echo number_format($order['total_price'] * 0.926, 2); ?></strong>
                    </p>
                    <p class="mb-3">
                        <span>Tax (8%):</span>
                        <strong class="float-end">$<?php echo number_format($order['total_price'] * 0.074, 2); ?></strong>
                    </p>
                    <div class="border-top pt-2">
                        <p class="fs-6">
                            <span>Total:</span>
                            <strong class="float-end text-success">$<?php echo number_format($order['total_price'], 2); ?></strong>
                        </p>
                    </div>
                </div>
            </div>

            <a href="user-dashboard.php" class="btn btn-outline-secondary w-100 mt-3">Back to Orders</a>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>
