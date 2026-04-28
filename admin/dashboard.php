<?php
require '../config/db.php';

// Check if user is logged in and is admin (MUST BE BEFORE ANY OUTPUT)
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../login.php?redirect=admin/dashboard.php");
    exit;
}

require '../includes/header.php';
?>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 style="font-family: 'Bebas Neue', serif; letter-spacing: 2px; font-weight: 900; color: #00d4ff; text-shadow: 3px 3px 0px rgba(0, 255, 136, 0.4), 2px 2px 0px rgba(0, 0, 0, 0.8);">STYLE SPARK CONTROL CENTER</h1>
            <a href="logout.php" class="btn btn-outline-danger">Logout</a>
        </div>

        <?php
        // Get statistics
        $total_orders = $conn->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'];
        $total_revenue = $conn->query("SELECT SUM(total_price) as total FROM orders")->fetch_assoc()['total'];
        $total_products = $conn->query("SELECT COUNT(*) as count FROM product")->fetch_assoc()['count'];
        $total_customers = $conn->query("SELECT COUNT(*) as count FROM client")->fetch_assoc()['count'];
        ?>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card" style="background-color: #0f0f0f; border: 2px solid rgba(0, 212, 255, 0.3);">
                    <div class="card-body">
                        <h6 class="card-title" style="color: #00d4ff; font-weight: 900; text-transform: uppercase; letter-spacing: 1px;">Total Orders</h6>
                        <h2 style="color: #00d4ff; font-weight: 900;"><?php echo $total_orders; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card" style="background-color: #0f0f0f; border: 2px solid rgba(76, 175, 80, 0.3);">
                    <div class="card-body">
                        <h6 class="card-title" style="color: #4CAF50; font-weight: 900; text-transform: uppercase; letter-spacing: 1px;">Total Revenue</h6>
                        <h2 style="color: #4CAF50; font-weight: 900;">$<?php echo number_format($total_revenue, 2); ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card" style="background-color: #0f0f0f; border: 2px solid rgba(255, 193, 7, 0.3);">
                    <div class="card-body">
                        <h6 class="card-title" style="color: #FFC107; font-weight: 900; text-transform: uppercase; letter-spacing: 1px;">Total Products</h6>
                        <h2 style="color: #FFC107; font-weight: 900;"><?php echo $total_products; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card" style="background-color: #0f0f0f; border: 2px solid rgba(244, 67, 54, 0.3);">
                    <div class="card-body">
                        <h6 class="card-title" style="color: #F44336; font-weight: 900; text-transform: uppercase; letter-spacing: 1px;">Total Customers</h6>
                        <h2 style="color: #F44336; font-weight: 900;"><?php echo $total_customers; ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <h3 style="color: #00d4ff; font-weight: 900; text-transform: uppercase; letter-spacing: 1px;">Recent Orders</h3>
                <div class="table-responsive" style="background-color: #0f0f0f; border: 1px solid #333; border-radius: 4px;">
                    <table class="table table-sm" style="margin: 0; color: #b0b0b0;">
                        <thead style="background-color: rgba(0, 212, 255, 0.1); border-bottom: 2px solid #00d4ff;">
                            <tr>
                                <th style="color: #00d4ff; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px;">Order ID</th>
                                <th style="color: #00d4ff; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px;">Customer</th>
                                <th style="color: #00d4ff; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px;">Total</th>
                                <th style="color: #00d4ff; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $recent_orders = $conn->query("SELECT o.*, c.name FROM orders o JOIN client c ON o.client_id = c.id ORDER BY o.order_date DESC LIMIT 5");
                            while ($order = $recent_orders->fetch_assoc()): ?>
                                <tr style="border-bottom: 1px solid #333; color: #b0b0b0;">
                                    <td style="color: #fff;">#<?php echo $order['id']; ?></td>
                                    <td style="color: #fff;"><?php echo htmlspecialchars($order['name']); ?></td>
                                    <td style="color: #00d4ff; font-weight: 600;">$<?php echo number_format($order['total_price'], 2); ?></td>
                                    <td><span class="badge" style="background: linear-gradient(135deg, #00d4ff, #0099cc); font-weight: 700; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.5px;"><?php echo ucfirst($order['status']); ?></span></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <h3 style="color: #f44336; font-weight: 900; text-transform: uppercase; letter-spacing: 1px;">Low Stock Products</h3>
                <div class="table-responsive" style="background-color: #0f0f0f; border: 1px solid #333; border-radius: 4px;">
                    <table class="table table-sm" style="margin: 0; color: #b0b0b0;">
                        <thead style="background-color: rgba(244, 67, 54, 0.1); border-bottom: 2px solid #f44336;">
                            <tr>
                                <th style="color: #f44336; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px;">Product</th>
                                <th style="color: #f44336; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px;">Stock</th>
                                <th style="color: #f44336; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px;">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $low_stock = $conn->query("SELECT * FROM product WHERE qtstock <= 10 ORDER BY qtstock ASC LIMIT 5");
                            while ($product = $low_stock->fetch_assoc()): ?>
                                <tr style="border-bottom: 1px solid #333; color: #b0b0b0;">
                                    <td style="color: #fff;"><?php echo htmlspecialchars($product['name']); ?></td>
                                    <td><span class="badge" style="background-color: #f44336; font-weight: 700; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.5px;"><?php echo $product['qtstock']; ?></span></td>
                                    <td style="color: #00d4ff; font-weight: 600;">$<?php echo number_format($product['price'], 2); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <h3 style="color: #00d4ff; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px;">Quick Actions</h3>
            <div class="btn-group" role="group" style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="manage-products.php" class="btn btn-outline-primary" style="text-transform: uppercase; font-weight: 600; letter-spacing: 1px; border-color: #00d4ff; color: #00d4ff;">Manage Products</a>
                <a href="manage-orders.php" class="btn btn-outline-primary" style="text-transform: uppercase; font-weight: 600; letter-spacing: 1px; border-color: #00d4ff; color: #00d4ff;">Manage Orders</a>
                <a href="manage-customers.php" class="btn btn-outline-primary" style="text-transform: uppercase; font-weight: 600; letter-spacing: 1px; border-color: #00d4ff; color: #00d4ff;">Manage Customers</a>
            </div>
        </div>
    </div>

<?php require '../includes/footer.php'; ?>
