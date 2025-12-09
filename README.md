# 🛍️ RetroLoved - Vintage Fashion E-Commerce Platform

![Version](https://img.shields.io/badge/version-2.0-blue)
![PHP](https://img.shields.io/badge/PHP-7.4+-purple)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-orange)
![License](https://img.shields.io/badge/license-MIT-green)

Platform e-commerce modern untuk penjualan fashion vintage dan preloved berkualitas tinggi. Sistem lengkap dengan fitur customer dan admin panel yang powerful.

![RetroLoved](https://img.shields.io/badge/Version-2.0.0-orange?style=for-the-badge)
![PHP](https://img.shields.io/badge/PHP-7.4+-blue?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-blue?style=for-the-badge&logo=mysql)
![Responsive](https://img.shields.io/badge/Mobile-Responsive-green?style=for-the-badge)

**RetroLoved** adalah platform e-commerce modern untuk penjualan fashion vintage dan preloved. Dibangun dengan PHP native, MySQL, dan modern UI/UX design yang responsif.

---

## 🎯 Fitur Utama

### 👤 Fitur Customer

**🔐 Autentikasi & Profil:**
- Login/Register dengan validasi email lengkap
- Reset password dengan kode verifikasi 6 digit
- Manajemen profil dengan upload foto profil
- Multiple alamat pengiriman dengan opsi alamat default

**🛒 Shopping Experience:**
- Browse produk dengan pagination (12 item per halaman)
- Product detail dengan image gallery (hingga 10 gambar)
- Image lightbox dengan zoom & navigation
- Recently viewed products (tracking otomatis menggunakan cookies)
- Featured products showcase di homepage
- Direct checkout "Beli Sekarang" tanpa masuk cart
- Shopping cart dengan validasi real-time stock

**📦 Order Management:**
- Checkout dengan pilihan alamat pengiriman
- Upload bukti pembayaran dengan auto-compression
- Track status pesanan real-time (Pending → Processing → Shipped → Delivered)
- Riwayat pesanan lengkap dengan detail items
- Cancel order untuk status Pending
- Download & view payment proof

**🔔 Real-time Notifications:**
- Notifikasi otomatis untuk setiap perubahan status order
- Filter notifikasi by type (Pending, Confirmed, Shipped, Delivered, Cancelled)
- Mark as read/unread untuk setiap notifikasi
- Delete notification individual atau bulk
- Badge counter untuk unread notifications
- Toast notifications yang modern dan non-blocking

### 👨‍💼 Fitur Admin Panel

**📊 Dashboard & Analytics:**
- Statistik real-time (Products, Orders, Customers, Pending Orders)
- Revenue summary (Hari ini, Minggu ini, Bulan ini)
- Recent orders dengan quick actions
- Growth indicators dan trend analysis

**📦 Product Management:**
- CRUD lengkap untuk produk dengan validasi
- Upload multiple images (hingga 10 gambar per produk)
- Image management (tambah/hapus gambar secara individual)
- Bulk actions (delete, mark as sold/available)
- Quick toggle sold status dengan 1 klik
- Featured product marking untuk homepage
- Product status management (active/inactive)
- Support untuk original price & discount

**🛍️ Order Management:**
- Daftar pesanan lengkap dengan filter by status
- Update status pesanan dengan tracking number & admin notes
- Bulk update status untuk multiple orders
- View order details lengkap dengan customer info
- Order history & timeline untuk audit trail
- Verifikasi bukti pembayaran customer
- Export orders ke Excel, PDF, CSV
- Automatic customer notifications saat status berubah

**👥 Customer Management:**
- Daftar customer dengan statistik pembelian
- View customer profile & order history
- Customer lifetime value (CLV) calculation
- Total orders & total spent per customer
- Shipping addresses management
- Search & filter customers

### 🎨 Design & User Experience

**Modern UI/UX:**
- Clean & professional minimalist design
- Fully responsive (Desktop, Tablet, Mobile optimized)
- Touch-friendly mobile interface dengan smooth gestures
- Smooth animations & micro-interactions
- Professional typography dengan hierarchy yang jelas
- Consistent color scheme dan spacing
- Accessibility-friendly dengan proper contrast

**Interactive Elements:**
- Modern toast notifications (no more blocking alert!)
- Loading spinners untuk feedback saat proses
- Image lightbox dengan zoom & navigation
- Hover effects & transitions yang smooth
- Form validation real-time dengan feedback visual
- Dropdown menus dan modals yang elegant

### 🔧 Technical Features

**Performance & Optimization:**
- Image auto-compression (hingga 70% lebih kecil)
- Lazy loading untuk gambar produk
- Optimized database queries dengan indexing
- Session management yang secure
- CSRF protection untuk forms

**Code Quality:**
- **Clean codebase** - 49+ file dokumentasi tidak berguna telah dihapus
- **Comprehensive comments** - Semua kode dengan comment Bahasa Indonesia
- **Modular structure** - Kode yang rapi dan mudah di-maintain
- **Best practices** - Mengikuti PHP & SQL best practices
- **Security first** - SQL injection protection, XSS prevention

**Developer Friendly:**
- Database migrations untuk easy setup
- Helper functions yang reusable
- Consistent naming conventions
- Separation of concerns (MVC-like structure)
- Toast notification system yang mudah digunakan

---

## 🚀 Tech Stack

| Technology | Purpose |
|------------|---------|
| **PHP 7.4+** | Backend Logic |
| **MySQL 8.0+** | Database |
| **HTML5/CSS3** | Frontend Structure |
| **Vanilla JavaScript** | Interactive Features |
| **XAMPP** | Local Development |

---

## 📁 Struktur Project

```
RetroLoved/
├── 📂 admin/                    # Admin Panel
│   ├── dashboard.php           # Dashboard dengan statistik
│   ├── products.php            # Manajemen produk
│   ├── product-add.php         # Tambah produk baru
│   ├── product-edit.php        # Edit produk
│   ├── orders.php              # Manajemen pesanan
│   ├── order-detail.php        # Detail pesanan
│   ├── customers.php           # Manajemen customer
│   ├── customer-detail.php     # Detail customer
│   └── includes/               # Components admin
│       ├── sidebar.php
│       └── navbar.php
│
├── 📂 auth/                     # Autentikasi
│   ├── process-auth.php        # Proses login/register/reset
│   └── logout.php              # Logout
│
├── 📂 customer/                 # Area Customer
│   ├── cart.php                # Keranjang belanja
│   ├── checkout.php            # Proses checkout
│   ├── orders.php              # Riwayat pesanan
│   ├── notifications.php       # Notifikasi
│   ├── profile.php             # Pengaturan profil
│   └── product-detail.php      # Detail produk
│
├── 📂 config/                   # Konfigurasi
│   └── database.php            # Koneksi DB & helper functions
│
├── 📂 includes/                 # Reusable Components
│   ├── header.php              # Header & navbar
│   └── footer.php              # Footer
│
├── 📂 assets/                   # Static Files
│   ├── css/
│   │   ├── style.css           # Main stylesheet
│   │   ├── admin.css           # Admin panel styles
│   │   ├── auth.css            # Auth modal styles
│   │   └── toast.css           # Toast notification styles
│   ├── js/
│   │   ├── script.js           # Main JavaScript
│   │   ├── auth-modal.js       # Modal autentikasi
│   │   ├── toast.js            # Sistem notifikasi toast
│   │   ├── modal.js            # Sistem modal
│   │   ├── validation.js       # Form validation
│   │   ├── image-upload.js     # Image upload handler
│   │   └── product-lightbox.js # Image lightbox
│   └── images/
│       ├── products/           # Upload produk
│       ├── payments/           # Bukti pembayaran
│       └── profiles/           # Foto profil user
│
├── 📂 database/                 # Database & Migrations
│   ├── retroloved.sql          # Database schema
│   └── migrations/             # Database migrations
│
├── 📄 index.php                 # Homepage
├── 📄 shop.php                  # Katalog produk
├── 📄 faq.php                   # FAQ
├── 📄 how-it-works.php          # Cara kerja
├── 📄 terms-conditions.php      # Syarat & ketentuan
├── 📄 privacy-policy.php        # Kebijakan privasi
├── 📄 shipping-delivery.php     # Info pengiriman
├── 📄 size-guide.php            # Panduan ukuran
├── 📄 .htaccess                 # Apache configuration
└── 📄 README.md                 # Dokumentasi ini
```

---

## 💾 Database Schema

### Tabel Utama:

| Tabel | Deskripsi | Kolom Utama |
|-------|-----------|-------------|
| **users** | Data pengguna (admin & customer) | user_id, username, email, password, role, profile_picture |
| **products** | Katalog produk | product_id, product_name, price, category, image_url (1-10), is_sold, is_featured |
| **cart** | Keranjang belanja | cart_id, user_id, product_id |
| **orders** | Transaksi pesanan | order_id, user_id, total_amount, status, payment_proof, tracking_number |
| **order_items** | Detail item pesanan | item_id, order_id, product_id, price |
| **shipping_addresses** | Alamat pengiriman | address_id, user_id, recipient_name, phone, address, is_default |
| **notifications** | Notifikasi pengguna | notification_id, user_id, order_id, type, title, message, is_read |
| **order_history** | Riwayat perubahan order | history_id, order_id, status, tracking_number, changed_by |
| **password_resets** | Reset password tokens | reset_id, user_id, reset_code, expires_at |

### Relasi Database:
- **users** ↔ **orders** (One to Many)
- **orders** ↔ **order_items** (One to Many)
- **products** ↔ **order_items** (One to Many)
- **users** ↔ **shipping_addresses** (One to Many)
- **users** ↔ **notifications** (One to Many)
- **orders** ↔ **order_history** (One to Many)

---

## ⚙️ Instalasi & Setup

### Persyaratan Sistem:
- **PHP** 7.4 atau lebih tinggi
- **MySQL** 5.7 atau lebih tinggi
- **Apache** dengan mod_rewrite enabled
- **XAMPP** / **WAMP** / **MAMP** (untuk local development)

### Langkah Instalasi:

1. **Clone atau Download Project**
   ```bash
   git clone https://github.com/username/retroloved.git
   cd retroloved
   ```

2. **Setup Database**
   - Buka phpMyAdmin (http://localhost/phpmyadmin)
   - Buat database baru: `retroloved`
   - Import file `database/retroloved.sql`
   - Atau jalankan migration: `database/run_all_migrations.php`

3. **Konfigurasi Database**
   - Buka file `config/database.php`
   - Sesuaikan kredensial database:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'retroloved');
   ```

4. **Setup Folder Permissions**
   ```bash
   chmod 755 assets/images/products/
   chmod 755 assets/images/payments/
   chmod 755 assets/images/profiles/
   ```

5. **Akses Website**
   - Buka browser dan akses: `http://localhost/retroloved`
   - Login dengan akun default (lihat di bawah)

## 🔐 Akun Default

### 👨‍💼 Admin Account
```
Email: admin@retroloved.com
Password: admin123
```

### Customer Account (Test)
```
Email: gilang@gmail.com
Password: 123
```

---

## 📦 Installation

Lihat file **[INSTALLATION_GUIDE.md](INSTALLATION_GUIDE.md)** untuk panduan lengkap instalasi.

**Quick Start:**
1. Install XAMPP
2. Clone/extract project ke `C:\xampp\htdocs\RetroLoved`
3. Run migrations: `php database/run_all_migrations.php` ⭐ **NEW**
4. Buka `http://localhost/retroloved`

### 🔄 Database Migrations (NEW)
Untuk setup database dengan mudah:
```bash
php database/run_all_migrations.php
```
Script ini akan otomatis:
- ✅ Menambahkan semua kolom yang diperlukan
- ✅ Membuat tabel baru (notifications, shipping_addresses, password_resets)
- ✅ Menambahkan indexes untuk performa
- ✅ Membersihkan kolom yang tidak diperlukan
- ✅ Skip jika sudah dijalankan sebelumnya (safe to re-run)

---

## 📚 Documentation

### Available Guides
- **[INSTALLATION_GUIDE.md](INSTALLATION_GUIDE.md)** - Setup lengkap dari awal
- **[FEATURES_GUIDE.md](FEATURES_GUIDE.md)** - Panduan lengkap semua fitur baru ⭐ **NEW**
- **[CHANGELOG.md](CHANGELOG.md)** - Daftar perubahan versi 2.0 ⭐ **NEW**
- **[PROJECT_SUMMARY.md](PROJECT_SUMMARY.md)** - Ringkasan project
- **[LIVE_SERVER_GUIDE.md](LIVE_SERVER_GUIDE.md)** - Deploy ke hosting

### Quick Reference

#### Toast Notifications
```javascript
// Success
toastSuccess('Data berhasil disimpan!');

// Error
toastError('Gagal menyimpan data!');

// Warning  
toastWarning('Periksa input Anda!');

// Info
toastInfo('Proses sedang berjalan...');
```

#### Image Compression
```javascript
// Auto setup
setupImageCompression('input[type="file"]');

// Manual
const compressed = await compressImage(file, 1200, 1200, 0.8);
```

#### Export Orders
```html
<!-- Excel -->
<button data-export="excel">Export to Excel</button>

<!-- PDF -->
<button data-export="pdf">Export to PDF</button>
```

---

## 🌐 Live Server untuk Presentasi

Lihat file **[LIVE_SERVER_GUIDE.md](LIVE_SERVER_GUIDE.md)** untuk cara share project dengan teman dan setup live server untuk presentasi.

---

## 📱 Responsive Design

Website fully responsive untuk semua device:

| Device | Breakpoint | Layout |
|--------|------------|--------|
| Desktop | 1280px+ | 4 column grid |
| Tablet | 768px - 1024px | 2-3 column grid |
| Mobile | < 768px | 1 column, horizontal cards |

---

## 🎯 Key Features Detail

### 🛍️ Shopping Experience
- **Product Grid** - Modern card design with hover effects
- **Smart Search** - Real-time product filtering
- **Category Filter** - Browse by category
- **Wishlist** - Save favorite items
- **Cart** - Add multiple items, update quantity
- **Checkout** - Multiple payment methods (Bank Transfer, E-Wallet)

### 💳 Payment System
- **Multiple Methods** - BCA, BRI, Mandiri, GoPay, DANA, OVO
- **Upload Proof** - Customer upload payment screenshot
- **Admin Verification** - Admin verify and approve payments
- **Order Tracking** - Real-time order status

### 📊 Admin Dashboard
- **Statistics** - Total products, orders, customers
- **Order Management** - View, verify, update order status
- **Product Management** - CRUD operations
- **Image Upload** - Product image management

---

## 🛠️ Development

### Requirements
- PHP 7.4 or higher
- MySQL 8.0 or higher
- XAMPP (recommended) or any LAMP/WAMP stack
- Modern browser (Chrome, Firefox, Edge)

### Setup Development Environment
```bash
# 1. Clone repository
git clone https://github.com/yourusername/retroloved.git

# 2. Move to XAMPP directory
move retroloved C:\xampp\htdocs\

# 3. Import database
# Open phpMyAdmin → Import → retroloved.sql

# 4. Configure database (if needed)
# Edit config/database.php

# 5. Start XAMPP
# Apache + MySQL

# 6. Open browser
http://localhost/retroloved
```

---

## 🧪 Testing

### Test Accounts
- **Admin**: admin@retroloved.com / admin123
- **Customer**: gilang@gmail.com / 123

### Test Flow
1. Register new customer account
2. Browse products
3. Add to cart & wishlist
4. Checkout with payment method
5. Upload payment proof
6. Login as admin
7. Verify payment & update order status

---

## 🎨 Screenshots

### Homepage
Modern hero section dengan featured products

### Product Grid
Responsive card layout dengan hover effects

### Cart & Checkout
Clean checkout process dengan payment options

### Admin Dashboard
Professional admin panel dengan statistics

---

## 🚧 Future Enhancements

- [ ] Product reviews & ratings
- [ ] Advanced search & filters
- [ ] Email notifications
- [ ] SMS notifications
- [ ] Export reports to PDF
- [ ] Product variants (size, color)
- [ ] Shipping integration
- [ ] Payment gateway integration
- [ ] Mobile app (React Native)

---

## 📄 License

This project is created for **educational purposes**.

---

## 👨‍💻 Developer

**Developed by:** [Your Name]  
**Project:** Final Project / Tugas Akhir  
**Institution:** [Your University/School]  
**Year:** 2025

---

## 📞 Support

For questions or issues:
- Email: support@retroloved.com
- GitHub Issues: [Create Issue](https://github.com/yourusername/retroloved/issues)

---

## 🙏 Acknowledgments

- Design inspiration: Nike, Apple
- Icons: Feather Icons
- Fonts: Inter, Playfair Display
- Colors: Tailwind CSS Orange palette

---

**⭐ If you find this project helpful, please give it a star!**

Made with ❤️ in Surabaya, Indonesia
