<?php
require 'config/db.php';

// Check if orders table exists and has correct structure
$result = $conn->query("DESCRIBE orders");
if (!$result) {
    echo "Error: orders table doesn't exist or can't be described.<br>";
    echo "Error: " . $conn->error;
    exit;
}

// Get all columns
$columns = [];
while ($row = $result->fetch_assoc()) {
    $columns[$row['Field']] = $row;
}

// Expected columns
$expected = ['id', 'client_id', 'order_date', 'status', 'total_price'];
$actual = array_keys($columns);

echo "<h2>Table Structure Check</h2>";
echo "<p><strong>Expected Columns:</strong> " . implode(", ", $expected) . "</p>";
echo "<p><strong>Actual Columns:</strong> " . implode(", ", $actual) . "</p>";

if (count($actual) !== count($expected)) {
    echo "<p style='color: red;'><strong>WARNING:</strong> Column count mismatch! " . count($actual) . " actual vs " . count($expected) . " expected</p>";
    echo "<p>Extra columns: " . implode(", ", array_diff($actual, $expected)) . "</p>";
    echo "<p>Missing columns: " . implode(", ", array_diff($expected, $actual)) . "</p>";
} else {
    echo "<p style='color: green;'><strong>OK:</strong> Column counts match</p>";
}

// Test INSERT
echo "<h2>Test INSERT</h2>";
$test_date = date('Y-m-d');
$test_sql = "INSERT INTO orders (client_id, order_date, status, total_price) VALUES (1, '$test_date', 'pending', 99.99)";
echo "<p><strong>SQL:</strong> " . htmlspecialchars($test_sql) . "</p>";

if ($conn->query($test_sql)) {
    echo "<p style='color: green;'><strong>SUCCESS:</strong> Test insert worked</p>";
    $test_id = $conn->insert_id;
    echo "<p>New order ID: $test_id</p>";
    
    // Clean up test data
    $conn->query("DELETE FROM orders WHERE id = $test_id");
    echo "<p>Test data deleted</p>";
} else {
    echo "<p style='color: red;'><strong>FAILED:</strong> " . $conn->error . "</p>";
}
?>
