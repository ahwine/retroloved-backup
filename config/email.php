<?php
/**
 * Email Configuration & Helper Class
 * Supports both PHPMailer (SMTP) and PHP mail() function
 * RetroLoved E-Commerce System
 */

// ===== EMAIL CONFIGURATION =====
// Ganti dengan kredensial Gmail Anda atau SMTP lainnya

define('EMAIL_METHOD', 'SMTP'); // 'SMTP' atau 'MAIL'

// SMTP Configuration (untuk Gmail atau SMTP lainnya)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls'); // 'tls' atau 'ssl'
define('SMTP_USERNAME', 'retroloved.ofc@gmail.com'); // Email untuk kirim
define('SMTP_PASSWORD', 'ypxwjcehfokgscvd');    // App Password (16 digit tanpa spasi)

// Email Default Settings
define('EMAIL_FROM', 'retroloved.ofc@gmail.com');
define('EMAIL_FROM_NAME', 'RetroLoved');
define('EMAIL_REPLY_TO', 'retroloved.ofc@gmail.com');

// Admin Email untuk notifikasi
define('ADMIN_EMAIL', 'admin@retroloved.com');

// Support Email untuk reply customer
define('SUPPORT_EMAIL', 'retroloved.ofc@gmail.com');

/**
 * Email Helper Class
 * Mengirim email menggunakan PHPMailer atau built-in mail()
 */
class EmailHelper {
    
    /**
     * Kirim email dengan template HTML
     * 
     * @param string $to - Email penerima
     * @param string $subject - Subject email
     * @param string $body - HTML body email
     * @param string $replyTo - Reply-to email (optional)
     * @return bool - True jika berhasil
     */
    public static function send($to, $subject, $body, $replyTo = null) {
        if (EMAIL_METHOD === 'SMTP' && file_exists(__DIR__ . '/../vendor/autoload.php')) {
            return self::sendViaSMTP($to, $subject, $body, $replyTo);
        } else {
            return self::sendViaMail($to, $subject, $body, $replyTo);
        }
    }
    
    /**
     * Kirim email via PHPMailer SMTP
     */
    private static function sendViaSMTP($to, $subject, $body, $replyTo = null) {
        try {
            // Load PHPMailer
            require_once __DIR__ . '/../vendor/phpmailer/PHPMailer-6.9.1/src/PHPMailer.php';
            require_once __DIR__ . '/../vendor/phpmailer/PHPMailer-6.9.1/src/SMTP.php';
            require_once __DIR__ . '/../vendor/phpmailer/PHPMailer-6.9.1/src/Exception.php';
            
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            
            // Enable verbose debug output (comment out in production)
            // $mail->SMTPDebug = 2; // 0 = off, 1 = client, 2 = client and server
            // $mail->Debugoutput = function($str, $level) { error_log("SMTP Debug level $level; message: $str"); };
            
            // SMTP Configuration
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USERNAME;
            $mail->Password = SMTP_PASSWORD;
            $mail->SMTPSecure = SMTP_SECURE;
            $mail->Port = SMTP_PORT;
            $mail->Timeout = 30; // 30 seconds timeout
            
            // Email Content
            $mail->setFrom(EMAIL_FROM, EMAIL_FROM_NAME);
            $mail->addAddress($to);
            
            if ($replyTo) {
                $mail->addReplyTo($replyTo);
            } else {
                $mail->addReplyTo(EMAIL_REPLY_TO, EMAIL_FROM_NAME);
            }
            
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->CharSet = 'UTF-8';
            
            // Send
            $mail->send();
            return true;
            
        } catch (Exception $e) {
            error_log("Email Error (SMTP): " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Kirim email via built-in mail() function
     */
    private static function sendViaMail($to, $subject, $body, $replyTo = null) {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: " . EMAIL_FROM_NAME . " <" . EMAIL_FROM . ">" . "\r\n";
        
        if ($replyTo) {
            $headers .= "Reply-To: $replyTo" . "\r\n";
        } else {
            $headers .= "Reply-To: " . EMAIL_REPLY_TO . "\r\n";
        }
        
        try {
            $result = @mail($to, $subject, $body, $headers);
            if (!$result) {
                error_log("Email Error (mail()): Failed to send to $to");
            }
            return $result;
        } catch (Exception $e) {
            error_log("Email Error (mail()): " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Generate HTML email template
     */
    public static function getTemplate($title, $content, $footerText = null) {
        $year = date('Y');
        $footer = $footerText ?: "&copy; $year RetroLoved. All Rights Reserved.";
        
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background: #f4f4f4; }
                .container { max-width: 600px; margin: 20px auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
                .header { background: #D97706; color: white; padding: 30px 20px; text-align: center; }
                .header h1 { margin: 0; font-size: 24px; font-weight: 700; }
                .content { padding: 30px 20px; }
                .footer { background: #1F2937; color: #9CA3AF; padding: 20px; text-align: center; font-size: 12px; }
                .btn { display: inline-block; padding: 12px 30px; background: #D97706; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; margin: 15px 0; }
                .btn:hover { background: #B45309; }
                .info-box { background: #F3F4F6; border-left: 4px solid #D97706; padding: 15px; margin: 15px 0; border-radius: 4px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>$title</h1>
                </div>
                <div class='content'>
                    $content
                </div>
                <div class='footer'>
                    <p>$footer</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
}
?>
