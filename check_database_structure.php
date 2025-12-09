<?php
/**
 * Check Database Structure - Orders Table
 * Untuk melihat struktur tabel dan constraint
 */

session_start();
require_once 'config/database.php';

echo "<h1>Database Structure Check</h1>";

// Check table structure
echo "<h2>1. Orders Table Structure:</h2>";
$structure = query("DESCRIBE orders");
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
while($row = mysqli_fetch_assoc($structure)) {
    echo "<tr>";
    echo "<td>" . $row['Field'] . "</td>";
    echo "<td>" . $row['Type'] . "</td>";
    echo "<td>" . $row['Null'] . "</td>";
    echo "<td>" . $row['Key'] . "</td>";
    echo "<td>" . ($row['Default'] ?? 'NULL') . "</td>";
    echo "<td>" . $row['Extra'] . "</td>";
    echo "</tr>";
}
echo "</table>";

// Check for triggers
echo "<h2>2. Triggers on Orders Table:</h2>";
$triggers = query("SHOW TRIGGERS WHERE `Table` = 'orders'");
if(mysqli_num_rows($triggers) > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Trigger</th><th>Event</th><th>Timing</th><th>Statement</th></tr>";
    while($row = mysqli_fetch_assoc($triggers)) {
        echo "<tr>";
        echo "<td>" . $row['Trigger'] . "</td>";
        echo "<td>" . $row['Event'] . "</td>";
        echo "<td>" . $row['Timing'] . "</td>";
        echo "<td><pre>" . htmlspecialchars($row['Statement']) . "</pre></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No triggers found.</p>";
}

// Check current order #66 status
echo "<h2>3. Current Status of Order #66:</h2>";
$order = mysqli_fetch_assoc(query("SELECT * FROM orders WHERE order_id = 66"));
echo "<pre>";
print_r($order);
echo "</pre>";

// Check order_history for order #66
echo "<h2>4. Order History for Order #66:</h2>";
$history = query("SELECT * FROM order_history WHERE order_id = 66 ORDER BY created_at DESC");
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>ID</th><th>Status</th><th>Status Detail</th><th>Notes</th><th>Created At</th></tr>";
while($row = mysqli_fetch_assoc($history)) {
    echo "<tr>";
    echo "<td>" . $row['history_id'] . "</td>";
    echo "<td>" . $row['status'] . "</td>";
    echo "<td>" . $row['status_detail'] . "</td>";
    echo "<td>" . $row['notes'] . "</td>";
    echo "<td>" . $row['created_at'] . "</td>";
    echo "</tr>";
}
echo "</table>";
?>
