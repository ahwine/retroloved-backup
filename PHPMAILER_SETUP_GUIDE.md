# ============================================================
# PHPMAILER IMPLEMENTATION GUIDE
# RetroLoved E-Commerce System
# Date: 2025-12-08 13:22:34
# ============================================================

## 📧 EMAIL IMPLEMENTATION COMPLETE!

Successfully integrated email functionality for:
1. ✅ Contact Support (Customer → Admin)
2. ✅ Forgot Password (Reset Code)

## 🎯 FEATURES IMPLEMENTED

### EmailHelper Class (config/email.php)
- ✅ Support untuk SMTP (PHPMailer) atau mail()
- ✅ Automatic fallback jika PHPMailer tidak tersedia
- ✅ Beautiful HTML email templates
- ✅ Easy configuration
- ✅ Error handling & logging

### Contact Support Email
- ✅ Kirim notifikasi ke admin saat customer submit form
- ✅ Include support ID, customer info, dan message
- ✅ Direct link ke admin panel
- ✅ Reply-to customer email

### Forgot Password Email
- ✅ Kirim kode verifikasi 6 digit
- ✅ Expiry time display (15 menit)
- ✅ Security warning message
- ✅ Development mode fallback (tampilkan kode di response)

## 🛠️ SETUP INSTRUCTIONS

### Option 1: Menggunakan Gmail SMTP (Recommended untuk Testing)

#### Step 1: Install PHPMailer via Composer
\\\ash
# Di terminal/command prompt, jalankan:
composer install
\\\

Jika composer belum terinstall, download dari: https://getcomposer.org/

#### Step 2: Setup Gmail App Password

1. **Login ke Gmail Anda**
2. **Buka Settings** → Security
3. **Enable 2-Step Verification** (wajib!)
4. **Generate App Password:**
   - Go to: https://myaccount.google.com/apppasswords
   - Select app: "Mail"
   - Select device: "Other (Custom name)" → "RetroLoved"
   - Click "Generate"
   - Copy 16-character password (contoh: abcd efgh ijkl mnop)

#### Step 3: Update config/email.php

Edit file \config/email.php\ line 11-14:

\\\php
define('SMTP_USERNAME', 'your-email@gmail.com');     // Ganti dengan email Anda
define('SMTP_PASSWORD', 'abcdefghijklmnop');         // Paste App Password (tanpa spasi)
\\\

Edit line 18 untuk admin email:
\\\php
define('ADMIN_EMAIL', 'admin@retroloved.com');  // Email admin untuk terima notifikasi
\\\

### Option 2: Menggunakan Built-in mail() (Localhost)

Jika tidak ingin setup Gmail SMTP, edit \config/email.php\ line 9:

\\\php
define('EMAIL_METHOD', 'MAIL'); // Ganti dari 'SMTP' ke 'MAIL'
\\\

**Catatan:** mail() function tidak akan bekerja di XAMPP localhost. Tapi:
- ✅ Data tetap tersimpan ke database
- ✅ Admin tetap bisa lihat di panel
- ✅ Development mode akan tampilkan kode reset password di response

## 📝 CONFIGURATION FILE

File: \config/email.php\

\\\php
// ===== EMAIL METHOD =====
define('EMAIL_METHOD', 'SMTP'); // 'SMTP' atau 'MAIL'

// ===== SMTP SETTINGS =====
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls');
define('SMTP_USERNAME', 'your-email@gmail.com');     // ⚠️ GANTI INI
define('SMTP_PASSWORD', 'your-app-password');        // ⚠️ GANTI INI

// ===== EMAIL DEFAULTS =====
define('EMAIL_FROM', 'noreply@retroloved.com');
define('EMAIL_FROM_NAME', 'RetroLoved');
define('ADMIN_EMAIL', 'admin@retroloved.com');       // ⚠️ GANTI INI
\\\

## 🧪 TESTING

### Test 1: Contact Support Email

1. **Buka website:** http://localhost/retroloved/
2. **Click** floating Contact Support button
3. **Isi form** dan submit
4. **Check:**
   - ✅ Toast success muncul
   - ✅ Data masuk admin panel
   - ✅ Email dikirim ke ADMIN_EMAIL (jika SMTP configured)

### Test 2: Forgot Password Email

1. **Go to login page**
2. **Click** "Forgot Password"
3. **Enter** registered email
4. **Check:**
   - ✅ Success message muncul
   - ✅ Email dengan 6-digit code terkirim
   - ✅ Code valid untuk 15 menit
   - ✅ Jika email gagal, code ditampilkan di response (dev mode)

## 📊 EMAIL TEMPLATES

### Contact Support Template
- **Subject:** 🆘 Contact Support Request #{id}
- **To:** Admin email
- **Reply-To:** Customer email
- **Content:**
  - Support ID
  - Customer name & email
  - Subject & message
  - Link to admin panel

### Forgot Password Template
- **Subject:** 🔐 Reset Password - Kode Verifikasi
- **To:** User email
- **Content:**
  - User's full name
  - 6-digit verification code (large, centered)
  - Expiry time (15 minutes)
  - Security warning

## 🔧 DEVELOPMENT MODE

Jika email tidak bisa dikirim (SMTP not configured), system akan:

### Contact Support:
- ✅ Data TETAP tersimpan ke database
- ✅ Admin TETAP bisa lihat di panel
- ✅ Customer dapat success message
- ⚠️ Admin tidak dapat email notification

### Forgot Password:
- ✅ Reset code TETAP tersimpan ke database
- ✅ User dapat success message
- ✅ Code ditampilkan di response JSON (dev_code)
- ⚠️ User tidak dapat email

## 🚀 PRODUCTION MODE

Di production server dengan SMTP configured:
- ✅ Email akan terkirim otomatis
- ✅ Code tidak akan ditampilkan di response
- ✅ Professional email notifications
- ✅ Better user experience

## 📁 FILES MODIFIED

1. **config/email.php** (NEW)
   - EmailHelper class
   - SMTP configuration
   - Email templates

2. **process-contact-support.php**
   - Added require_once 'config/email.php'
   - Use EmailHelper::send()
   - Better email template

3. **auth/process-auth.php**
   - Added email functionality for forgot password
   - Beautiful verification code email
   - Development mode fallback

4. **composer.json** (NEW)
   - PHPMailer dependency

## ⚠️ IMPORTANT NOTES

### Gmail SMTP:
- ✅ FREE untuk penggunaan normal
- ✅ Reliable dan fast
- ✅ Limit: 500 emails/day
- ⚠️ Butuh 2-Step Verification enabled
- ⚠️ Harus pakai App Password, BUKAN password Gmail biasa

### Security:
- ✅ NEVER commit config/email.php dengan real credentials ke Git
- ✅ Use .gitignore untuk protect sensitive files
- ✅ Use environment variables di production
- ✅ Rotate App Password secara berkala

### Alternative SMTP Services:
- Mailtrap.io (Testing only, fake SMTP)
- SendGrid (Production, free tier 100 emails/day)
- Mailgun (Production, free tier 5000 emails/month)
- AWS SES (Production, pay as you go)

## 🎯 NEXT STEPS

1. [ ] Install Composer (if not installed)
2. [ ] Run \composer install\
3. [ ] Setup Gmail App Password
4. [ ] Edit config/email.php
5. [ ] Test Contact Support
6. [ ] Test Forgot Password
7. [ ] Verify emails received

## 💡 TROUBLESHOOTING

### Email tidak terkirim?

1. **Check SMTP credentials**
   - Username benar?
   - App Password correct?
   
2. **Check Gmail settings**
   - 2-Step Verification enabled?
   - App Password generated?
   
3. **Check error logs**
   - Look for errors in PHP error log
   - Check browser console for JS errors
   
4. **Test connection**
   - Try sending test email manually
   - Use email testing tool

### PHPMailer not found?

\\\ash
# Install via Composer
composer install

# Or download manually from:
# https://github.com/PHPMailer/PHPMailer
\\\

### Still not working?

Change EMAIL_METHOD to 'MAIL' and system will still work:
- Data saved to database ✅
- Admin can see in panel ✅
- Just no email notifications ✅

## 📞 SUPPORT

Jika ada masalah dengan implementasi email, system akan tetap berfungsi normal:
- Contact Support: Data tersimpan, admin bisa lihat
- Forgot Password: Code tersimpan, ditampilkan di response

Email is a BONUS feature, not a requirement!

============================================================
