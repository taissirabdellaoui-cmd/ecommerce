<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
$cart_count = count($_SESSION['cart']);

$is_admin_page = strpos($_SERVER['PHP_SELF'], '/admin/') !== false;
$base_url = $is_admin_page ? '../' : './';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Style Spark - Premium Streetwear</title>
    <link rel="icon" type="image/x-icon" href="<?php echo $base_url; ?>images/StyleSpark.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@400;600;700;900&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1a1a1a;
            --secondary: #0f0f0f;
            --accent: #00d4ff;
            --accent-alt: #00ff88;
            --text-light: #ffffff;
            --text-muted: #b0b0b0;
        }
        
        body {
            background-color: var(--primary);
            color: var(--text-light);
            font-family: 'Poppins', sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        h1, h2, h3, .display-3 {
            font-family: 'Bebas Neue', serif;
            letter-spacing: 2px;
            font-weight: 900;
            color: #00d4ff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8), 0 0 20px rgba(0, 212, 255, 0.3);
        }
        
        main { flex: 1; }
        
        .navbar {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--primary) 100%) !important;
            border-bottom: 3px solid var(--accent);
            box-shadow: 0 2px 15px rgba(0, 212, 255, 0.2);
        }
        
        .navbar-brand {
            font-family: 'Bebas Neue', serif;
            font-weight: 900;
            font-size: 1.8rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            background: linear-gradient(135deg, var(--accent), var(--accent-alt));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .navbar-brand img {
            height: 50px;
            margin-right: 12px;
            filter: drop-shadow(0 0 8px rgba(0, 212, 255, 0.3));
        }
        
        .nav-link {
            text-transform: uppercase;
            font-weight: 600;
            font-size: 0.85rem;
            letter-spacing: 1px;
            color: var(--text-light) !important;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .nav-link:hover {
            color: var(--accent) !important;
            text-shadow: 0 0 10px rgba(0, 212, 255, 0.5);
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--accent);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .nav-link:hover::after {
            width: 100%;
        }
        
        .badge {
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        
        .product-card {
            background-color: var(--secondary);
            border: 2px solid var(--accent);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 0 15px rgba(0, 212, 255, 0.15);
        }
        
        .product-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 255, 136, 0.3), transparent);
            transition: left 0.5s ease;
        }
        
        .product-card:hover::before {
            left: 100%;
        }
        
        .product-card:hover {
            border-color: var(--accent-alt);
            box-shadow: 0 0 30px rgba(0, 212, 255, 0.4), 0 0 20px rgba(0, 255, 136, 0.2);
            transform: translateY(-10px) scale(1.02);
        }
        
        .product-img {
            transition: transform 0.3s ease;
        }
        
        .product-card:hover .product-img {
            transform: scale(1.1);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--accent), #0099cc);
            border: none;
            font-family: 'Bebas Neue', serif;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            box-shadow: 0 0 30px rgba(0, 212, 255, 0.6), 0 0 50px rgba(0, 255, 136, 0.3);
            transform: scale(1.05);
        }
        
        .btn-outline-primary {
            color: var(--accent);
            border: 2px solid var(--accent);
            font-family: 'Bebas Neue', serif;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .btn-outline-primary:hover {
            background-color: var(--accent);
            border-color: var(--accent);
            color: #000;
            box-shadow: 0 0 20px rgba(0, 212, 255, 0.5);
        }
        
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"],
        textarea,
        select {
            background-color: var(--secondary) !important;
            border: 2px solid #333 !important;
            color: var(--text-light) !important;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
        }
        
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="number"]:focus,
        textarea:focus,
        select:focus {
            background-color: var(--secondary) !important;
            border-color: var(--accent) !important;
            box-shadow: 0 0 20px rgba(0, 212, 255, 0.3) !important;
            color: var(--text-light) !important;
        }
        
        label {
            font-family: 'Bebas Neue', serif;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--accent);
            font-weight: 700;
        }
        
        .badge-cart {
            font-size: 0.75rem;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(0, 212, 255, 0.7); }
            50% { box-shadow: 0 0 0 10px rgba(0, 212, 255, 0); }
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo $base_url; ?>index.php" style="display: flex; align-items: center; gap: 12px;">
            <img src="<?php echo $base_url; ?>images/StyleSpark.png" alt="Style Spark Logo" style="height: 50px; filter: drop-shadow(0 0 8px rgba(0, 212, 255, 0.3));">
            <span>STYLE SPARK</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if (!$is_admin_page): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_url; ?>index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_url; ?>index.php#products">Products</a>
                    </li>
                <?php endif; ?>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $is_admin_page ? 'dashboard.php' : $base_url . 'admin/dashboard.php'; ?>">
                                <i class="bi bi-speedometer2"></i> Admin Dashboard
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $base_url; ?>user-dashboard.php">My Account</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_url; ?>logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_url; ?>login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_url; ?>register.php">Register</a>
                    </li>
                <?php endif; ?>
                <?php if (!$is_admin_page): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_url; ?>cart.php">
                            <i class="bi bi-cart3"></i> Cart
                            <?php if ($cart_count > 0): ?>
                                <span class="badge bg-danger badge-cart ms-1"><?php echo $cart_count; ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endif; ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<main class="py-4">
