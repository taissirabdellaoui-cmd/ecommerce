<?php
require 'config/db.php';

// Check the structure of the orders table
$result = $conn->query("DESCRIBE orders");

echo "<!DOCTYPE html>
<html>
<head>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
</head>
<body>
<div class='container mt-5'>
    <h1>Orders Table Structure</h1>
    <table class='table table-hover'>
        <thead class='table-light'>
            <tr>
                <th>Field</th>
                <th>Type</th>
                <th>Null</th>
                <th>Key</th>
                <th>Default</th>
                <th>Extra</th>
            </tr>
        </thead>
        <tbody>";

if ($result) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['Field']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Key']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Default']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Extra']) . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6' class='text-danger'>Error: " . $conn->error . "</td></tr>";
}

echo "</tbody>
    </table>
    <p><a href='index.php' class='btn btn-primary'>Back to Home</a></p>
</div>
</body>
</html>";
?>
