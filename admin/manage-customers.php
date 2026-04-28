<?php
require '../config/db.php';

// Admin check (MUST BE BEFORE ANY OUTPUT)
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../login.php?redirect=admin/dashboard.php");
    exit;
}

require '../includes/header.php';

$message = '';

// Handle customer deletion
if (isset($_GET['delete'])) {
    $customer_id = (int)$_GET['delete'];
    if ($conn->query("DELETE FROM client WHERE id = $customer_id")) {
        $message = '<div class="alert alert-success">Customer deleted successfully.</div>';
    }
}

$customers = $conn->query("SELECT c.*, COUNT(o.id) as total_orders FROM client c LEFT JOIN orders o ON c.id = o.client_id GROUP BY c.id ORDER BY c.id DESC");
?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Customers</h1>
    </div>

    <?php echo $message; ?>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Total Orders</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($customer = $customers->fetch_assoc()): ?>
                    <tr>
                        <td>#<?php echo $customer['id']; ?></td>
                        <td><?php echo htmlspecialchars($customer['name']); ?></td>
                        <td><?php echo htmlspecialchars($customer['email']); ?></td>
                        <td><?php echo htmlspecialchars(substr($customer['adress'] ?? 'N/A', 0, 50)); ?></td>
                        <td><?php echo $customer['total_orders']; ?></td>
                        <td>
                            <a href="manage-customers.php?delete=<?php echo $customer['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this customer?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <a href="dashboard.php" class="btn btn-outline-secondary mt-3">Back to Dashboard</a>
</div>

<?php require '../includes/footer.php'; ?>
