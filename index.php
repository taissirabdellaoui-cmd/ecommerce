<?php
require 'config/db.php';
require 'includes/header.php';

// Get all categories
$categories_result = $conn->query("SELECT * FROM categories");
$categories = $categories_result->fetch_all(MYSQLI_ASSOC);

// Get filter
$category_filter = isset($_GET['category']) ? (int)$_GET['category'] : 0;

// Get products
$query = "SELECT p.*, c.name as category_name FROM product p LEFT JOIN categories c ON p.category_id = c.id";
if ($category_filter) {
    $query .= " WHERE p.category_id = $category_filter";
}
$query .= " ORDER BY p.id DESC";
$products_result = $conn->query($query);
$products = $products_result->fetch_all(MYSQLI_ASSOC);
?>

<div class="container">
    <!-- Hero Section -->
    <div class="row mb-5 mt-4">
        <div class="col-12">
            <div class="p-5 rounded position-relative" style="background: linear-gradient(135deg, rgba(0, 212, 255, 0.08), rgba(0, 255, 136, 0.08)); border: 3px solid #00d4ff; overflow: hidden;">
                <!-- Graffiti Background Elements -->
                <div style="position: absolute; top: -5%; right: -5%; width: 500px; height: 500px; background: radial-gradient(circle, rgba(0, 212, 255, 0.05), transparent); border-radius: 50%; pointer-events: none;"></div>
                <div style="position: absolute; bottom: -10%; left: 0; width: 300px; height: 300px; background: radial-gradient(circle, rgba(0, 255, 136, 0.05), transparent); border-radius: 50%; pointer-events: none;"></div>
                
                <div style="position: relative; z-index: 2;">
                    <h1 class="display-2 fw-900" style="font-family: 'Bebas Neue', serif; letter-spacing: 3px; color: #00d4ff; text-shadow: 3px 3px 0px rgba(0, 255, 136, 0.5), 6px 6px 0px rgba(0, 0, 0, 0.8);">
                        STYLE SPARK
                    </h1>
                    <p class="fs-4 fw-bold text-uppercase" style="font-family: 'Poppins', sans-serif; color: #00ff88; letter-spacing: 2px; text-shadow: 2px 2px 0px rgba(0, 212, 255, 0.3);">
                        Premium Streetwear Collections
                    </p>
                    <p style="font-family: 'Poppins', sans-serif; color: #b0b0b0; font-size: 1.1rem; font-weight: 500;">Express yourself. Define your style. Own the streets.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sidebar - Categories -->
        <div class="col-md-3 mb-4">
            <h5 class="mb-3" style="font-family: 'Bebas Neue', serif; text-transform: uppercase; font-weight: 900; letter-spacing: 2px; color: #00d4ff; text-shadow: 2px 2px 0px rgba(0, 255, 136, 0.4);">Categories</h5>
            <div class="list-group" style="background-color: transparent;">
                <a href="index.php" class="list-group-item list-group-item-action <?php echo $category_filter == 0 ? 'active' : ''; ?>" style="background-color: <?php echo $category_filter == 0 ? 'rgba(0, 212, 255, 0.2)' : '#1a1a1a'; ?>; border: 1px solid #333; color: #fff; text-transform: uppercase; font-weight: 600; font-size: 0.9rem; letter-spacing: 0.5px; transition: all 0.3s;">
                    All Products
                </a>
                <?php foreach ($categories as $cat): ?>
                    <a href="index.php?category=<?php echo $cat['id']; ?>" 
                       class="list-group-item list-group-item-action <?php echo $category_filter == $cat['id'] ? 'active' : ''; ?>" style="background-color: <?php echo $category_filter == $cat['id'] ? 'rgba(0, 212, 255, 0.2)' : '#1a1a1a'; ?>; border: 1px solid #333; color: #fff; text-transform: uppercase; font-weight: 600; font-size: 0.9rem; letter-spacing: 0.5px; transition: all 0.3s;">
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-md-9">
            <h3 id="products" class="mb-4">
                <?php echo $category_filter ? 'Category: ' . $categories[$category_filter - 1]['name'] : 'All Products'; ?>
            </h3>
            
            <?php if (count($products) > 0): ?>
                <div class="row g-4">
                    <?php foreach ($products as $product): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card product-card h-100 position-relative overflow-hidden" style="background-color: #0f0f0f; border: 2px solid #00d4ff; box-shadow: 0 0 20px rgba(0, 212, 255, 0.2); transition: all 0.3s;">
                                <!-- Product Image -->
                                <div style="position: relative; height: 250px; background: linear-gradient(135deg, rgba(0, 212, 255, 0.1), rgba(0, 255, 136, 0.1)); overflow: hidden; display: flex; align-items: center; justify-content: center;">
                                    <img src="images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;" class="product-img" onerror="this.src='https://via.placeholder.com/250?text=<?php echo urlencode($product['name']); ?>'">
                                    <div style="position: absolute; top: 10px; right: 10px; background: linear-gradient(135deg, #00ff88, #00d4ff); color: #000; padding: 6px 12px; border-radius: 4px; font-family: 'Bebas Neue', serif; font-weight: 900; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; z-index: 10;">
                                        <?php echo $product['qtstock']; ?> left
                                    </div>
                                </div>
                                
                                <div style="position: absolute; top: -2px; right: -2px; width: 100px; height: 100px; background: radial-gradient(circle, rgba(0, 255, 136, 0.15), transparent); pointer-events: none;"></div>
                                
                                <div class="card-body position-relative" style="z-index: 2; padding: 20px;">
                                    <h5 class="card-title" style="font-family: 'Bebas Neue', serif; color: #fff; font-weight: 900; letter-spacing: 1px; font-size: 1.2rem; text-shadow: 2px 2px 0px rgba(0, 212, 255, 0.2); margin-bottom: 10px;"><?php echo htmlspecialchars($product['name']); ?></h5>
                                    <p class="card-text" style="font-family: 'Poppins', sans-serif; color: #00ff88; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; margin-bottom: 10px;"><?php echo htmlspecialchars($product['category_name']); ?></p>
                                    <p class="card-text" style="color: #b0b0b0; font-size: 0.9rem; line-height: 1.4; margin-bottom: 15px;"><?php echo substr(htmlspecialchars($product['description']), 0, 80) . '...'; ?></p>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <span class="h5 mb-0" style="font-family: 'Bebas Neue', serif; color: #00d4ff; font-weight: 900; font-size: 1.5rem; text-shadow: 2px 2px 0px rgba(0, 0, 0, 0.8);">$<?php echo number_format($product['price'], 2); ?></span>
                                    </div>
                                </div>
                                
                                <div class="card-footer" style="background-color: #0f0f0f; border-top: 2px solid #333; padding: 10px;">
                                    <a href="product-detail.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-outline-primary w-100" style="font-family: 'Bebas Neue', serif; color: #00d4ff; border: 2px solid #00d4ff; text-transform: uppercase; font-weight: 700; font-size: 0.8rem; letter-spacing: 1px;">
                                        <i class="bi bi-eye"></i> VIEW DETAILS
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert" style="background-color: rgba(0, 212, 255, 0.1); border: 1px solid #00d4ff; color: #b0b0b0; text-align: center; border-radius: 4px;\">No products found in this category.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>
