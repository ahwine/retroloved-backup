-- ===================================================================
-- MIGRATION SCRIPT: FIX BUGS & ADD MISSING FEATURES
-- RetroLoved E-Commerce - SMK RPL Project
-- ===================================================================
-- Jalankan script ini untuk memperbaiki bug dan menambah fitur baru
-- ===================================================================

-- 1. Tambah kolom is_sold untuk track produk yang sudah terjual
-- Ini fix BUG #1: Produk preloved harus unique, sekali terjual tidak bisa dibeli lagi
ALTER TABLE products ADD COLUMN IF NOT EXISTS is_sold TINYINT(1) DEFAULT 0 AFTER is_active;

-- 2. Tambah index untuk improve performance
-- Ini akan mempercepat query saat presentasi dengan banyak data
ALTER TABLE cart ADD INDEX IF NOT EXISTS idx_user_id (user_id);
ALTER TABLE cart ADD INDEX IF NOT EXISTS idx_product_id (product_id);
ALTER TABLE orders ADD INDEX IF NOT EXISTS idx_user_id (user_id);
ALTER TABLE orders ADD INDEX IF NOT EXISTS idx_status (status);
ALTER TABLE order_items ADD INDEX IF NOT EXISTS idx_order_id (order_id);
ALTER TABLE order_items ADD INDEX IF NOT EXISTS idx_product_id (product_id);
ALTER TABLE wishlist ADD INDEX IF NOT EXISTS idx_user_id (user_id);
ALTER TABLE wishlist ADD INDEX IF NOT EXISTS idx_product_id (product_id);
ALTER TABLE products ADD INDEX IF NOT EXISTS idx_is_active (is_active);
ALTER TABLE products ADD INDEX IF NOT EXISTS idx_is_sold (is_sold);
ALTER TABLE products ADD INDEX IF NOT EXISTS idx_is_featured (is_featured);

-- 3. Update products yang sudah ada di order_items menjadi sold
-- Tandai produk yang sudah terjual
UPDATE products p
INNER JOIN order_items oi ON p.product_id = oi.product_id
INNER JOIN orders o ON oi.order_id = o.order_id
SET p.is_sold = 1
WHERE o.status IN ('Processing', 'Shipped', 'Delivered')
AND p.is_sold = 0;

-- 4. Bersihkan duplicate di cart (jika ada)
-- Hapus duplicate cart items, keep yang terbaru
DELETE c1 FROM cart c1
INNER JOIN cart c2 
WHERE c1.cart_id < c2.cart_id 
AND c1.user_id = c2.user_id 
AND c1.product_id = c2.product_id;

-- 5. Bersihkan duplicate di wishlist (jika ada)
DELETE w1 FROM wishlist w1
INNER JOIN wishlist w2 
WHERE w1.wishlist_id < w2.wishlist_id 
AND w1.user_id = w2.user_id 
AND w1.product_id = w2.product_id;

-- 6. Hapus cart items untuk produk yang sudah tidak aktif
DELETE c FROM cart c
INNER JOIN products p ON c.product_id = p.product_id
WHERE p.is_active = 0 OR p.is_sold = 1;

-- 7. Hapus wishlist items untuk produk yang sudah tidak aktif atau terjual
DELETE w FROM wishlist w
INNER JOIN products p ON w.product_id = p.product_id
WHERE p.is_active = 0 OR p.is_sold = 1;

-- ===================================================================
-- VERIFICATION QUERIES (Run these to check)
-- ===================================================================

-- Check kolom is_sold sudah ada
-- SHOW COLUMNS FROM products LIKE 'is_sold';

-- Check berapa produk yang sudah terjual
-- SELECT COUNT(*) as total_sold FROM products WHERE is_sold = 1;

-- Check berapa produk available
-- SELECT COUNT(*) as total_available FROM products WHERE is_active = 1 AND is_sold = 0;

-- Check indexes
-- SHOW INDEX FROM products;
-- SHOW INDEX FROM cart;
-- SHOW INDEX FROM orders;

-- ===================================================================
-- ROLLBACK (Jika ada masalah, jalankan ini untuk rollback)
-- ===================================================================

-- ALTER TABLE products DROP COLUMN is_sold;
-- DROP INDEX idx_user_id ON cart;
-- DROP INDEX idx_product_id ON cart;
-- (dll... untuk rollback semua perubahan)

-- ===================================================================
-- END OF MIGRATION
-- ===================================================================
