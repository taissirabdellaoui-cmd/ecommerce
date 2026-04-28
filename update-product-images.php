<?php
require 'config/db.php';

// Map product names to image filenames
$image_mapping = [
    'Premium Oversized Logo Hoodie' => 'Classic Oversized Logo Hoodie.jpg',
    'Vintage Graphic Tee' => 'VintageGraphicTee-Street_Style.jpg',
    'Black Cargo Pants' => 'BlackCargoJeans.jpg',
    'Classic Black Shorts' => 'BlackShorts.jpg',
    'Premium Street Sneakers' => 'streetShose.jpg'
];

echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <link rel='preconnect' href='https://fonts.googleapis.com'>
    <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
    <link href='https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@400;600;700;900&display=swap' rel='stylesheet'>
    <style>
        body { background: #1a1a1a; color: #00d4ff; font-family: 'Poppins', sans-serif; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        h1 { font-family: 'Bebas Neue', serif; font-size: 2.5em; text-shadow: 2px 2px 0px rgba(0,255,136,0.4); margin-bottom: 30px; }
        .status { background: #0f0f0f; border: 2px solid #00d4ff; padding: 15px; margin: 10px 0; border-radius: 4px; }
        .success { border-color: #00ff88; color: #00ff88; }
        .error { border-color: #ff4444; color: #ff4444; }
        .info { border-color: #00d4ff; color: #00d4ff; }
    </style>
</head>
<body>
<div class='container'>
    <h1>🎨 UPDATING PRODUCT IMAGES</h1>";

$updated_count = 0;
$skipped = [];

foreach ($image_mapping as $product_name => $image_file) {
    // Check if image file exists
    if (!file_exists("images/$image_file")) {
        echo "<div class='status error'>❌ Image not found: $image_file</div>";
        $skipped[] = $product_name;
        continue;
    }
    
    // Update product with new image
    $escaped_name = $conn->real_escape_string($product_name);
    $escaped_image = $conn->real_escape_string($image_file);
    
    $query = "UPDATE product SET image = '$escaped_image' WHERE name = '$escaped_name'";
    
    if ($conn->query($query)) {
        if ($conn->affected_rows > 0) {
            echo "<div class='status success'>✅ Updated: $product_name → $image_file</div>";
            $updated_count++;
        } else {
            echo "<div class='status error'>⚠️ Product not found: $product_name</div>";
            $skipped[] = $product_name;
        }
    } else {
        echo "<div class='status error'>❌ Error: " . $conn->error . "</div>";
        $skipped[] = $product_name;
    }
}

echo "<hr style='border: 1px solid #00d4ff; margin: 20px 0;'>";
echo "<div class='status info'>";
echo "✨ Summary: <br>";
echo "✅ Updated: $updated_count products<br>";
echo "⚠️ Skipped: " . count($skipped) . " products";
if (count($skipped) > 0) {
    echo "<br><br>Skipped products:<br>";
    foreach ($skipped as $p) {
        echo "• $p<br>";
    }
}
echo "</div>";

// Show all products with their current images
echo "<hr style='border: 1px solid #00d4ff; margin: 20px 0;'>";
echo "<h2 style='font-family: \"Bebas Neue\", serif; color: #00ff88;'>📦 Current Products in Database</h2>";

$all_products = $conn->query("SELECT id, name, image FROM product ORDER BY id");
if ($all_products) {
    echo "<table style='width: 100%; border-collapse: collapse; margin-top: 20px;'>";
    echo "<tr style='background: #0f0f0f; border-bottom: 2px solid #00d4ff;'>";
    echo "<th style='text-align: left; padding: 10px; color: #00d4ff;'>ID</th>";
    echo "<th style='text-align: left; padding: 10px; color: #00d4ff;'>Product Name</th>";
    echo "<th style='text-align: left; padding: 10px; color: #00d4ff;'>Image File</th>";
    echo "</tr>";
    
    while ($p = $all_products->fetch_assoc()) {
        echo "<tr style='border-bottom: 1px solid #333;'>";
        echo "<td style='padding: 10px; color: #b0b0b0;'>" . $p['id'] . "</td>";
        echo "<td style='padding: 10px; color: #fff;'>" . htmlspecialchars($p['name']) . "</td>";
        echo "<td style='padding: 10px; color: #00ff88;'>" . htmlspecialchars($p['image']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

echo "</div></body></html>";
?>
