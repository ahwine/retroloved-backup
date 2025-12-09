-- ===== SHIPPING SYSTEM DATABASE MIGRATION =====
-- RetroLoved E-Commerce - Shipping & Order Tracking Feature
-- Consistent with existing database structure

-- 1. Create shipping_couriers table (Master Data Ekspedisi)
CREATE TABLE IF NOT EXISTS shipping_couriers (
    courier_id INT PRIMARY KEY AUTO_INCREMENT,
    courier_code VARCHAR(20) NOT NULL UNIQUE,
    courier_name VARCHAR(100) NOT NULL,
    logo_url VARCHAR(255) NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_courier_code (courier_code),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Create shipping_services table (Layanan per Ekspedisi)
CREATE TABLE IF NOT EXISTS shipping_services (
    service_id INT PRIMARY KEY AUTO_INCREMENT,
    courier_id INT NOT NULL,
    service_code VARCHAR(50) NOT NULL,
    service_name VARCHAR(100) NOT NULL,
    description TEXT NULL,
    base_cost DECIMAL(10,2) NOT NULL DEFAULT 0,
    estimated_days_min INT DEFAULT 1,
    estimated_days_max INT DEFAULT 3,
    is_active TINYINT(1) DEFAULT 1,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (courier_id) REFERENCES shipping_couriers(courier_id) ON DELETE CASCADE,
    UNIQUE KEY unique_service (courier_id, service_code),
    INDEX idx_active (is_active),
    INDEX idx_display_order (display_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Modify orders table - Add shipping columns
ALTER TABLE orders 
ADD COLUMN IF NOT EXISTS shipping_service_id INT NULL AFTER phone,
ADD COLUMN IF NOT EXISTS shipping_cost DECIMAL(10,2) DEFAULT 0 AFTER total_amount,
ADD COLUMN IF NOT EXISTS subtotal DECIMAL(10,2) DEFAULT 0 AFTER total_amount,
ADD COLUMN IF NOT EXISTS courier_name VARCHAR(100) NULL AFTER tracking_number,
ADD COLUMN IF NOT EXISTS courier_phone VARCHAR(20) NULL AFTER courier_name,
ADD COLUMN IF NOT EXISTS current_location VARCHAR(255) NULL AFTER courier_phone,
ADD COLUMN IF NOT EXISTS current_status_detail VARCHAR(50) NULL AFTER current_location,
ADD COLUMN IF NOT EXISTS estimated_delivery_date DATETIME NULL AFTER current_status_detail,
ADD COLUMN IF NOT EXISTS shipped_at TIMESTAMP NULL AFTER estimated_delivery_date,
ADD COLUMN IF NOT EXISTS delivered_at TIMESTAMP NULL AFTER shipped_at;

-- Add foreign key if not exists (check first to avoid duplicate)
SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS 
    WHERE CONSTRAINT_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'orders' 
    AND CONSTRAINT_NAME = 'fk_orders_shipping_service');

SET @sql = IF(@constraint_exists = 0, 
    'ALTER TABLE orders ADD CONSTRAINT fk_orders_shipping_service FOREIGN KEY (shipping_service_id) REFERENCES shipping_services(service_id) ON DELETE SET NULL',
    'SELECT "Foreign key already exists" AS message');

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 4. Modify order_history table - Add tracking detail columns
ALTER TABLE order_history
ADD COLUMN IF NOT EXISTS status_detail VARCHAR(50) NULL AFTER status,
ADD COLUMN IF NOT EXISTS location VARCHAR(255) NULL AFTER notes,
ADD COLUMN IF NOT EXISTS courier_name VARCHAR(100) NULL AFTER location,
ADD COLUMN IF NOT EXISTS courier_phone VARCHAR(20) NULL AFTER courier_name,
ADD COLUMN IF NOT EXISTS estimated_arrival DATETIME NULL AFTER courier_phone;

-- 5. Create tracking_statuses table (Predefined Status Tracking)
CREATE TABLE IF NOT EXISTS tracking_statuses (
    status_id INT PRIMARY KEY AUTO_INCREMENT,
    status_code VARCHAR(50) NOT NULL UNIQUE,
    status_name VARCHAR(100) NOT NULL,
    status_name_id VARCHAR(100) NOT NULL,
    icon_svg TEXT NULL,
    color VARCHAR(20) DEFAULT '#6B7280',
    step_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_step_order (step_order),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Insert Master Data - Couriers
INSERT INTO shipping_couriers (courier_code, courier_name, is_active) VALUES
('jne', 'JNE Express', 1),
('jnt', 'J&T Express', 1),
('sicepat', 'SiCepat Express', 1),
('anteraja', 'AnterAja', 1),
('pickup', 'Ambil Sendiri', 1)
ON DUPLICATE KEY UPDATE courier_name=VALUES(courier_name);

-- 7. Insert Master Data - Shipping Services
INSERT INTO shipping_services (courier_id, service_code, service_name, description, base_cost, estimated_days_min, estimated_days_max, display_order) VALUES
-- JNE Services
(1, 'REG', 'JNE Regular', 'Layanan reguler dengan harga ekonomis', 15000.00, 3, 4, 2),
(1, 'YES', 'JNE YES', 'Yakin Esok Sampai - Garansi pengiriman cepat', 25000.00, 1, 2, 1),
(1, 'OKE', 'JNE OKE', 'Ongkos Kirim Ekonomis untuk pengiriman hemat', 12000.00, 4, 6, 3),

-- J&T Services
(2, 'EZ', 'J&T Express Economy', 'Layanan ekonomis dengan harga terjangkau', 12000.00, 3, 5, 4),
(2, 'REG', 'J&T Regular', 'Layanan standar J&T Express', 15000.00, 2, 4, 5),

-- SiCepat Services
(3, 'REG', 'SiCepat REG', 'Regular Service dengan tracking real-time', 15000.00, 2, 3, 6),
(3, 'HALU', 'SiCepat HALU', 'Hari itu sampai - Layanan same day', 18000.00, 1, 2, 7),

-- AnterAja Services
(4, 'REG', 'AnterAja Regular', 'Layanan reguler AnterAja', 14000.00, 2, 4, 8),
(4, 'NEXT', 'AnterAja Next Day', 'Pengiriman keesokan hari', 20000.00, 1, 2, 9),

-- Pickup (Self Collect)
(5, 'STORE', 'Ambil di Toko', 'Gratis ongkir - Ambil langsung di toko kami', 0.00, 0, 0, 10)
ON DUPLICATE KEY UPDATE 
    service_name=VALUES(service_name),
    description=VALUES(description),
    base_cost=VALUES(base_cost);

-- 8. Insert Master Data - Tracking Statuses
INSERT INTO tracking_statuses (status_code, status_name, status_name_id, color, step_order, description) VALUES
('order_placed', 'Order Placed', 'Pesanan Dibuat', '#6B7280', 1, 'Order successfully created by customer'),
('payment_confirmed', 'Payment Confirmed', 'Pembayaran Dikonfirmasi', '#10B981', 2, 'Payment has been verified by admin'),
('processing', 'Being Packed', 'Pesanan Dikemas', '#F59E0B', 3, 'Product is being packed in warehouse'),
('picked_up', 'Picked Up by Courier', 'Diserahkan ke Kurir', '#3B82F6', 4, 'Package has been picked up by courier'),
('in_sorting', 'At Sorting Center', 'Di Sorting Center', '#8B5CF6', 5, 'Package is at courier sorting center'),
('in_transit', 'In Transit', 'Dalam Perjalanan', '#06B6D4', 6, 'Package is on the way to destination city'),
('arrived_destination', 'Arrived at Destination', 'Tiba di Kota Tujuan', '#14B8A6', 7, 'Package has arrived at destination hub'),
('out_for_delivery', 'Out for Delivery', 'Dikirim ke Alamat', '#6366F1', 8, 'Courier is delivering to customer address'),
('delivered', 'Delivered', 'Pesanan Diterima', '#22C55E', 9, 'Package successfully delivered to customer')
ON DUPLICATE KEY UPDATE 
    status_name=VALUES(status_name),
    status_name_id=VALUES(status_name_id);

-- 9. Update existing orders - Set subtotal and shipping_cost
UPDATE orders 
SET subtotal = total_amount,
    shipping_cost = 0,
    current_status_detail = CASE 
        WHEN status = 'Pending' THEN 'order_placed'
        WHEN status = 'Processing' THEN 'payment_confirmed'
        WHEN status = 'Shipped' THEN 'in_transit'
        WHEN status = 'Delivered' THEN 'delivered'
        ELSE 'order_placed'
    END
WHERE subtotal IS NULL OR subtotal = 0;

-- 10. Create indexes for better performance (if not exists)
CREATE INDEX IF NOT EXISTS idx_orders_status_detail ON orders(current_status_detail);
CREATE INDEX IF NOT EXISTS idx_orders_shipping_service ON orders(shipping_service_id);
CREATE INDEX IF NOT EXISTS idx_orders_estimated_delivery ON orders(estimated_delivery_date);
CREATE INDEX IF NOT EXISTS idx_history_status_detail ON order_history(status_detail);

