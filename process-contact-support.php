<?php
session_start();
require_once 'config/database.php';
require_once 'config/email.php';

// Set timezone ke Asia/Jakarta (WIB)
date_default_timezone_set('Asia/Jakarta');

header('Content-Type: application/json');

// Enable error logging for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors in JSON response
ini_set('log_errors', 1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get form data
$name = mysqli_real_escape_string($conn, trim($_POST['name'] ?? ''));
$email = mysqli_real_escape_string($conn, trim($_POST['email'] ?? ''));
$subject = mysqli_real_escape_string($conn, trim($_POST['subject'] ?? ''));
$message = mysqli_real_escape_string($conn, trim($_POST['message'] ?? ''));

// Validation
$errors = [];

if (empty($name)) {
    $errors[] = 'Nama harus diisi';
}

if (empty($email)) {
    $errors[] = 'Email harus diisi';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Format email tidak valid';
}

if (empty($subject)) {
    $errors[] = 'Subjek harus diisi';
}

if (empty($message)) {
    $errors[] = 'Pesan harus diisi';
} elseif (strlen($message) < 10) {
    $errors[] = 'Pesan minimal 10 karakter';
}

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

// PRIORITAS 1: Simpan ke database dulu (yang pasti berhasil)
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

// Check if table exists
$table_check = mysqli_query($conn, "SHOW TABLES LIKE 'contact_support'");
if (mysqli_num_rows($table_check) == 0) {
    // Table doesn't exist, should not happen but create it just in case
    echo json_encode([
        'success' => false, 
        'message' => 'Tabel contact_support belum dibuat. Silakan hubungi administrator.'
    ]);
    exit;
}

// Insert to database
$insert_query = "INSERT INTO contact_support (user_id, name, email, subject, message, status, created_at) 
                 VALUES ('$user_id', '$name', '$email', '$subject', '$message', 'new', NOW())";

$insert_result = mysqli_query($conn, $insert_query);

if (!$insert_result) {
    // Database insert failed
    $db_error = mysqli_error($conn);
    error_log("Contact Support DB Error: " . $db_error);
    
    echo json_encode([
        'success' => false, 
        'message' => 'Gagal menyimpan pesan. Silakan coba lagi. Error: ' . $db_error
    ]);
    mysqli_close($conn);
    exit;
}

// Database insert successful!
$insert_id = mysqli_insert_id($conn);

// PRIORITAS 2: Kirim email ke admin
$email_subject = 'Contact Support Request #' . $insert_id;

// Generate email content
$email_content = "
    <div class='info-box'>
        <h3 style='margin-top: 0; color: #D97706;'>Pesan Contact Support Baru</h3>
        <p><strong>Support ID:</strong> #$insert_id</p>
    </div>
    
    <table style='width: 100%; border-collapse: collapse; margin: 20px 0;'>
        <tr style='background: #F3F4F6;'>
            <td style='padding: 12px; font-weight: 600; width: 30%; border: 1px solid #E5E7EB;'>Dari:</td>
            <td style='padding: 12px; border: 1px solid #E5E7EB;'>{$name}</td>
        </tr>
        <tr>
            <td style='padding: 12px; font-weight: 600; border: 1px solid #E5E7EB;'>Email:</td>
            <td style='padding: 12px; border: 1px solid #E5E7EB;'><a href='mailto:{$email}' style='color: #D97706;'>{$email}</a></td>
        </tr>
        <tr style='background: #F3F4F6;'>
            <td style='padding: 12px; font-weight: 600; border: 1px solid #E5E7EB;'>Subject:</td>
            <td style='padding: 12px; border: 1px solid #E5E7EB;'><strong>{$subject}</strong></td>
        </tr>
        <tr>
            <td style='padding: 12px; font-weight: 600; border: 1px solid #E5E7EB;'>Waktu:</td>
            <td style='padding: 12px; border: 1px solid #E5E7EB;'>" . date('d F Y, H:i:s') . "</td>
        </tr>
    </table>
    
    <div style='background: #F9FAFB; padding: 20px; border-radius: 8px; border: 1px solid #E5E7EB; margin: 20px 0;'>
        <h4 style='margin-top: 0; color: #1F2937;'>Pesan:</h4>
        <div style='line-height: 1.8; color: #4B5563;'>" . nl2br(htmlspecialchars($message)) . "</div>
    </div>
    
    <p style='color: #6B7280; font-size: 14px;'>
        Silakan balas langsung ke email customer untuk merespon pesan ini.
    </p>
";

$email_body = EmailHelper::getTemplate('Pesan Contact Support - RetroLoved', $email_content);

// Kirim email ke retroloved.ofc@gmail.com
try {
    $mail_sent = EmailHelper::send(SUPPORT_EMAIL, $email_subject, $email_body, $email);
    if ($mail_sent) {
        error_log("Contact Support Email sent successfully to " . SUPPORT_EMAIL . " for support #$insert_id");
    }
} catch (Exception $e) {
    // Mail failed, but that's okay - data is already saved
    error_log("Contact Support Email failed: " . $e->getMessage());
}

// Success response - data saved to database
echo json_encode([
    'success' => true, 
    'message' => 'Pesan berhasil dikirim! Tim admin akan segera membalas pesan Anda.',
    'support_id' => $insert_id
]);

mysqli_close($conn);
?>