<?php
require 'config/db.php';

echo "<h2 style='color: #00d4ff; font-weight: 900;'>UPDATING DATABASE TO STREETWEAR STYLE...</h2>";

// Update categories to streetwear
$categories = [
    1 => ['Hoodies & Hoody', 'Premium streetwear hoodies and pullovers'],
    2 => ['Graphic Tees', 'Bold graphic printed t-shirts and vintage tees'],
    3 => ['Cargo Pants', 'Utility and cargo style pants with multiple pockets'],
    4 => ['Shorts', 'Street shorts and bermuda styles'],
    5 => ['Sneakers', 'Premium streetwear sneakers and kicks']
];

foreach ($categories as $id => $data) {
    $name = $conn->real_escape_string($data[0]);
    $desc = $conn->real_escape_string($data[1]);
    $sql = "UPDATE categories SET name = '$name', description = '$desc' WHERE id = $id";
    if ($conn->query($sql)) {
        echo "✓ Category $id updated: " . $data[0] . "<br>";
    } else {
        echo "✗ Error updating category $id<br>";
    }
}

// Update products to streetwear
$products = [
    1 => ['Classic Oversized Logo Hoodie', 'Premium heavy-weight hoodie with embroidered logo. Perfect for layering.', 89.99, 1],
    2 => ['Vintage Graphic Tee - Street Style', 'Distressed vintage print graphic tee. Limited edition print. 100% cotton.', 39.99, 2],
    3 => ['Black Cargo Pants', 'Functional cargo pants with tactical pockets. Adjustable straps. Premium fabric.', 79.99, 3],
    4 => ['Classic Black Shorts', 'Timeless black shorts. Perfect for the streets or beach. Comfortable fit.', 44.99, 4],
    5 => ['Premium Street Sneakers', 'High-quality street sneakers. Versatile design. Comfortable all day wear.', 129.99, 5]
];

foreach ($products as $id => $data) {
    $name = $conn->real_escape_string($data[0]);
    $desc = $conn->real_escape_string($data[1]);
    $price = $data[2];
    $cat_id = $data[3];
    $sql = "UPDATE product SET name = '$name', description = '$desc', price = $price, category_id = $cat_id WHERE id = $id";
    if ($conn->query($sql)) {
        echo "✓ Product $id updated: " . $data[0] . "<br>";
    } else {
        echo "✗ Error updating product $id<br>";
    }
}

echo "<br><strong style='color: #ff006e;'>✨ DATABASE UPDATED TO STREETWEAR STYLE! ✨</strong><br>";
echo "<small><a href='index.php'>Go to Homepage</a> | You can delete this file (streetwear-update.php) now</small>";
?>
