<?php
/**
 * Quick Test - Email Verification Feature
 * Run this to verify all components are working
 */

echo "========================================\n";
echo "📧 EMAIL VERIFICATION FEATURE TEST\n";
echo "========================================\n\n";

require_once '../config/database.php';

// Test 1: Check if email_verifications table exists
echo "Test 1: Checking email_verifications table...\n";
$result = query("SHOW TABLES LIKE 'email_verifications'");
if (mysqli_num_rows($result) > 0) {
    echo "   ✅ Table exists\n";
} else {
    echo "   ❌ Table does NOT exist\n";
}

// Test 2: Check table structure
echo "\nTest 2: Checking table structure...\n";
$result = query("DESCRIBE email_verifications");
$columns = [];
while ($row = mysqli_fetch_assoc($result)) {
    $columns[] = $row['Field'];
}
$required_columns = ['verification_id', 'email', 'full_name', 'username', 'password', 'verification_code', 'expires_at', 'created_at'];
$all_exist = true;
foreach ($required_columns as $col) {
    if (in_array($col, $columns)) {
        echo "   ✅ Column '$col' exists\n";
    } else {
        echo "   ❌ Column '$col' missing\n";
        $all_exist = false;
    }
}

// Test 3: Check users table columns
echo "\nTest 3: Checking users table columns...\n";
$result = query("DESCRIBE users");
$user_columns = [];
while ($row = mysqli_fetch_assoc($result)) {
    $user_columns[] = $row['Field'];
}
$new_columns = ['email_verified', 'is_active', 'verified_at'];
foreach ($new_columns as $col) {
    if (in_array($col, $user_columns)) {
        echo "   ✅ Column '$col' exists in users table\n";
    } else {
        echo "   ❌ Column '$col' missing in users table\n";
    }
}

// Test 4: Check if files exist
echo "\nTest 4: Checking modified files...\n";
$files = [
    '../auth/process-auth.php' => 'Backend process file',
    '../assets/js/auth-modal.js' => 'Frontend JavaScript',
    '../assets/css/auth.css' => 'CSS styles'
];

foreach ($files as $file => $desc) {
    if (file_exists($file)) {
        echo "   ✅ $desc exists\n";
    } else {
        echo "   ❌ $desc missing\n";
    }
}

// Test 5: Check for new actions in process-auth.php
echo "\nTest 5: Checking new actions in process-auth.php...\n";
$content = file_get_contents('../auth/process-auth.php');
$actions = ['verify_register_otp', 'resend_register_otp'];
foreach ($actions as $action) {
    if (strpos($content, "'$action'") !== false || strpos($content, "\"$action\"") !== false) {
        echo "   ✅ Action '$action' found\n";
    } else {
        echo "   ❌ Action '$action' not found\n";
    }
}

// Test 6: Check for new functions in auth-modal.js
echo "\nTest 6: Checking new functions in auth-modal.js...\n";
$content = file_get_contents('../assets/js/auth-modal.js');
$functions = ['showEmailVerificationModal', 'createEmailVerificationModal', 'handleEmailVerification', 'resendRegisterVerificationCode'];
foreach ($functions as $func) {
    if (strpos($content, "function $func") !== false) {
        echo "   ✅ Function '$func' found\n";
    } else {
        echo "   ❌ Function '$func' not found\n";
    }
}

echo "\n========================================\n";
echo "✅ TEST COMPLETED\n";
echo "========================================\n\n";

echo "🚀 Next Steps:\n";
echo "1. Open http://localhost/retroloved/ in browser\n";
echo "2. Click 'Register' button\n";
echo "3. Fill the registration form\n";
echo "4. Check email for OTP code\n";
echo "5. Enter OTP and verify\n\n";

echo "💡 Tips:\n";
echo "- Check browser console for debug logs\n";
echo "- OTP code shown in toast if email fails (dev mode)\n";
echo "- OTP expires after 10 minutes\n";
echo "- Resend available after 60 seconds\n\n";
?>
