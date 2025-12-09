<?php
/**
 * Run Email Verification Migration
 * Creates email_verifications table and adds columns to users table
 */

require_once '../config/database.php';

echo "========================================\n";
echo "EMAIL VERIFICATION MIGRATION\n";
echo "========================================\n\n";

// Read SQL file and remove BOM
$sql_file = 'create_email_verification_table.sql';
$sql = file_get_contents($sql_file);

if (!$sql) {
    echo "❌ Error: Could not read SQL file\n";
    exit(1);
}

// Remove BOM if present
$sql = preg_replace('/^\xEF\xBB\xBF/', '', $sql);

// Remove comments and empty lines
$lines = explode("\n", $sql);
$filtered_lines = array_filter($lines, function($line) {
    $line = trim($line);
    return !empty($line) && strpos($line, '--') !== 0;
});

$sql = implode("\n", $filtered_lines);

// Split SQL into individual statements
$statements = array_filter(array_map('trim', explode(';', $sql)));

$success_count = 0;
$error_count = 0;

foreach ($statements as $statement) {
    if (empty($statement)) continue;
    
    echo "Executing: " . substr(str_replace(["\n", "\r"], ' ', $statement), 0, 80) . "...\n";
    
    if (query($statement)) {
        echo "✅ Success\n\n";
        $success_count++;
    } else {
        echo "❌ Error: " . mysqli_error($GLOBALS['conn']) . "\n\n";
        $error_count++;
    }
}

echo "========================================\n";
echo "MIGRATION COMPLETED\n";
echo "✅ Success: $success_count\n";
echo "❌ Errors: $error_count\n";
echo "========================================\n";
?>
