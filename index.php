<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecommerce App</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">EcommerceApp</a>
        <div class="navbar-nav">
            <a class="nav-link" href="pages/products.php">Products</a>
            <a class="nav-link" href="pages/clients.php">Clients</a>
            <a class="nav-link" href="pages/orders.php">Orders</a>
        </div>
    </div>
</nav>

<!-- Main content -->
<div class="container mt-5">
    <h1>Welcome to EcommerceApp</h1>
    <p>Manage your products, clients and orders.</p>
    <form action="pages/products.php" method="get">
        <button type="submit" class="btn btn-primary">View Products</button>
</div>

<!-- Bootstrap JS CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
