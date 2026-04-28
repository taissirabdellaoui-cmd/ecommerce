<?php
// Quick branding update script
require 'config/db.php';

echo "<div style='background: #1a1a1a; color: #00d4ff; padding: 20px; font-family: Arial; text-align: center;'>";
echo "<h1 style='font-size: 3em; font-weight: 900; letter-spacing: -2px; margin-bottom: 10px;'>⚡ STYLE SPARK ⚡</h1>";
echo "<p style='font-size: 1.2em; letter-spacing: 2px; color: #b0b0b0; margin-bottom: 20px;'>PREMIUM STREETWEAR COLLECTION</p>";

// Check database updates
echo "<div style='background: #0f0f0f; padding: 15px; margin: 20px 0; border-left: 3px solid #00d4ff;'>";

$categories = $conn->query("SELECT * FROM categories");
echo "<h3 style='color: #00d4ff; margin-top: 20px;'>📂 CATEGORIES:</h3>";
while ($cat = $categories->fetch_assoc()) {
    echo "<p style='margin: 5px 0; color: #fff;'>• " . htmlspecialchars($cat['name']) . "</p>";
}

$products = $conn->query("SELECT * FROM product");
echo "<h3 style='color: #ff006e; margin-top: 20px;'>🔥 PRODUCTS:</h3>";
while ($prod = $products->fetch_assoc()) {
    echo "<p style='margin: 5px 0; color: #b0b0b0;'>• " . htmlspecialchars($prod['name']) . " - \$" . $prod['price'] . "</p>";
}

echo "</div>";

echo "<h2 style='color: #00d4ff; margin-top: 30px;'>✨ STYLE SPARK IS LIVE ✨</h2>";
echo "<p style='color: #b0b0b0;'><a href='index.php' style='color: #00d4ff; text-decoration: none; font-weight: bold;'>→ VISIT THE SHOP</a></p>";
echo "</div>";
?>
