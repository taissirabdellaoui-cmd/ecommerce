

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require '../config/db.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../login.php?redirect=admin/dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">Admin Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="manage-products.php">Products</a></li>
                <li class="nav-item"><a class="nav-link" href="manage-orders.php">Orders</a></li>
                <li class="nav-item"><a class="nav-link" href="manage-customers.php">Customers</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Admin Dashboard</h1>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>

    <?php
    $total_orders = $conn->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'];
    $total_revenue = $conn->query("SELECT SUM(total_price) as total FROM orders")->fetch_assoc()['total'];
    $total_products = $conn->query("SELECT COUNT(*) as count FROM product")->fetch_assoc()['count'];
    $total_customers = $conn->query("SELECT COUNT(*) as count FROM client")->fetch_assoc()['count'];
    ?>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Total Orders</h6>
                    <h2><?php echo $total_orders; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Total Revenue</h6>
                    <h2>$<?php echo number_format($total_revenue ?? 0, 2); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Total Products</h6>
                    <h2><?php echo $total_products; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Total Customers</h6>
                    <h2><?php echo $total_customers; ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <h3>Recent Orders</h3>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $recent_orders = $conn->query("SELECT o.*, c.name FROM orders o JOIN client c ON o.client_id = c.id ORDER BY o.order_date DESC LIMIT 5");
                        while ($order = $recent_orders->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo $order['id']; ?></td>
                                <td><?php echo htmlspecialchars($order['name']); ?></td>
                                <td>$<?php echo number_format($order['total_price'], 2); ?></td>
                                <td><span class="badge bg-primary"><?php echo ucfirst($order['status']); ?></span></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <h3>Low Stock Products</h3>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Stock</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $low_stock = $conn->query("SELECT * FROM product WHERE qtstock <= 10 ORDER BY qtstock ASC LIMIT 5");
                        while ($product = $low_stock->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                <td><span class="badge bg-warning"><?php echo $product['qtstock']; ?></span></td>
                                <td>$<?php echo number_format($product['price'], 2); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <h3>Quick Actions</h3>
        <div class="btn-group" role="group">
            <a href="manage-products.php" class="btn btn-primary">Manage Products</a>
            <a href="manage-orders.php" class="btn btn-primary">Manage Orders</a>
            <a href="manage-customers.php" class="btn btn-primary">Manage Customers</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
