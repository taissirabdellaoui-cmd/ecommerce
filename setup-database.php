<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'config/db.php';

echo "<h1>Database Setup Helper</h1>";

// Drop existing orders table and recreate
$sql_drop = "DROP TABLE IF EXISTS orders";
if ($conn->query($sql_drop)) {
    echo "<p style='color: green;'>✓ Dropped old orders table (if existed)</p>";
} else {
    echo "<p style='color: red;'>✗ Error dropping orders table: " . $conn->error . "</p>";
}

// Create new orders table with correct structure
$sql_create = "CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `order_date` date DEFAULT NULL,
  `status` enum('pending','confirmed','shipped','delivered','cancelled') DEFAULT 'pending',
  `total_price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

if ($conn->query($sql_create)) {
    echo "<p style='color: green;'>✓ Created new orders table</p>";
} else {
    echo "<p style='color: red;'>✗ Error creating orders table: " . $conn->error . "</p>";
}

// Verify structure
$result = $conn->query("DESCRIBE orders");
echo "<h2>Orders Table Structure</h2>";
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['Field']) . "</td>";
    echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
    echo "<td>" . htmlspecialchars($row['Null']) . "</td>";
    echo "<td>" . htmlspecialchars($row['Key']) . "</td>";
    echo "<td>" . htmlspecialchars($row['Default'] ?? 'N/A') . "</td>";
    echo "<td>" . htmlspecialchars($row['Extra']) . "</td>";
    echo "</tr>";
}
echo "</table>";

// Test INSERT
echo "<h2>Test Insert</h2>";
$test_date = date('Y-m-d');
$test_sql = "INSERT INTO orders (client_id, order_date, status, total_price) VALUES (1, '$test_date', 'pending', 99.99)";
if ($conn->query($test_sql)) {
    echo "<p style='color: green;'>✓ Test insert successful! Order ID: " . $conn->insert_id . "</p>";
    // Cleanup
    $conn->query("DELETE FROM orders WHERE id > 0");
    echo "<p>Test data cleaned up</p>";
} else {
    echo "<p style='color: red;'>✗ Test insert failed: " . $conn->error . "</p>";
}

echo "<h2>Setup Complete</h2>";
echo "<p><a href='checkout.php'>Try checkout again</a></p>";
?>
