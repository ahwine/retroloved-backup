<?php
require_once 'config/database.php';

echo "========================================\n";
echo "EMAIL VERIFICATIONS TABLE\n";
echo "========================================\n\n";

// Check all data in email_verifications
$result = query("SELECT * FROM email_verifications ORDER BY created_at DESC LIMIT 5");
$count = mysqli_num_rows($result);

if ($count > 0) {
    echo "Found $count verification(s):\n\n";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "Email: " . $row['email'] . "\n";
        echo "Username: " . $row['username'] . "\n";
        echo "Full Name: " . $row['full_name'] . "\n";
        echo "Code: " . $row['verification_code'] . "\n";
        echo "Expires: " . $row['expires_at'] . "\n";
        echo "Created: " . $row['created_at'] . "\n";
        
        // Calculate time left
        $now = new DateTime();
        $expires = new DateTime($row['expires_at']);
        $diff = $now->diff($expires);
        
        if ($now < $expires) {
            echo "Status: VALID (expires in " . $diff->i . " minutes)\n";
        } else {
            echo "Status: EXPIRED\n";
        }
        echo "----------------------------------------\n";
    }
} else {
    echo "No verifications found in database\n";
}

echo "\n========================================\n";
?>