<?php
/**
 * Migration Runner - Update Old Notification Types
 * Run this file ONCE to update all old notifications to new types
 */

require_once '../config/database.php';

echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Notification Migration</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .info { color: blue; }
        h1 { color: #D97706; }
        .result { background: #f5f5f5; padding: 15px; border-radius: 8px; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>🔄 Notification Type Migration</h1>
    <p>Updating old notification types to new synchronized types...</p>
    <hr>
";

// Count before migration
echo "<div class='result'>";
echo "<h3>📊 BEFORE Migration:</h3>";
$before = query("SELECT type, COUNT(*) as count FROM notifications GROUP BY type");
if(mysqli_num_rows($before) > 0) {
    while ($row = mysqli_fetch_assoc($before)) {
        echo "<p>Type: <strong>" . htmlspecialchars($row['type']) . "</strong> - Count: " . $row['count'] . "</p>";
    }
} else {
    echo "<p>No notifications found in database.</p>";
}
echo "</div>";

echo "<h3>🔧 Running Migrations...</h3>";

// Migration 1: Update 'order' with 'diproses' or 'dikonfirmasi' → order_confirmed
$result1 = query("UPDATE notifications 
    SET type = 'order_confirmed' 
    WHERE type = 'order' 
    AND (message LIKE '%diproses%' OR message LIKE '%dikonfirmasi%' OR title LIKE '%Diproses%' OR title LIKE '%Dikonfirmasi%')");
$affected1 = mysqli_affected_rows($conn);
echo "<p class='info'>✓ Updated 'order' (processing) → 'order_confirmed': <strong>{$affected1}</strong> rows</p>";

// Migration 2: Update 'shipping' → order_shipped
$result2 = query("UPDATE notifications SET type = 'order_shipped' WHERE type = 'shipping'");
$affected2 = mysqli_affected_rows($conn);
echo "<p class='info'>✓ Updated 'shipping' → 'order_shipped': <strong>{$affected2}</strong> rows</p>";

// Migration 3: Update 'order' with 'dikirim' → order_shipped
$result3 = query("UPDATE notifications 
    SET type = 'order_shipped' 
    WHERE type = 'order' 
    AND (message LIKE '%dikirim%' OR title LIKE '%Dikirim%')");
$affected3 = mysqli_affected_rows($conn);
echo "<p class='info'>✓ Updated 'order' (shipped) → 'order_shipped': <strong>{$affected3}</strong> rows</p>";

// Migration 4: Update 'order' with 'sampai' → order_delivered
$result4 = query("UPDATE notifications 
    SET type = 'order_delivered' 
    WHERE type = 'order' 
    AND (message LIKE '%sampai%' OR message LIKE '%delivered%' OR title LIKE '%Sampai%')");
$affected4 = mysqli_affected_rows($conn);
echo "<p class='info'>✓ Updated 'order' (delivered) → 'order_delivered': <strong>{$affected4}</strong> rows</p>";

// Migration 5: Update 'order' with 'dibatalkan' → order_cancelled
$result5 = query("UPDATE notifications 
    SET type = 'order_cancelled' 
    WHERE type = 'order' 
    AND (message LIKE '%dibatalkan%' OR message LIKE '%cancelled%' OR title LIKE '%Dibatalkan%')");
$affected5 = mysqli_affected_rows($conn);
echo "<p class='info'>✓ Updated 'order' (cancelled) → 'order_cancelled': <strong>{$affected5}</strong> rows</p>";

// Migration 6: Update 'order' with 'menunggu' → order_pending
$result6 = query("UPDATE notifications 
    SET type = 'order_pending' 
    WHERE type = 'order' 
    AND (message LIKE '%menunggu%' OR message LIKE '%pending%' OR title LIKE '%Menunggu%')");
$affected6 = mysqli_affected_rows($conn);
echo "<p class='info'>✓ Updated 'order' (pending) → 'order_pending': <strong>{$affected6}</strong> rows</p>";

// Migration 7: Update remaining 'order' → order_pending (default)
$result7 = query("UPDATE notifications SET type = 'order_pending' WHERE type = 'order'");
$affected7 = mysqli_affected_rows($conn);
echo "<p class='info'>✓ Updated remaining 'order' → 'order_pending': <strong>{$affected7}</strong> rows</p>";

$total_affected = $affected1 + $affected2 + $affected3 + $affected4 + $affected5 + $affected6 + $affected7;

echo "<div class='result'>";
echo "<h3 class='success'>✅ Migration Complete!</h3>";
echo "<p>Total notifications updated: <strong>{$total_affected}</strong></p>";
echo "</div>";

// Count after migration
echo "<div class='result'>";
echo "<h3>📊 AFTER Migration:</h3>";
$after = query("SELECT type, COUNT(*) as count FROM notifications GROUP BY type ORDER BY type");
if(mysqli_num_rows($after) > 0) {
    while ($row = mysqli_fetch_assoc($after)) {
        echo "<p>Type: <strong>" . htmlspecialchars($row['type']) . "</strong> - Count: " . $row['count'] . "</p>";
    }
} else {
    echo "<p>No notifications found in database.</p>";
}
echo "</div>";

echo "<hr>";
echo "<h3 class='success'>🎉 All Done!</h3>";
echo "<p>All old notification types have been updated to the new synchronized types.</p>";
echo "<p><a href='../customer/notifications.php' style='background: #D97706; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; display: inline-block; margin-top: 10px;'>Go to Notifications Page →</a></p>";

echo "</body></html>";
?>